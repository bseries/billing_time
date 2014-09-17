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

// In the moment of generating an invoice position the price is finalized.
class ScheduledInvoicePositions extends \base_core\models\Base {

	use \base_core\models\UserTrait;

	protected $_meta = [
		'source' => 'billing_scheduled_invoice_positions'
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

	// After placing the entity it is deleted.
	public function place($entity) {
		$user = $entity->user();

		$position = InvoicePositions::create(array_intersect_key($entity->data(), [
			'user_id', 'virtual_user_id',
			'description', 'quantity',
			'tax_type', 'tax_rate', 'amount_type', 'amount'
		]));

		if (!$position->save()) {
			return false;
		}
		return $entity->delete();
	}
}

?>