<?php

/*
 * This file is part of php-task library.
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
 */
interface TaskHandlerInterface
{
    /**
     * Handles given workload and returns result.
     *
     * @param mixed|mixed[] $workload
     *
     * @return mixed|mixed[]|void
     */
    public function handle($workload);
}

