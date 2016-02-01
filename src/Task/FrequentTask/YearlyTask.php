<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\FrequentTask;

use Cron\CronExpression;
use Task\TaskInterface;

/**
 * Run once a year, midnight, Jan. 1.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
class YearlyTask extends CronTask
{
    public function __construct(TaskInterface $task, \DateTime $start, \DateTime $end = null)
    {
        parent::__construct(CronExpression::factory('@yearly'), $task, $start, $end);
    }
}
