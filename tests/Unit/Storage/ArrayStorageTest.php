<?php

namespace Unit\Storage;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Task\Storage\ArrayStorage;
use Task\TaskInterface;

class ArrayStorageTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $task = $this->prophesize(TaskInterface::class);
        $taskCollection = $this->prophesize(Collection::class);

        $arrayStorage = new ArrayStorage($taskCollection->reveal());
        $arrayStorage->store($task->reveal());

        $taskCollection->add($task->reveal())->shouldBeCalled();
    }

    public function findScheduledProvider()
    {
        return [
            [[['test-1', new \DateTime('1 minute ago'), false]], ['test-1']],
            [[['test-1', new \DateTime('+1 minute'), false]], []],
            [
                [
                    ['test-1', new \DateTime('+1 minute'), false],
                    ['test-2', new \DateTime('1 minute ago'), false],
                ],
                ['test-2']
            ],
            [
                [
                    ['test-1', new \DateTime('+1 minute'), false],
                    ['test-2', new \DateTime('1 minute ago'), false],
                    ['test-3', new \DateTime(), false],
                ],
                ['test-2', 'test-3']
            ],
            [
                [
                    ['test-1', new \DateTime('+1 minute'), false],
                    ['test-2', new \DateTime('1 minute ago'), true],
                    ['test-3', new \DateTime(), false],
                ],
                ['test-3']
            ],
            [
                [
                    ['test-1', new \DateTime('+1 minute'), false],
                    ['test-2', new \DateTime('1 minute ago'), true],
                    ['test-3', new \DateTime(), true],
                ],
                []
            ],
        ];
    }

    /**
     * @dataProvider findScheduledProvider
     */
    public function testFindScheduled($taskData, $expectedData)
    {
        $tasks = array_map(
            function ($item) {
                $task = $this->prophesize(TaskInterface::class);
                $task->getTaskName()->willReturn($item[0]);
                $task->getExecutionDate()->willReturn($item[1]);
                $task->isCompleted()->willReturn($item[2]);

                return $task->reveal();
            },
            $taskData
        );

        $arrayStorage = new ArrayStorage(new ArrayCollection($tasks));

        $result = $arrayStorage->findScheduled();
        $this->assertCount(count($expectedData), $result);

        $i = -1;
        foreach ($result as $item) {
            $this->assertEquals($expectedData[++$i], $item->getTaskName());
        }
    }

    /**
     * @dataProvider findScheduledProvider
     */
    public function testFindAll($taskData)
    {
        $tasks = array_map(
            function ($item) {
                $task = $this->prophesize(TaskInterface::class);
                $task->getTaskName()->willReturn($item[0]);
                $task->getExecutionDate()->willReturn($item[1]);
                $task->isCompleted()->willReturn($item[2]);

                return $task->reveal();
            },
            $taskData
        );

        $arrayStorage = new ArrayStorage(new ArrayCollection($tasks));

        $result = $arrayStorage->findAll();
        $this->assertCount(count($taskData), $result);

        $i = -1;
        foreach ($result as $item) {
            $this->assertEquals($taskData[++$i][0], $item->getTaskName());
        }
    }
}
