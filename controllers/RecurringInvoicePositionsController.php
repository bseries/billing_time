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

namespace billing_time\controllers;

use base_core\models\VirtualUsers;
use base_core\models\Users;
use lithium\g11n\Message;
use base_core\models\Currencies;
use billing_time\models\RecurringInvoicePositions;
use billing_core\models\TaxTypes;

class RecurringInvoicePositionsController extends \base_core\controllers\BaseController {

	use \base_core\controllers\AdminIndexTrait;
	use \base_core\controllers\AdminAddTrait;
	use \base_core\controllers\AdminEditTrait;
	use \base_core\controllers\AdminDeleteTrait;
	use \base_core\controllers\AdminActivateTrait;

	protected function _selects($item = null) {
		extract(Message::aliases());

		$currencies = Currencies::find('list');
		$virtualUsers = [null => '-'] + VirtualUsers::find('list', ['order' => 'name']);
		$users = [null => '-'] + Users::find('list', ['order' => 'name']);
		$frequencies = RecurringInvoicePositions::enum('frequency', [
			'monthly' => $t('monthly', ['scope' => 'billing_time']),
			'yearly' => $t('yearly', ['scope' => 'billing_time'])
		]);

		if ($item) {
			$taxTypes = TaxTypes::find('list');
		}

		return compact('currencies', 'users', 'virtualUsers', 'frequencies', 'taxTypes');
	}
}

?>