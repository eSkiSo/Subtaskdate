<td>
    <?php if (! empty($subtask['due_date'])): ?>
        <?= $this->dt->date($subtask['due_date']) ?>
    <?php endif ?>
</td>
