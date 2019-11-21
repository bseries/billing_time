<?php
/**
 * Billing Time
 *
 * Copyright (c) 2014 David Persson - All rights reserved.
 * Copyright (c) 2016 Atelier Disko - All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
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