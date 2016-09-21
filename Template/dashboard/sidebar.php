<li <?= $this->app->checkMenuSelection('DashboardController', 'future', 'TaskBoardDate') ?>>
    <?= $this->url->link(t('My future tasks'), 'DashboardController', 'future', array('plugin' => 'TaskBoardDate', 'user_id' => $user['id'])) ?>
</li>
