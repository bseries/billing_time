<?php
/**
 * Billing Time
 *
 * Copyright (c) 2018 Atelier Disko - All rights reserved.
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
			$t('year average', [
				'scope' =>'billing_time'
			]) => $formatter->format(RecurringInvoicePositions::averageRecurringPerYear()->getNet())
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

