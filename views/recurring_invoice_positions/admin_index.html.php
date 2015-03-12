<?php

use lithium\g11n\Message;

$t = function($message, array $options = []) {
	return Message::translate($message, $options + ['scope' => 'billing_time', 'default' => $message]);
};

$this->set([
	'page' => [
		'type' => 'multiple',
		'object' => $t('recurring invoice positions')
	]
]);

?>
<article
	class="use-index-table"
	data-endpoint-sort="<?= $this->url([
		'action' => 'index',
		'page' => $paginator->getPages()->current,
		'orderField' => '__ORDER_FIELD__',
		'orderDirection' => '__ORDER_DIRECTION__'
	]) ?>"
>

	<div class="top-actions">
		<?= $this->html->link($t('recur a new invoice position'), ['action' => 'add', 'library' => 'billing_time'], ['class' => 'button add']) ?>
	</div>

	<?php if ($data->count()): ?>
		<table>
			<thead>
				<tr>
					<td data-sort="is-active" class="is-active flag table-sort"><?= $t('Active?') ?>
					<td data-sort="user" class="user table-sort"><?= $t('User') ?>
					<td data-sort="frequency" class="table-sort"><?= $t('Frequency') ?>
					<td data-sort="description" class="description table-sort"><?= $t('Description') ?>
					<td data-sort="quantity" class="quantity table-sort"><?= $t('Quantity') ?>
					<td><?= $t('Total (net)') ?>
					<td data-sort="modified" class="date modified table-sort desc"><?= $t('Modified') ?>
					<td class="actions">
			</thead>
			<tbody>
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
					<td class="date modified">
						<time datetime="<?= $this->date->format($item->modified, 'w3c') ?>">
							<?= $this->date->format($item->modified, 'date') ?>
						</time>
					<td class="actions">
						<?= $this->html->link($t('delete'), ['id' => $item->id, 'action' => 'delete', 'library' => 'billing_time'], ['class' => 'button']) ?>

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

	<?=$this->view()->render(['element' => 'paging'], compact('paginator'), ['library' => 'base_core']) ?>
</article>