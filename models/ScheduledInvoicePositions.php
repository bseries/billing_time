<?php
/**
 * Billing Time
 *
 * Copyright (c) 2014 Atelier Disko - All rights reserved.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 */

namespace billing_time\models;

use AD\Finance\Price;

// In the moment of generating an invoice position the price is finalized.
class ScheduledInvoicePositions extends \base_core\models\Base {

	protected $_meta = [
		'source' => 'billing_scheduled_invoice_positions'
	];

	public $belongsTo = [
		'User' => [
			'to' => 'base_core\models\Users',
			'key' => 'user_id'
		],
		'VirtualUser' => [
			'to' => 'base_core\models\VirtualUsers',
			'key' => 'virtual_user_id'
		]
	];

	protected $_actsAs = [
		'base_core\extensions\data\behavior\User',
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
				'description',
				'quantity'
			]
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
		$now = DateTime();
		$will = DateTime::createFromFormat('Y-m-d H:i:s', $entity->run_on);

		return $will <= $now;
	}

	public function place($entity) {
		$position = InvoicePositions::create(array_intersect_key($entity->data(), [
			'user_id' => null, 'virtual_user_id' => null,
			'description' => null, 'quantity' => null,
			'amount_rate' => null, 'amount_type' => null, 'amount' => null,
			'tags' => null
		]));

		return $position->save(null, ['localize' => false]) && $entity->delete();
	}
}

?>