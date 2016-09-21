<?php

namespace Kanboard\Plugin\TaskBoardDate;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Model\TaskModel;
use Kanboard\Plugin\TaskBoardDate\Filter\TaskBoardDateFilter;
use Kanboard\Plugin\TaskBoardDate\Pagination\FutureTaskPagination;
use PicoDb\Table;

class Plugin extends Base
{
    public function initialize()
    {
        $this->hook->on('formatter:board:query', array($this, 'applyDateFilter'));
        $this->hook->on('pagination:dashboard:task:query', array($this, 'applyDateFilter'));
        $this->hook->on('pagination:dashboard:subtask:query', array($this, 'applyDateFilter'));
        $this->hook->on('model:task:creation:prepare', array($this, 'beforeSave'));
        $this->hook->on('model:task:modification:prepare', array($this, 'beforeSave'));

        $this->template->hook->attach('template:task:form:third-column', 'TaskBoardDate:task_creation/form');
        $this->template->hook->attach('template:dashboard:sidebar', 'TaskBoardDate:dashboard/sidebar');
        $this->template->hook->attachCallable('template:dashboard:show', 'TaskBoardDate:dashboard/show', function(array $user) {
            return array(
                'paginator' => FutureTaskPagination::getInstance($this->container)->getDashboardPaginator($user['id'], true)
            );
        });

        $this->container->extend('taskLexer', function($taskLexer, $c) {
            $taskLexer->withFilter(TaskBoardDateFilter::getInstance($c)->setDateParser($c['dateParser']));
            return $taskLexer;
        });
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function beforeSave(array &$values)
    {
        $values = $this->dateParser->convert($values, array('date_board'));
        $this->helper->model->resetFields($values, array('date_board'));
    }

    public function applyDateFilter(Table $query)
    {
        $query->lte(TaskModel::TABLE.'.date_board', time());
    }

    public function getPluginName()
    {
        return 'TaskBoardDate';
    }

    public function getPluginDescription()
    {
        return t('Add a new date field for tasks to define the visibility on the board and dashboard');
    }

    public function getPluginAuthor()
    {
        return 'Frédéric Guillot';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/kanboard/plugin-task-board-date';
    }
}
