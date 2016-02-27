<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Handler;

/**
 * Task handler registry.
 *
 * Allows to add handler instances to run tasks.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface TaskHandlerInterface
{
    /**
     * Handles given workload and returns result.
     *
     * @param string|\Serializable $workload
     *
     * @return string|\Serializable
     */
    public function handle($workload);
}
