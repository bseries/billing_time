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
 * License. If not, see http://atelierdisko.de/licenses.
 */

namespace billing_time\models;

use AD\Finance\Price;
use DateTime;
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
			'yearly'
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

	public function mustPlace($entity) {
		$now = new DateTime();

		if ($entity->first_run) {
			$first = DateTime::createFromFormat('Y-m-d H:i:s', $entity->first_run);

			// first_run is inclusive, will run once reached date
			if ($now->getTimestamp() < $first->getTimestamp()) {
				return false;
			}
		}
		if (!$entity->ran) {
			return true;
		}
		$last = DateTime::createFromFormat('Y-m-d H:i:s', $entity->ran);
		$diff = $last->diff($now);

		// FIXME Check if this should be >= 0
		switch ($entity->frequency) {
			case 'monthly':
				return $diff->m >= 1;
			case 'yearly':
				return $diff->y >= 1;
		}
		return false;
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
}

?>