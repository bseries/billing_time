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

use Finance\Price;

class RecurringInvoicePositions extends \base_core\models\Base {

	use \base_core\models\UserTrait;

	protected $_meta = [
		'source' => 'billing_recurring_invoice_positions'
	];

	protected static $_actsAs = [
		'base_core\extensions\data\behavior\Timestamp',
		'base_core\extensions\data\behavior\Localizable' => [
			'fields' => [
				'amount' => 'money',
				'quantity' => 'decimal'
			]
		]
	];

	public static $enum = [
		'frequency' => [
			'monthly'
		]
	];

	public function amount($entity) {
		return new Price(
			(integer) $entity->amount,
			$entity->amount_currency,
			$entity->amount_type,
			(integer) $entity->tax_rate
		);
	}

	public function totalAmount($entity) {
		return $entity->amount()->multiply($entity->quantity);
	}

	public function mustPlace($entity) {
		if (!$entity->ran) {
			return true;
		}
		$last = DateTime::createFromFormat('Y-m-d', $entity->ran);
		$diff = $last->diff(new DateTime());

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
			'user_id', 'virtual_user_id',
			'description', 'quantity',
			'tax_type', 'tax_rate', 'amount_type', 'amount'
		]));

		if (!$position->save()) {
			return false;
		}
		return $entity->save([
			'ran' => date('Y-m-d'),
			'runs' => $entity->runs + 1
		], ['whitelist' => ['ran', 'runs']]);
	}
}

?>