<?php

namespace Task\Schedule;

interface ScheduleRepositoryInterface
{
    public function set(Schedule $schedule);
    public function get();
}
