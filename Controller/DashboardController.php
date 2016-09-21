<?php

namespace Kanboard\Plugin\TaskBoardDate\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Plugin\TaskBoardDate\Pagination\FutureTaskPagination;

class DashboardController extends BaseController
{
    public function future()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('TaskBoardDate:dashboard/show', array(
            'title' => t('Future tasks for %s', $this->helper->user->getFullname($user)),
            'paginator' => FutureTaskPagination::getInstance($this->container)->getDashboardPaginator($user['id'], false),
            'user' => $user,
        )));
    }
}
