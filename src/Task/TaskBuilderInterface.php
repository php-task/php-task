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
     * @return TaskBuilderInterface
     */
    public function daily(\DateTime $start = null, \DateTime $end = null);

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return TaskBuilderInterface
     */
    public function hourly(\DateTime $start = null, \DateTime $end = null);

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return TaskBuilderInterface
     */
    public function weekly(\DateTime $start = null, \DateTime $end = null);

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return TaskBuilderInterface
     */
    public function monthly(\DateTime $start = null, \DateTime $end = null);

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return TaskBuilderInterface
     */
    public function yearly(\DateTime $start = null, \DateTime $end = null);

    /**
     * @param string $cronExpression
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return TaskBuilderInterface
     */
    public function cron($cronExpression, \DateTime $start = null, \DateTime $end = null);

    /**
     * @param \DateTime $executionDate
     *
     * @return TaskBuilderInterface
     */
    public function setExecutionDate(\DateTime $executionDate);

    /**
     * @param string $key
     *
     * @return TaskBuilderInterface
     */
    public function setKey($key);

    /**
     * Set execution date immediately.
     *
     * @return TaskBuilderInterface
     */
    public function immediately();

    /**
     * Schedules task with given scheduler.
     */
    public function schedule();
}
