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

use base_core\extensions\cms\Jobs;
use base_core\models\Users;
use billing_core\models\Invoices;
use billing_time\models\ScheduledInvoicePositions;
use billing_time\models\RecurringInvoicePositions;

Jobs::recur('invoice_place_timed', function() {
	Invoices::pdo()->beginTransaction();

	$positions = ScheduledInvoicePositions::find('all'/*, [
		'conditions' => [
			'execute_on' => ['>=' => date('Y-m-d')]
		]
	] */);
	foreach ($positions as $position) {
		if (!$position->place()) {
			Invoices::pdo()->rollback();
			return false;
		}
	}

	$positions = RecurringInvoicePositions::find('all', [
		'conditions' => [
			'is_active' => true
		]
	]);
	foreach ($positions as $position) {
		if (!$position->place()) {
			Invoices::pdo()->rollback();
			return false;
		}
	}

	$users = Users::find('all', [
		'conditions' => [
			// 'is_auto_billed' => true
			// 'is_active' => true
		]
	]);
	foreach ($users as $user) {
		$invoice = Invoices::generateFromPending($user);

		if ($invoice === null) {
			continue; // No pending positions, no invoice to send.
		}
		if ($invoice === false) {
			Invoices::pdo()->rollback();
			return false;
		}
		if (!$invoice->send()) {
			Invoices::pdo()->rollback();
			return false;
		}
	}

	Invoices::pdo()->commit();
}, [
	'frequency' => Jobs::FREQUENCY_LOW
]);

?>