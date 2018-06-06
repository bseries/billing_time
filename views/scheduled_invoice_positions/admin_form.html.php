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
		'must_place' => $item->mustPlace() ? $t('must place') : null
	]
]);

?>
<article>
	<?=$this->form->create($item) ?>
		<?php if ($item->exists()): ?>
			<?= $this->form->field('id', ['type' => 'hidden']) ?>
		<?php endif ?>

		<div class="grid-row">
			<h1 class="h-gamma"><?= $t('Recipient') ?></h1>

			<div class="grid-column-left"></div>
			<div class="grid-column-right">
				<?= $this->form->field('user_id', [
					'type' => 'select',
					'label' => $t('User'),
					'list' => $users
				]) ?>
			</div>
		</div>

		<div class="grid-row">
			<h1 class="h-gamma"><?= $t('Execution') ?></h1>

			<div class="grid-column-left">
				<?= $this->form->field('run_on', [
					'type' => 'datetime',
					'label' => $t('run on'),
					'value' => $item->run_on ?: date('Y-m-d') . ' 14:00:00',
				]) ?>
				<div class="help"><?= $t('The date and time on which the position will be placed.') ?></div>
			</div>
			<div class="grid-column-right"></div>
		</div>

		<div class="grid-row">
			<div class="grid-column-left">
				<?= $this->form->field('description', [
					'type' => 'textarea',
					'label' => $t('Description'),
					'maxlength' => 250
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
					'value' => $item->amount_currency ?: 'EUR'
				]) ?>
				<?= $this->form->field('amount_type', [
					'type' => 'select',
					'label' => $t('Amount type'),
					'value' => $item->amount_type ?: 'net',
					'list' => ['net' => $t('net'), 'gross' => $t('gross')]
				]) ?>
				<?= $this->form->field('amount', [
					'type' => 'text',
					'label' => $t('Amount'),
					'placeholder' => $this->money->format(0, ['currency' => false]),
					'value' => $item->exists() ? $this->money->format($item->amount(), ['currency' => false]) : null
				]) ?>
				<?= $this->form->field('amount_rate', [
					'type' => 'text',
					'label' => $t('Tax rate (%)'),
					'value' => $item->amount_rate ?: '19'
				]) ?>
			</div>
			<div class="grid-column-right">
			</div>
		</div>

		<div class="bottom-actions">
			<div class="bottom-actions__left">
				<?php if ($item->exists()): ?>
					<?= $this->html->link($t('delete'), [
						'action' => 'delete', 'id' => $item->id
					], ['class' => 'button large delete']) ?>
				<?php endif ?>
			</div>
			<div class="bottom-actions__right">
				<?php if ($item->exists()): ?>
					<?= $this->html->link(
						$item->is_active ? $t('deactivate') : $t('activate'),
						['id' => $item->id, 'action' => $item->is_active ? 'deactivate' : 'activate'],
						['class' => 'button large']
					) ?>
				<?php endif ?>

				<?= $this->form->button($t('save'), [
					'type' => 'submit',
					'class' => 'button large save'
				]) ?>
			</div>
		</div>

	<?=$this->form->end() ?>
</article>
