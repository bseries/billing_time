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
					<td class="flag"><?= $t('Run?') ?>
					<td data-sort="is-active" class="flag table-sort"><?= $t('Active?') ?>
					<td data-sort="user.number" class="user table-sort"><?= $t('User') ?>
					<td data-sort="runs" class="number table-sort"><?= $t('Runs') ?>
					<td data-sort="ran" class="table-sort"><?= $t('Last run') ?>
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
				<tr data-id="<?= $item->id ?>">
					<td class="flag"><i class="material-icons"><?= ($item->mustPlace() ? 'warning' : '') ?></i>
					<td class="flag"><i class="material-icons"><?= ($item->is_active ? 'done' : '') ?></i>
					<td class="user">
						<?= $this->user->link($item->user()) ?>
					<td class="runs number"><?= $item->runs ?>
					<td class="ran date">
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

						<?php if ($item->mustPlace()): ?>
							<?= $this->html->link($t('place'), ['id' => $item->id, 'action' => 'place', 'library' => 'billing_time'], ['class' => 'button']) ?>
						<?php endif ?>

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

	<div class="bottom-help">
		<?= $t("Recurring invoice positions can be placed as pending positions, from which in turn invoices may be generated.") ?>
	</div>

	<?=$this->_render('element', 'paging', compact('paginator'), ['library' => 'base_core']) ?>
</article>