<?php

namespace Task;

use Cron\CronExpression;

/**
 * TODO introduce interface.
 */
class TaskInterval
{
    public static function once()
    {
        return;
    }

    public static function hourly()
    {
        return CronExpression::factory('@hourly');
    }

    public static function daily()
    {
        return CronExpression::factory('@daily');
    }

    public static function weekly()
    {
        return CronExpression::factory('@weekly');
    }

    public static function monthly()
    {
        return CronExpression::factory('@monthly');
    }

    public static function yearly()
    {
        return CronExpression::factory('@yearly');
    }

    /**
     * @var CronExpression
     */
    private $interval;

    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @param string $cronExpression
     * @param \DateTime $start
     * @param \DateTime $end
     */
    public function __construct($cronExpression, \DateTime $start, \DateTime $end)
    {
        $this->interval = CronExpression::factory($cronExpression);
        $this->start = $start;
        $this->end = $end;
    }

    public function isRecurring()
    {
        return $this->interval !== null;
    }

    public function getCronExpression()
    {
        return $this->interval->getExpression();
    }

    public function getStart()
    {
        return $this->start;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function getNextRunDate()
    {
        if (!$this->interval) {
            return;
        }

        return $this->interval->getNextRunDate();
    }

    public function getMinute()
    {
        if (!$this->interval) {
            return;
        }

        return $this->interval->getExpression(CronExpression::MINUTE);
    }

    public function getHour()
    {
        if (!$this->interval) {
            return;
        }

        return $this->interval->getExpression(CronExpression::HOUR);
    }

    public function getDay()
    {
        if (!$this->interval) {
            return;
        }

        return $this->interval->getExpression(CronExpression::DAY);
    }

    public function getWeekDay()
    {
        if (!$this->interval) {
            return;
        }

        return $this->interval->getExpression(CronExpression::WEEKDAY);
    }

    public function getMonth()
    {
        if (!$this->interval) {
            return;
        }

        return $this->interval->getExpression(CronExpression::MONTH);
    }

    public function getYear()
    {
        if (!$this->interval) {
            return;
        }

        return $this->interval->getExpression(CronExpression::YEAR);
    }
}
