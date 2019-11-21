<?php
/**
 * Billing Time
 *
 * Copyright (c) 2018 Atelier Disko - All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace billing_invoice\config;

use AD\Finance\Money\MoneyIntlFormatter as MoneyFormatter;
use AD\Finance\Money\MoniesIntlFormatter as MoniesFormatter;
use base_core\extensions\cms\Widgets;
use billing_invoice\models\InvoicePositions;
use billing_invoice\models\Invoices;
use lithium\core\Environment;
use lithium\g11n\Message;

use billing_time\models\RecurringInvoicePositions;

extract(Message::aliases());

Widgets::register('recurring', function() use ($t) {
	$formatter = new MoniesFormatter(Environment::get('locale'));

	return [
		'title' => $t('Recurring invoice positions', ['scope' => 'billing_time']),
		'data' => [
			$t('year average ({:year}, ongoing)', [
				'scope' => 'billing_time',
				'year' => date('Y')
			]) => $formatter->format(RecurringInvoicePositions::averageRecurringPerYear(date('Y'))->getNet()),
			$t('year average ({:year})', [
				'scope' => 'billing_time',
				'year' => date('Y') - 1
			]) => $formatter->format(RecurringInvoicePositions::averageRecurringPerYear(date('Y') - 1)->getNet())
		],
		'url' => [
			'library' => 'billing_invoice',
			'controller' => 'Invoices',
			'action' => 'index'
		]
	];
}, [
	'type' => Widgets::TYPE_COUNTER,
	'group' => Widgets::GROUP_DASHBOARD
]);

?>

