<?php

use lithium\g11n\Message;

$t = function($message, array $options = []) {
	return Message::translate($message, $options + ['scope' => 'billing_time', 'default' => $message]);
};


$this->set([
	'page' => [
		'type' => 'single',
		'title' => null,
		'empty' => false,
		'object' => $t('scheduled invoice position')
	],
	'meta' => [
		'is_active' => $item->is_active ? $t('active') : $t('inactive'),
	]
]);

?>
<article>

	<?=$this->form->create($item) ?>
		<?= $this->form->field('id', ['type' => 'hidden']) ?>

		<div class="grid-row">
			<h1 class="h-gamma"><?= $t('Recipient') ?></h1>

			<div class="grid-column-left"></div>
			<div class="grid-column-right">
				<div class="compound-users">
					<?php
						$user = $item->exists() ? $item->user() : false;
					?>
					<?= $this->form->field('user_id', [
						'type' => 'select',
						'label' => $t('User'),
						'list' => $users,
						'class' => !$user || !$user->isVirtual() ? null : 'hide'
					]) ?>
					<?= $this->form->field('virtual_user_id', [
						'type' => 'select',
						'label' => false,
						'list' => $virtualUsers,
						'class' => $user && $user->isVirtual() ? null : 'hide'
					]) ?>
					<?= $this->form->field('user.is_real', [
						'type' => 'checkbox',
						'label' => $t('real user'),
						'checked' => $user ? !$user->isVirtual() : true
					]) ?>
				</div>
			</div>
		</div>

		<div class="grid-row">
			<h1 class="h-gamma"><?= $t('Execution') ?></h1>

			<div class="grid-column-left">
				<?= $this->form->field('run_on', [
					'type' => 'datetime',
					'label' => $t('run on'),
					'value' => $item->run_on ?: date('Y-m-d H:i:s'),
				]) ?>
			</div>
			<div class="grid-column-right"></div>
		</div>

		<div class="grid-row">
			<div class="grid-column-left">
				<?= $this->form->field('description', [
					'type' => 'textarea',
					'label' => $t('Description')
				]) ?>
			</div>
			<div class="grid-column-right">
				<?= $this->form->field('tags', [
					'value' => $item->tags(),
					'label' => $t('Tags'),
					'placeholder' => 'foo, bar',
					'class' => 'input--tags'
				]) ?>
			</div>
		</div>

		<div class="grid-row">
			<div class="grid-column-left">
				<?= $this->form->field('quantity', [
					'type' => 'text',
					'label' => $t('Quantity'),
					'value' => $this->number->format($item->quantity, 'decimal'),
				]) ?>
				<?= $this->form->field('amount_currency', [
					'type' => 'select',
					'label' => $t('Amount currency'),
					'list' => $currencies,
					'value' => $item->amount_currency
				]) ?>
				<?= $this->form->field('amount_type', [
					'type' => 'select',
					'label' => $t('Amount type'),
					'value' => $item->amount_type,
					'list' => ['net' => $t('net'), 'gross' => $t('gross')]
				]) ?>
				<?= $this->form->field('amount_rate', [
					'type' => 'text',
					'label' => $t('Tax rate')
				]) ?>
				<?= $this->form->field('amount', [
					'type' => 'text',
					'label' => $t('Amount'),
					'value' => $item->exists() ? $this->money->format($item->amount(), ['currency' => false]) : null
				]) ?>
			</div>
			<div class="grid-column-right">
			</div>
		</div>

		<div class="bottom-actions">
			<?php if ($item->exists()): ?>
				<?php if ($item->is_active): ?>
					<?= $this->html->link($t('deactivate'), ['id' => $item->id, 'action' => 'deactivate', 'library' => 'billing_time'], ['class' => 'button large']) ?>
				<?php else: ?>
					<?= $this->html->link($t('activate'), ['id' => $item->id, 'action' => 'activate', 'library' => 'billing_time'], ['class' => 'button large']) ?>
				<?php endif ?>
			<?php endif ?>
			<?= $this->form->button($t('save'), ['type' => 'submit', 'class' => 'save large']) ?>
		</div>

	<?=$this->form->end() ?>
</article>