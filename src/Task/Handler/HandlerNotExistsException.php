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
 * Thrown when the requested handler not exists.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
class HandlerNotExistsException extends \Exception
{
    /**
     * @var string
     */
    private $name;

    public function __construct($name)
    {
        parent::__construct(sprintf('Handler with name "%s" not exists.', $name));

        $this->name = $name;
    }

    /**
     * Returns name of requested handler.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
