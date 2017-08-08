<?php

namespace Task\Runner;

/**
 * TODO add description here
 */
class RetryException extends \Exception
{
    /**
     * @var \Exception
     */
    private $innerException;

    /**
     * @param \Exception $innerException
     */
    public function __construct(\Exception $innerException)
    {
        $this->innerException = $innerException;
    }

    /**
     * Returns innerException.
     *
     * @return \Exception
     */
    public function getInnerException()
    {
        return $this->innerException;
    }
}
