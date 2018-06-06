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

namespace billing_time\controllers;

use billing_core\models\Currencies;
use billing_core\billing\TaxTypes;
use billing_time\models\RecurringInvoicePositions;
use li3_flash_message\extensions\storage\FlashMessage;
use lithium\g11n\Message;

class RecurringInvoicePositionsController extends \base_core\controllers\BaseController {

	use \base_core\controllers\AdminIndexTrait;
	use \base_core\controllers\AdminAddTrait;
	use \base_core\controllers\AdminEditTrait;
	use \base_core\controllers\AdminDeleteTrait;
	use \base_core\controllers\AdminActivateTrait;
	use \base_core\controllers\UsersTrait;

	public function admin_place() {
		extract(Message::aliases());

		$item = RecurringInvoicePositions::find('first', [
			'conditions' => ['id' => $this->request->id]
		]);
		if ($item->mustPlace() && $item->place()) {
			FlashMessage::write($t('Successfully placed position.', ['scope' => 'base_core']), [
				'level' => 'success'
			]);
		} else {
			FlashMessage::write($t('Failed to place position.', ['scope' => 'base_core']), [
				'level' => 'error'
			]);
		}
		return $this->redirect($this->request->referer());
	}

	protected function _selects($item = null) {
		extract(Message::aliases());

		$frequencies = RecurringInvoicePositions::enum('frequency', [
			'monthly' => $t('monthly', ['scope' => 'billing_time']),
			'yearly' => $t('yearly', ['scope' => 'billing_time']),
			'2-yearly' => $t('biyearly', ['scope' => 'billing_time']),
			'3-yearly' => $t('every 3 years', ['scope' => 'billing_time']),
			'4-yearly' => $t('every 4 years', ['scope' => 'billing_time'])
		]);

		if ($item) {
			$users = $this->_users($item, ['field' => 'user_id', 'empty' => true]);
			$currencies = Currencies::find('list');
			$taxTypes = TaxTypes::enum();

			return compact('currencies', 'users', 'frequencies', 'taxTypes');
		}
		return compact('frequencies');
	}
}

?>