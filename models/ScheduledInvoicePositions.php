<?php
/**
 * Billing Recur
 *
 * Copyright (c) 2014 Atelier Disko - All rights reserved.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 */

namespace billing_recur\models;

// In the moment of generating an invoice position the price is finalized.
class ScheduledInvoicePositions extends \cms_core\models\Base {

	use \cms_core\models\UserTrait;

	protected $_meta = [
		'source' => 'billing_scheduled_invoice_positions'
	];

	protected static $_actsAs = [
		'cms_core\extensions\data\behavior\Timestamp',
		'cms_core\extensions\data\behavior\Localizable' => [
			'fields' => [
				'amount' => 'money',
				'quantity' => 'decimal'
			]
		]
	];
}

?>