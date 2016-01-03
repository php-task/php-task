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
 * Interface for task handler registry.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface RegistryInterface
{
    /**
     * Executes handler with given name and injects given workload.
     *
     * @param string $name name of handler.
     * @param string|\Serializable $workload
     *
     * @return mixed result of task handler.
     */
    public function run($name, $workload);

    /**
     * Returns true if handler exists.
     *
     * @param string $name name of handler.
     *
     * @return bool
     */
    public function has($name);
}
