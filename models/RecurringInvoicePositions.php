<?php
/**
 * Billing Time
 *
 * Copyright (c) 2014 Atelier Disko - All rights reserved.
 *
 * Licensed under the AD General Software License v1.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *
 * You should have received a copy of the AD General Software
 * License. If not, see https://atelierdisko.de/licenses.
 */

namespace billing_time\models;

use AD\Finance\Price;
use AD\Finance\Price\Prices;
use DateTime;
use DateInterval;
use Exception;
use billing_invoice\models\InvoicePositions;

class RecurringInvoicePositions extends \base_core\models\Base {

	protected $_meta = [
		'source' => 'billing_recurring_invoice_positions'
	];

	public $belongsTo = [
		'User' => [
			'to' => 'base_core\models\Users',
			'key' => 'user_id'
		]
	];

	protected $_actsAs = [
		'base_core\extensions\data\behavior\RelationsPlus',
		'base_core\extensions\data\behavior\Timestamp',
		'base_core\extensions\data\behavior\Localizable' => [
			'fields' => [
				'amount' => 'money',
				'quantity' => 'decimal'
			]
		],
		'li3_taggable\extensions\data\behavior\Taggable' => [
			'field' => 'tags',
			'tagsModel' => 'base_tag\models\Tags',
			'filters' => ['strtolower']
		],
		'base_core\extensions\data\behavior\Searchable' => [
			'fields' => [
				'User.number',
				'User.name',
				'description',
				'quantity',
				'runs',
				'frequency',
				'tags'
			]
		]
	];

	public static $enum = [
		'frequency' => [
			'monthly',
			'yearly',
			'2-yearly',
			'3-yearly',
			'4-yearly'
		]
	];

	public function amount($entity) {
		return new Price(
			(integer) $entity->amount,
			$entity->amount_currency,
			$entity->amount_type,
			(integer) $entity->amount_rate
		);
	}

	public function totalAmount($entity) {
		return $entity->amount()->multiply($entity->quantity);
	}

	// Returns the next DateTime the position is scheduled to run. The method may return `false`,
	// to indicate there isn't a scheduled time, yet.
	public function nextRun($entity, $now = null) {
		$now = $now ?: new DateTime();

		// Consider for placement only when first_run has been reeached. Conditionally
		// checking, as first_run is optional.
		if ($entity->first_run) {
			$first = DateTime::createFromFormat('Y-m-d H:i:s', $entity->first_run);

			// first_run is inclusive, will run once reached date
			if ($now < $first) {
				return $first;
			}
		}

		// Once considered, generate an initial run immediately.
		if (!$entity->ran) {
			return $now;
		}

		// Caclulate the next date we should be placed according to given frequency.
		$last = DateTime::createFromFormat('Y-m-d H:i:s', $entity->ran);

		$interval = null;
		if (preg_match('/^([a-z]+)ly$/', $entity->frequency, $matches)) {
			$interval = "1 {$matches[1]}";
		} elseif (preg_match('/^([2-9]+)-([a-z]+)(ly?)$/', $entity->frequency, $matches)) {
			$interval = "{$matches[1]} {$matches[2]}s";
		} else {
			$message = "Failed to map frequency {$entity->frequency} to interval.";
			throw new Exception($message);
		}
		return $last->add(DateInterval::createFromDateString($interval));
	}

	public function mustPlace($entity, $now = null) {
		$now = $now ?: new DateTime();
		$next = $entity->nextRun($now);

		if ($next === false) {
			return false;
		}
		return $next <= $now;
	}

	public function place($entity) {
		$position = InvoicePositions::create(array_intersect_key($entity->data(), [
			'user_id' => null,
			'description' => null, 'quantity' => null,
			'amount_rate' => null, 'amount_type' => null, 'amount' => null,
			'tags' => null
		]));

		if (!$position->save(null, ['localize' => false])) {
			return false;
		}
		return $entity->save([
			'ran' => date('Y-m-d H:i:s'),
			'runs' => $entity->runs + 1
		], ['whitelist' => ['ran', 'runs']]);
	}

	/* Statistics */

	public static function averageRecurringPerYear() {
		$total = new Prices();

		foreach (static::$enum['frequency'] as $frequency) {
			$multiplier = null;
			$divider = null;

			if ($frequency === 'monthly') {
				$multiplier = 12;
			} elseif ($frequency === 'yearly') {
				$multiplier = 1;
			} elseif (preg_match('/^([2-9]+)-monthly$/', $frequency, $matches)) {
				$multiplier = 12 / $matches[1];
			} elseif (preg_match('/^([2-9]+)-yearly$/', $frequency, $matches)) {
				$divider = $matches[1];
			} else {
				$message = "Failed to map frequency {$entity->frequency} to interval.";
				throw new Exception($message);
			}

			$data = static::find('all', [
				'conditions' => [
					'frequency' => $frequency
				],
				'fields' => [
					'amount_currency',
					'amount_type',
					'amount_rate',
					'ROUND(SUM(amount * quantity)' . ($divider ? " / {$divider}" : "* {$multiplier}") . ') AS amount'
				],
				'group' => [
					'amount_currency',
					'amount_type',
					'amount_rate'
				],
			]);
			foreach ($data as $item) {
				$total = $total->add($item->amount());
			}
		}
		return $total;
	}
}

?>
