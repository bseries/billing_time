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

namespace billing_time\config;

use base_core\async\Jobs;
use billing_invoice\models\Invoices;

Jobs::recur('billing_time:invoice_place_timed', function() {
	Invoices::pdo()->beginTransaction();

	$models = [
		'billing_time\models\RecurringInvoicePositions',
		'billing_time\models\ScheduledInvoicePositions'
	];
	foreach ($models as $model) {
		$positions = $model::find('all', [
			'conditions' => [
				'is_active' => true
			]
		]);
		foreach ($positions as $position) {
			if (!$position->mustPlace()) {
				continue;
			}
			if (!$position->place()) {
				Invoices::pdo()->rollback();
				return false;
			}
		}
	}

	Invoices::pdo()->commit();
	return true;
}, [
	'frequency' => Jobs::FREQUENCY_LOW
]);

?>