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

use base_core\extensions\cms\Panes;
use lithium\g11n\Message;

extract(Message::aliases());

Panes::register('billing.scheduled', [
	'title' => $t('Scheduled', ['scope' => 'billing_time']),
	'url' => ['controller' => 'ScheduledInvoicePositions', 'action' => 'index', 'library' => 'billing_time', 'admin' => true],
	'weight' => 44
]);

Panes::register('billing.recurring', [
	'title' => $t('Recurring', ['scope' => 'billing_time']),
	'url' => ['controller' => 'RecurringInvoicePositions', 'action' => 'index', 'library' => 'billing_time', 'admin' => true],
	'weight' => 45
]);

?>