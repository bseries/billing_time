<?php

use base_core\extensions\cms\Features;

$this->set([
	'page' => [
		'type' => 'single',
		'title' => null,
		'empty' => false,
		'object' => $t('recurring invoice position')
	],
	'meta' => [
		'is_active' => $item->is_active ? $t('active') : $t('inactive'),
	]
]);

?>
<article class="view-<?= $this->_config['controller'] . '-' . $this->_config['template'] ?>">

	<?=$this->form->create($item) ?>
		<?= $this->form->field('id', ['type' => 'hidden']) ?>

		<div class="grid-row">
			<div class="grid-column-left">
				<?= $this->form->field('description', [
					'type' => 'text',
					'label' => $t('Description')
				]) ?>
				<?= $this->form->field('frequency', [
					'type' => 'select',
					'list' => $frequencies,
					'label' => $t('Frequency')
				]) ?>
			</div>
			<div class="grid-column-right">
				<?= $this->form->field('created', [
					'type' => 'date',
					'label' => $t('Created'),
					'value' => $item->created ?: date('Y-m-d'),
					'disabled' => true
				]) ?>
			</div>
		</div>
		<div class="grid-row">
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

		<div class="grid-row grid-row-last">
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
				<?= $this->form->field('amount', [
					'type' => 'text',
					'label' => $t('Amount'),
					'value' => $item->exists() ? $this->money->format($item->amount(), 'decimal') : null
				]) ?>
			</div>
			<div class="grid-column-right">
				<?= $this->form->field('total_net', [
					'type' => 'text',
					'disabled' => true,
					'label' => $t('Total amount (net)'),
					'value' => $item->exists() ? $this->money->format($item->totalAmount(), 'decimal') : null
				]) ?>
			</div>
		</div>

		<div class="bottom-actions">
			<?php if ($item->exists()): ?>
				<?php if ($item->is_active): ?>
					<?= $this->html->link($t('deactivate'), ['id' => $item->id, 'action' => 'deactivate', 'library' => 'billing_recur'], ['class' => 'button large']) ?>
				<?php else: ?>
					<?= $this->html->link($t('activate'), ['id' => $item->id, 'action' => 'activate', 'library' => 'billing_recur'], ['class' => 'button large']) ?>
				<?php endif ?>
			<?php endif ?>
			<?= $this->form->button($t('save'), ['type' => 'submit', 'class' => 'save large']) ?>
		</div>

	<?=$this->form->end() ?>
</article>