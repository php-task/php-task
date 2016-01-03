<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task;

/**
 * Factory for task builder.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
class TaskBuilderFactory implements TaskBuilderFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(SchedulerInterface $scheduler, $taskName, $workload)
    {
        return TaskBuilder::create($scheduler, $taskName, $workload);
    }
}
