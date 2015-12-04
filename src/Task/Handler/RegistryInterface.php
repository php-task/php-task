<?php

namespace Task\Handler;

interface RegistryInterface
{
    public function run($name, $workload);
}
