<?php

namespace Kanboard\Plugin\TaskBoardDate\Filter;

use Kanboard\Core\Filter\FilterInterface;
use Kanboard\Filter\BaseDateFilter;
use Kanboard\Model\TaskModel;
use PicoDb\Table;

/**
 * Filter tasks by board date
 *
 * @package filter
 * @author  Frederic Guillot
 */
class TaskBoardDateFilter extends BaseDateFilter implements FilterInterface
{
    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return array('board_date');
    }

    /**
     * Apply filter
     *
     * @access public
     * @return FilterInterface
     */
    public function apply()
    {
        $this->applyDateFilter(TaskModel::TABLE . '.date_board');
        return $this;
    }
}
