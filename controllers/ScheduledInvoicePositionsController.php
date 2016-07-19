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
 * License. If not, see http://atelierdisko.de/licenses.
 */

namespace billing_time\controllers;

use billing_core\models\Currencies;
use billing_core\billing\TaxTypes;

class ScheduledInvoicePositionsController extends \base_core\controllers\BaseController {

	use \base_core\controllers\AdminIndexTrait;
	use \base_core\controllers\AdminAddTrait;
	use \base_core\controllers\AdminEditTrait;
	use \base_core\controllers\AdminDeleteTrait;
	use \base_core\controllers\AdminActivateTrait;
	use \base_core\controllers\UsersTrait;

	protected function _selects($item = null) {
		if ($item) {
			$this->_users($item, ['field' => 'user_id', 'empty' => true]);
			$currencies = Currencies::find('list');
			$taxTypes = TaxTypes::enum();

			return compact('currencies', 'users', 'taxTypes');
		}
		return [];
	}
}

?>