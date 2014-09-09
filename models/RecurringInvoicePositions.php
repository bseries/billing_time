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
		'source' => 'billing_timering_invoice_positions'
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

	public function amount($entity, $taxZone = null) {
		return new Price(
			$entity->amount,
			$entity->amount_currency,
			$entity->amount_type,
			$taxZone ?: $entity->user()->taxZone()
		);
	}

	public function totalAmount($entity, $taxZone = null) {
		return $entity->amount($taxZone)->multiply($entity->quantity);
	}
}

?>