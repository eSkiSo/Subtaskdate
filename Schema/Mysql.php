<?php

namespace Kanboard\Plugin\TaskBoardDate\Schema;

use PDO;

const VERSION = 1;

function version_1(PDO $pdo)
{
    $pdo->exec("ALTER TABLE `tasks` ADD COLUMN `date_board` INT DEFAULT '0'");
}
