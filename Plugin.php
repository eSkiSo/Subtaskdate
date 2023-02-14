<?php

namespace Kanboard\Plugin\Subtaskdate;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Model\TaskModel;
use Kanboard\Plugin\Subtaskdate\Filter\SubTaskDueDateFilter;
use Kanboard\Model\SubtaskModel;
use Kanboard\Plugin\Subtaskdate\Model\SubtaskCalendarModel;
use Kanboard\Plugin\Subtaskdate\Api\Procedure\NewSubtaskProcedure;
use PicoDb\Table;
use PicoDb\Database;
use JsonRPC\Server;

class Plugin extends Base
{
    public function initialize()
    {
        //Filter
        $this->container->extend('taskLexer', function ($taskLexer, $c) {
            $taskLexer->withFilter(SubTaskDueDateFilter::getInstance()->setDatabase($c['db'])
                                                                      ->setDateParser($c['dateParser']));
            return $taskLexer;
        });

        //Model
        $this->hook->on('model:subtask:creation:prepare', array($this, 'beforeSave'));
        $this->hook->on('model:subtask:modification:prepare', array($this, 'beforeSave'));

        //Forms
        $this->template->hook->attach('template:subtask:form:create', 'Subtaskdate:subtask/form');
        $this->template->hook->attach('template:subtask:form:edit', 'Subtaskdate:subtask/form');

        //Task Details
        $this->template->hook->attach('template:subtask:table:header:before-timetracking', 'Subtaskdate:subtask/table_header');
        $this->template->hook->attach('template:subtask:table:rows', 'Subtaskdate:subtask/table_rows');

        //Dashboard - Removed after 1.0.41
        $wasmaster = str_replace('v', '', APP_VERSION);
        $wasmaster = preg_replace('/\s+/', '', $wasmaster);

        if (strpos(APP_VERSION, 'master') !== false || strpos(APP_VERSION, 'main') !== false && file_exists('ChangeLog')) {
            $wasmaster = trim(file_get_contents('ChangeLog', false, null, 8, 6), ' ');
        }

        if (version_compare($wasmaster, '1.0.40') <= 0) {
            $this->template->hook->attach('template:dashboard:subtasks:header:before-timetracking', 'Subtaskdate:subtask/table_header');
            $this->template->hook->attach('template:dashboard:subtasks:rows', 'Subtaskdate:subtask/table_rows');
        }

        //Board Tooltip
        $this->template->hook->attach('template:board:tooltip:subtasks:header:before-assignee', 'Subtaskdate:subtask/table_header');
        $this->template->hook->attach('template:board:tooltip:subtasks:rows', 'Subtaskdate:subtask/table_rows');

        // API
        $this->api->getProcedureHandler()->withClassAndMethod('createSubtaskdd', new NewSubtaskProcedure($this->container), 'createSubtaskdd');
        $this->api->getProcedureHandler()->withClassAndMethod('updateSubtaskdd', new NewSubtaskProcedure($this->container), 'updateSubtaskdd');

        //Events
        $container = $this->container;

        $this->hook->on('controller:calendar:user:events', function ($user_id, $start, $end) use ($container) {
            $model = new SubtaskCalendarModel($container);
            return $model->getUserCalendarEvents($user_id, $start, $end); // Return new events
        });

        $this->hook->on('controller:calendar:project:events', function ($project_id, $start, $end) use ($container) {
            $model = new SubtaskCalendarModel($container);
            return $model->getProjectCalendarEvents($project_id, $start, $end); // Return new events
        });
    }
    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function beforeSave(array &$values)
    {
        $values = $this->dateParser->convert($values, array('due_date'));
        $this->helper->model->resetFields($values, array('due_date'));
    }

     public function applyDateFilter(Table $query)
     {
         $query->lte(SubtaskModel::TABLE.'.due_date', time());
     }

    public function getPluginName()
    {
        return 'SubtaskDueDate';
    }

    public function getPluginDescription()
    {
        return t('Add a new due date field to subtasks');
    }

    public function getPluginAuthor()
    {
        return 'Manuel Raposo';
    }

    public function getPluginVersion()
    {
        return '1.1.3';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/eSkiSo/Subtaskdate';
    }
}
