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

namespace billing_time\config;

use base_core\extensions\cms\Jobs;
use base_core\models\Users;
use billing_core\models\Invoices;
use billing_time\models\ScheduledInvoicePositions;
use billing_time\models\RecurringInvoicePositions;

Jobs::recur('billing_time:invoice_place_timed', function() {
	Invoices::pdo()->beginTransaction();

	$positions = RecurringInvoicePositions::find('all', [
		'conditions' => [
			'is_active' => true
		]
	]);
	foreach ($positions as $position) {
		if (!$position->mustPlace()) {
			continue;
		}
		if (!$position->place()) {
			// FIXME increment runs.
			Invoices::pdo()->rollback();
			return false;
		}
	}
	Invoices::pdo()->commit();
}, [
	'frequency' => Jobs::FREQUENCY_LOW
]);

?>