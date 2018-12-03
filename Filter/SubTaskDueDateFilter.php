<?php

namespace Kanboard\Plugin\Subtaskdate\Filter;

use Kanboard\Core\Filter\FilterInterface;
use Kanboard\Filter\BaseDateFilter;
use Kanboard\Model\SubtaskModel;
use Kanboard\Model\TaskModel;
use PicoDb\Table;
use PicoDb\Database;

/**
 * Filter subtasks by due date
 *
 * @package filter
 * @author  Craig Crosby
 */
class SubTaskDueDateFilter extends BaseDateFilter implements FilterInterface
{
const TABLE = 'subtasks';
    /**
     * Database object
     *
     * @access private
     * @var Database
     */
    private $db;
    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return array('subtask_due');
    }
    /**
     * Set database object
     *
     * @access public
     * @param  Database $db
     * @return $this
     */
    public function setDatabase(Database $db)
    {
        $this->db = $db;
        return $this;
    }

    /**
     * Apply filter
     *
     * @access public
     * @return FilterInterface
     */
    public function apply()
    {        
        if ($this->value == "none") {
            $duedate = $this->db
            ->table(self::TABLE)
            ->eq('due_date', 0)
            ->findAllByColumn('task_id');
        } else {
            
            $method = $this->parseOperator();
            $timestamp = $this->dateParser->getTimestampFromIsoFormat($this->value);
            
            if ($method !== '') {
                $duedate = $this->db
                ->table(self::TABLE)
                ->neq('due_date', 0)
                ->notNull('due_date')
                ->$method('due_date', $this->getTimestampFromOperator($method, $timestamp))
                ->findAllByColumn('task_id');
            } else {
                $duedate = $this->db
                ->table(self::TABLE)
                ->neq('due_date', 0)
                ->notNull('due_date')
                ->gte('due_date', $timestamp)
                ->lte('due_date', $timestamp + 86399)
                ->findAllByColumn('task_id');
            }
        }
        if (isset($duedate) && !empty($duedate)) { return $this->query->in(TaskModel::TABLE.'.id', $duedate); } else { return $this->query->in(TaskModel::TABLE.'.id', [0]); }
    }
}
