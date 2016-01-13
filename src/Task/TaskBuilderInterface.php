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
 * Interface for task builder.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface TaskBuilderInterface
{
    /**
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return TaskBuilder
     */
    public function daily(\DateTime $start = null, \DateTime $end = null);

    /**
     * @param \DateTime $executionDate
     *
     * @return TaskBuilder
     */
    public function setExecutionDate(\DateTime $executionDate);

    /**
     * @param string $key
     *
     * @return TaskBuilder
     */
    public function setKey($key);

    /**
     * Schedules task with given scheduler.
     */
    public function schedule();
}
