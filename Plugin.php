<?php

namespace Kanboard\Plugin\Subtaskdate;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Model\TaskModel;
//use Kanboard\Plugin\Subtaskdate\Filter\SubTaskDueDateFilter; //Needs work
use Kanboard\Model\SubtaskModel;
use PicoDb\Table;

class Plugin extends Base
{
    public function initialize()
    {
        
        //Needs work
        //$this->hook->on('formatter:board:query', array($this, 'applyDateFilter'));

        //Model
        $this->hook->on('model:subtask:creation:prepare', array($this, 'beforeSave'));
        $this->hook->on('model:subtask:modification:prepare', array($this, 'beforeSave'));

        //Forms
        $this->template->hook->attach('template:subtask:form:create', 'Subtaskdate:subtask/form');
        $this->template->hook->attach('template:subtask:form:edit', 'Subtaskdate:subtask/form');

        //Task Details
        $this->template->hook->attach('template:subtask:table:header:before-timetracking', 'Subtaskdate:subtask/table_header');
        $this->template->hook->attach('template:subtask:table:rows', 'Subtaskdate:subtask/table_rows');

        //Dashboard
        $this->template->hook->attach('template:dashboard:subtasks:header:before-timetracking', 'Subtaskdate:subtask/table_header');
        $this->template->hook->attach('template:dashboard:subtasks:rows', 'Subtaskdate:subtask/table_rows');

        //Board Tooltip
        $this->template->hook->attach('template:board:tooltip:subtasks:header:before-assignee', 'Subtaskdate:subtask/table_header');
        $this->template->hook->attach('template:board:tooltip:subtasks:rows', 'Subtaskdate:subtask/table_rows');
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
        return 'Subtaskdate';
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
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/eSkiSo/Subtaskdate';
    }
}
