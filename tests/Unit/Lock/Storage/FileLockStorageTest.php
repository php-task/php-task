<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Tests\Unit\Lock\Storage;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use Task\Lock\LockStorageInterface;
use Task\Lock\Storage\FileLockStorage;

class FileLockStorageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;

    /**
     * @var LockStorageInterface
     */
    private $storage;

    public function setUp()
    {
        $this->root = vfsStream::setup('tmp');

        $this->storage = new FileLockStorage($this->root->url());
    }

    public function testSave()
    {
        $this->assertTrue($this->storage->save('test', 300));

        $this->assertTrue($this->root->hasChild('test.lock'));
        $this->assertLessThanOrEqual(time() + 300, $this->root->getChild('test.lock')->getContent());
    }

    public function testSaveClassKey()
    {
        $this->assertTrue($this->storage->save('AppBundle\\TestClass', 300));

        $this->assertTrue($this->root->hasChild('AppBundle_TestClass.lock'));
        $this->assertLessThanOrEqual(time() + 300, $this->root->getChild('AppBundle_TestClass.lock')->getContent());
    }

    public function testDelete()
    {
        $child = new vfsStreamFile('test.lock');
        $child->setContent((string) (time() + 300));
        $this->root->addChild($child);

        $this->assertTrue($this->storage->delete('test'));

        $this->assertFalse($this->root->hasChild('test.lock'));
    }

    public function testDeleteClassKey()
    {
        $child = new vfsStreamFile('AppBundle_TestClass.lock');
        $child->setContent((string) (time() + 300));
        $this->root->addChild($child);

        $this->assertTrue($this->storage->delete('AppBundle\\TestClass'));

        $this->assertFalse($this->root->hasChild('AppBundle_TestClass.lock'));
    }

    public function testDeleteNotExists()
    {
        $child = new vfsStreamFile('test2.lock');
        $child->setContent((string) (time() + 300));
        $this->root->addChild($child);

        // no exception should be thrown and other lock should not be deleted
        $this->assertTrue($this->storage->delete('test'));

        $this->assertTrue($this->root->hasChild('test2.lock'));
    }

    public function testExists()
    {
        $child = new vfsStreamFile('test.lock');
        $child->setContent((string) (time() + 300));
        $this->root->addChild($child);

        $this->assertTrue($this->storage->exists('test'));
    }

    public function testExistsClassKey()
    {
        $child = new vfsStreamFile('AppBundle_TestClass.lock');
        $child->setContent((string) (time() + 300));
        $this->root->addChild($child);

        $this->assertTrue($this->storage->exists('AppBundle\\TestClass'));
    }

    public function testExistsNotExists()
    {
        $this->assertFalse($this->storage->exists('test'));
    }

    public function testExistsEndOfLive()
    {
        $child = new vfsStreamFile('test.lock');
        $child->setContent((string) (time() - 10));
        $this->root->addChild($child);

        $this->assertFalse($this->storage->exists('test'));
    }
}
