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
	class="use-rich-index"
	data-endpoint="<?= $this->url([
		'action' => 'index',
		'page' => '__PAGE__',
		'orderField' => '__ORDER_FIELD__',
		'orderDirection' => '__ORDER_DIRECTION__',
		'filter' => '__FILTER__'
	]) ?>"
>

	<div class="top-actions">
		<?= $this->html->link($t('recur position'), ['action' => 'add'], ['class' => 'button add']) ?>
	</div>

	<?php if ($data->count()): ?>
		<table>
			<thead>
				<tr>
					<td data-sort="is-active" class="flag table-sort"><?= $t('Active?') ?>
					<td data-sort="user.number" class="user table-sort"><?= $t('User') ?>
					<td data-sort="frequency" class="table-sort"><?= $t('Frequency') ?>
					<td data-sort="runs" class="table-sort"><?= $t('Runs') ?>
					<td data-sort="ran" class="table-sort"><?= $t('Ran') ?>
					<td data-sort="description" class="description table-sort"><?= $t('Description') ?>
					<td><?= $t('Total (net)') ?>
					<td data-sort="modified" class="date modified table-sort desc"><?= $t('Modified') ?>
					<td class="actions">
						<?= $this->form->field('search', [
							'type' => 'search',
							'label' => false,
							'placeholder' => $t('Filter'),
							'class' => 'table-search',
							'value' => $this->_request->filter
						]) ?>
			</thead>
			<tbody>
				<?php foreach ($data as $item): ?>
					<?php $user = $item->user() ?>
				<tr data-id="<?= $item->id ?>">
					<td class="flag"><i class="material-icons"><?= ($item->is_active ? 'done' : '') ?></i>
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
					<td class="runs"><?= $item->runs ?>
					<td class="ran">
						<?php if (!$item->ran): ?>
							<?= $t('never') ?>
						<?php else: ?>
							<?= $this->date->format($item->ran, 'date') ?>
						<?php endif ?>
					<td class="description"><?= $item->description ?>
					<td><?= $this->price->format($item->totalAmount(), 'net') ?>
					<td class="date modified">
						<time datetime="<?= $this->date->format($item->modified, 'w3c') ?>">
							<?= $this->date->format($item->modified, 'date') ?>
						</time>
					<td class="actions">
						<?= $this->html->link($t('delete'), ['id' => $item->id, 'action' => 'delete', 'library' => 'billing_time'], ['class' => 'button']) ?>

						<?= $this->html->link($item->is_active ? $t('deactivate') : $t('activate'), [
							'id' => $item->id, 'action' => $item->is_active ? 'deactivate' : 'activate'
						], ['class' => 'button']) ?>

						<?= $this->html->link($t('open'), ['id' => $item->id, 'action' => 'edit', 'library' => 'billing_time'], ['class' => 'button']) ?>
				<?php endforeach ?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="none-available"><?= $t('No items available, yet.') ?></div>
	<?php endif ?>

	<?=$this->view()->render(['element' => 'paging'], compact('paginator'), ['library' => 'base_core']) ?>
</article>