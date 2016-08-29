<?php

namespace Task\Schedule;

interface ScheduleRepository
{
    public function set(Schedule $schedule);

    public function get();
}
