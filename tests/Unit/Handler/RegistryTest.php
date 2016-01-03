<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Unit\Handler;

use Task\Handler\HandlerInterface;
use Task\Handler\Registry;

/**
 * Test for class Registry.
 *
 * @author Johannes Wachter <@wachterjohannes>
 */
class RegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HandlerInterface
     */
    private static $handler;

    public function testHasFalsy()
    {
        $registry = new Registry();

        $this->assertFalse($registry->has('test'));

        return $registry;
    }

    /**
     * @depends testHasFalsy
     *
     * @param Registry $registry
     *
     * @return Registry
     */
    public function testAdd(Registry $registry)
    {
        self::$handler = $this->prophesize(HandlerInterface::class);

        $registry->add('test', self::$handler->reveal());

        $this->assertTrue($registry->has('test'));

        return $registry;
    }

    /**
     * @depends testAdd
     *
     * @param Registry $registry
     */
    public function testRun(Registry $registry)
    {
        self::$handler->handle('workload')->willReturn('result');

        $result = $registry->run('test', 'workload');

        $this->assertEquals('result', $result);
    }

    /**
     * @expectedException \Task\Handler\HandlerNotExistsException
     */
    public function testHandlerNotExists()
    {
        $registry = new Registry();

        $registry->run('test', 'workload');
    }
}
