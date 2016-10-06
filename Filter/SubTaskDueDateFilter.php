<?php

namespace Kanboard\Plugin\Subtaskdate\Filter;

use Kanboard\Core\Filter\FilterInterface;
use Kanboard\Filter\BaseDateFilter;
use Kanboard\Model\SubtaskModel;
use PicoDb\Table;

/**
 * Filter subtasks by due date
 *
 * @package filter
 * @todo NEEDS work
 * @author  Manuel Raposo
 */
class SubTaskDueDateFilter extends BaseDateFilter implements FilterInterface
{
    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return array('subtask_date');
    }

    /**
     * Apply filter
     *
     * @access public
     * @return FilterInterface
     */
    public function apply()
    {
        $this->applyDateFilter(SubtaskModel::TABLE . '.due_date');
        return $this;
    }
}
