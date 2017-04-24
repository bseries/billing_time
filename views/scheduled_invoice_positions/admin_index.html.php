<?php

use lithium\g11n\Message;

$t = function($message, array $options = []) {
	return Message::translate($message, $options + ['scope' => 'billing_time', 'default' => $message]);
};

$this->set([
	'page' => [
		'type' => 'multiple',
		'object' => $t('scheduled invoice positions')
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
		<?= $this->html->link($t('schedule position'), ['action' => 'add'], ['class' => 'button add']) ?>
	</div>

	<?php if ($data->count()): ?>
		<table>
			<thead>
				<tr>
					<td data-sort="is-active" class="flag table-sort"><?= $t('Active?') ?>
					<td data-sort="user.number" class="user table-sort"><?= $t('User') ?>
					<td data-sort="run-on" class="table-sort"><?= $t('Run on') ?>
					<td data-sort="description" class="table-sort"><?= $t('Description') ?>
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
				<tr data-id="<?= $item->id ?>">
					<td class="flag"><i class="material-icons"><?= ($item->is_active ? 'done' : '') ?></i>
					<td class="user">
						<?= $this->user->link($item->user()) ?>
					<td class="run-on">
						<?php if ($item->run_on == date('Y-m-d')): ?>
							<?= $t('immediately') ?>
						<?php else: ?>
							<?= $this->date->format($item->run_on, 'date') ?>
						<?php endif ?>
					<td class="description"><?= $item->description ?>
					<td><?= $this->price->format($item->totalAmount(), 'net') ?>
					<td class="date modified">
						<time datetime="<?= $this->date->format($item->modified, 'w3c') ?>">
							<?= $this->date->format($item->modified, 'date') ?>
						</time>
					<td class="actions">
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

	<?=$this->_render('element', 'paging', compact('paginator'), ['library' => 'base_core']) ?>
</article>