<th>
	<?php if (isset($paginator)): ?>
	    <?= $paginator->order(t('Due Date'), \Kanboard\Model\SubtaskModel::TABLE.'.due_date') ?>
	<?php else: ?>
		<?= t('Due Date') ?>
	<?php endif ?>
</th>

