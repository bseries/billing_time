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

use base_core\extensions\cms\Panes;
use lithium\g11n\Message;

extract(Message::aliases());

Panes::register('billing.scheduled', [
	'title' => $t('Scheduled'),
	'url' => ['controller' => 'ScheduledInvoicePositions', 'action' => 'index', 'library' => 'billing_time', 'admin' => true],
	'weight' => 44
]);

Panes::register('billing.recurring', [
	'title' => $t('Recurring'),
	'url' => ['controller' => 'RecurringInvoicePositions', 'action' => 'index', 'library' => 'billing_time', 'admin' => true],
	'weight' => 45
]);

?>