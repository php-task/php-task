<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Lock\Storage;

use Task\Lock\LockStorageInterface;

/**
 * Save locks in the filesystem.
 */
class FileLockStorage implements LockStorageInterface
{
    /**
     * @var string
     */
    private $lockPath;

    /**
     * @param string $lockPath
     */
    public function __construct($lockPath)
    {
        $this->lockPath = $lockPath;

        if (!is_dir($this->lockPath)) {
            mkdir($this->lockPath, 0777, true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save($key, $ttl)
    {
        $fileName = $this->getFileName($key);
        if (!@file_put_contents($fileName, time() + $ttl)) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        $fileName = $this->getFileName($key);
        if (!file_exists($fileName)) {
            return true;
        }

        if (!@unlink($fileName)) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        $fileName = $this->getFileName($key);
        if (!file_exists($fileName)) {
            return false;
        }

        $content = file_get_contents($fileName);

        return time() <= $content;
    }

    /**
     * {@inheritdoc}
     */
    private function getFileName($key)
    {
        return $this->lockPath . DIRECTORY_SEPARATOR . preg_replace('/[^a-zA-Z0-9]/', '_', $key) . '.lock';
    }
}
