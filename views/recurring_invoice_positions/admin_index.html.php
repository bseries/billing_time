<?php

$this->set([
	'page' => [
		'type' => 'multiple',
		'object' => $t('scheduled invoice positions')
	]
]);

?>
<article class="view-<?= $this->_config['controller'] . '-' . $this->_config['template'] ?> use-list">

	<div class="top-actions">
		<?= $this->html->link($t('recur a new invoice position'), ['action' => 'add', 'library' => 'billing_time'], ['class' => 'button add']) ?>
	</div>

	<?php if ($data->count()): ?>
		<table>
			<thead>
				<tr>
					<td data-sort="is-active" class="is-active flag list-sort"><?= $t('Active?') ?>
					<td data-sort="user" class="user list-sort"><?= $t('User') ?>
					<td data-sort="frequency" class="list-sort"><?= $t('Frequency') ?>
					<td data-sort="quantity" class="quantity list-sort"><?= $t('Quantity') ?>
					<td data-sort="quantity" class="quantity list-sort"><?= $t('Quantity') ?>
					<td data-sort="total-net" class="total-net list-sort"><?= $t('Total (net)') ?>
					<td data-sort="created" class="date created list-sort"><?= $t('Created') ?>
					<td class="actions">
						<?= $this->form->field('search', [
							'type' => 'search',
							'label' => false,
							'placeholder' => $t('Filter'),
							'class' => 'list-search'
						]) ?>
			</thead>
			<tbody class="list">
				<?php foreach ($data as $item): ?>
					<?php $user = $item->user() ?>
				<tr data-id="<?= $item->id ?>">
					<td class="is-active flag"><?= $item->is_active ? '✓ ' : '×' ?>
					<td class="user">
						<?php if ($user): ?>
							<?= $this->html->link($user->number, [
								'controller' => $user->isVirtual() ? 'VirtualUsers' : 'Users',
								'action' => 'edit', 'id' => $user->id,
								'library' => 'base_core'
							]) ?>
						<?php else: ?>
							-
						<?php endif ?>
					<td class="frequency"><?= $frequencies[$item->frequency] ?>
					<td class="description"><?= $item->description ?>
					<td class="quantity"><?= $this->number->format($item->quantity, 'decimal') ?>
					<td><?= ($money = $item->totalAmount()) ? $this->money->format($money->getNet(), 'money') : null ?>
					<td class="date created">
						<time datetime="<?= $this->date->format($item->created, 'w3c') ?>">
							<?= $this->date->format($item->created, 'date') ?>
						</time>
					<td class="actions">
						<?php if ($item->is_active): ?>
							<?= $this->html->link($t('deactivate'), ['id' => $item->id, 'action' => 'deactivate', 'library' => 'billing_time'], ['class' => 'button']) ?>
						<?php else: ?>
							<?= $this->html->link($t('activate'), ['id' => $item->id, 'action' => 'activate', 'library' => 'billing_time'], ['class' => 'button']) ?>
						<?php endif ?>
						<?= $this->html->link($t('open'), ['id' => $item->id, 'action' => 'edit', 'library' => 'billing_time'], ['class' => 'button']) ?>
				<?php endforeach ?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="none-available"><?= $t('No items available, yet.') ?></div>
	<?php endif ?>
</article>