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
			$users = $this->_users($item, ['field' => 'user_id', 'empty' => true]);
			$currencies = Currencies::find('list');
			$taxTypes = TaxTypes::enum();

			return compact('currencies', 'users', 'taxTypes');
		}
		return [];
	}
}

?>