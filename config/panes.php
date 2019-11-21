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