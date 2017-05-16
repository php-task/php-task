<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Lock\Exception;

/**
 * Will be thrown when the lock was not already acquired.
 */
class LockNotAcquiredException extends LockConflictException
{
}
