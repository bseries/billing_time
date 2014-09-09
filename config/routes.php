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

use lithium\net\http\Router;

$persist = ['persist' => ['admin', 'controller']];

Router::connect('/admin/billing/scheduled/{:id:[0-9]+}', [
	'controller' => 'ScheduledInvoicePositions', 'library' => 'billing_time', 'action' => 'view', 'admin' => true
], $persist);
Router::connect('/admin/billing/scheduled/{:action}', [
	'controller' => 'ScheduledInvoicePositions', 'library' => 'billing_time', 'admin' => true
], $persist);
Router::connect('/admin/billing/scheduled/{:action}/{:id:[0-9]+}', [
	'controller' => 'ScheduledInvoicePositions', 'library' => 'billing_time', 'admin' => true
], $persist);

Router::connect('/admin/billing/recurring/{:id:[0-9]+}', [
	'controller' => 'RecurringInvoicePositions', 'library' => 'billing_time', 'action' => 'view', 'admin' => true
], $persist);
Router::connect('/admin/billing/recurring/{:action}', [
	'controller' => 'RecurringInvoicePositions', 'library' => 'billing_time', 'admin' => true
], $persist);
Router::connect('/admin/billing/recurring/{:action}/{:id:[0-9]+}', [
	'controller' => 'RecurringInvoicePositions', 'library' => 'billing_time', 'admin' => true
], $persist);

?>