<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Executor;

/**
 * Will be thrown by RetryTaskHandler to indicate that the current run was failed and should not be retried.
 */
class FailedException extends \Exception
{
    public function __construct(\Exception $previous)
    {
        parent::__construct($previous->getMessage(), $previous->getCode(), $previous);
    }
}
