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
 * Interface for task builder factory.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface TaskBuilderFactoryInterface
{
    /**
     * Returns task-builder.
     *
     * @param SchedulerInterface $scheduler
     * @param string $taskName
     * @param string $workload
     *
     * @return TaskBuilderInterface
     */
    public function create(SchedulerInterface $scheduler, $taskName, $workload);
}
