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
}

?>