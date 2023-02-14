<?php

namespace Kanboard\Plugin\Subtaskdate\Model;

use DateTime;
use Kanboard\Model\TimezoneModel;
use Kanboard\Model\TaskFinderModel;
use Kanboard\Core\Base;

/**
 * Subtask Calendar Model
 *
 * @package  Kanboard\Plugin\Subtaskdate
 * @author   Craig Crosby
 */
class SubtaskCalendarModel extends Base
{
    /**
     * SQL table name
     *
     * @var string
     */
    public const TABLE = 'subtasks';
    /**
     * Get query to fetch all users
     *
     * @access public
     * @param  integer $group_id
     * @return \PicoDb\Table
     */
    public function getUserCalendarEvents($user_id, $start, $end)
    {
        $tasks = $this->db->table(self::TABLE)
           ->eq('user_id', $user_id)
           ->gte('due_date', strtotime($start))
           ->lte('due_date', strtotime($end))
           ->neq('status', 2)
           ->columns('task_id', 'title', 'due_date')
           ->findAll();

        $events = array();

        foreach ($tasks as $task) {
            $fulltask = $this->taskFinderModel->getById($task['task_id']);

            $startDate = new DateTime();
            $startDate->setTimestamp($task['due_date']);

            $allDay = $startDate == $startDate && $startDate->format('Hi') == '0000';
            $format = $allDay ? 'Y-m-d' : 'Y-m-d\TH:i:s';

            $events[] = array(
                'timezoneParam' => $this->timezoneModel->getCurrentTimezone(),
                'id' => $task['task_id'],
                'title' => t('#%d', $task['task_id']).' '.$task['title'],
                'backgroundColor' => $this->colorModel->getBackgroundColor('grey'),
                'borderColor' => $this->colorModel->getBorderColor('red'),
                'textColor' => 'black',
                'url' => $this->helper->url->to('TaskViewController', 'show', array('task_id' => $task['task_id'], 'project_id' => $fulltask['project_id'])),
                'start' => $startDate->format($format),
                'end' => $startDate->format($format),
                'editable' => $allDay,
                'allday' => $allDay,
            );
        }

        return $events;
    }

    public function getProjectCalendarEvents($project_id, $start, $end)
    {
        $alltasks = $this->taskFinderModel->getAllIds($project_id);

        $tasks = array();

        foreach ($alltasks as $lonetask) {
            $foundtasks = $this->db->table(self::TABLE)
               ->eq('task_id', $lonetask)
               ->gte('due_date', strtotime($start))
               ->lte('due_date', strtotime($end))
               ->neq('status', 2)
               ->columns('task_id', 'title', 'due_date')
               ->findAll();

            $tasks = array_merge($tasks, $foundtasks);
        }

        $events = array();

        foreach ($tasks as $task) {
            $fulltask = $this->taskFinderModel->getById($task['task_id']);

            $startDate = new DateTime();
            $startDate->setTimestamp($task['due_date']);

            $allDay = $startDate == $startDate && $startDate->format('Hi') == '0000';
            $format = $allDay ? 'Y-m-d' : 'Y-m-d\TH:i:s';

            $events[] = array(
                'timezoneParam' => $this->timezoneModel->getCurrentTimezone(),
                'id' => $task['task_id'],
                'title' => t('#%d', $task['task_id']).' '.$task['title'],
                'backgroundColor' => $this->colorModel->getBackgroundColor('grey'),
                'borderColor' => $this->colorModel->getBorderColor('red'),
                'textColor' => 'black',
                'url' => $this->helper->url->to('TaskViewController', 'show', array('task_id' => $task['task_id'], 'project_id' => $fulltask['project_id'])),
                'start' => $startDate->format($format),
                'end' => $startDate->format($format),
                'editable' => $allDay,
                'allday' => $allDay,
            );
        }

        return $events;
    }
}
