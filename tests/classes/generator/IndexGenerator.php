<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class IndexGenerator
 *
 * PHP version 5
 *
 * LICENSE: This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.
 *
 * @category  Structures
 * @package   index
 * @author    Markus Malkusch <markus@malkusch.de>
 * @copyright 2011 Markus Malkusch
 * @license   GPL 3
 * @version   SVN: $Id$
 * @link      http://php-index.malkusch.de/en/
 */

/**
 * Namespace
 */
namespace de\malkusch\index\test;
use de\malkusch\index as index;

/**
 * Generates an index
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  GPL 3
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
abstract class IndexGenerator
{

    private
    /**
     * @var int
     */
    $_stepSize = 1,
    /**
     * @var int
     */
    $_length = 0;

    /**
     * Creates a new Index file
     *
     * @var string $file Path to the index
     *
     * @return void
     * @throws IndexTestException_CreateFile
     */
    abstract protected function createIndexFile($file);
    /**
     * Creates a new Index
     *
     * @var string $file Path to the index
     *
     * @return Index
     */
    abstract protected function createIndex($file);
    /**
     * Returns the index file name without the directory path
     *
     * @return string
     */
    abstract protected function getIndexFileName();

    /**
     * Returns the path to the test indexes
     *
     * @return string
     */
    static public function getTestIndexPath()
    {
        return __DIR__ . "/../../index/";
    }

    /**
     * Returns the path to the test indexes which are dynamically created
     *
     * @return string
     */
    static private function _getTestIndexVarPath()
    {
        return self::getTestIndexPath() . "/var/";
    }

    /**
     * Sets the size of one step in the sorted index.
     *
     * E.g. step size "1" generates the index 0,1,2,3…,
     * a step size "2" produces the index 0,2,4,6,….
     *
     * @param int $stepSize Step size
     *
     * @return void
     */
    public function setStepSize($stepSize)
    {
        $this->_stepSize = $stepSize;
    }

    /**
     * Gets the size of one step in the sorted index.
     *
     * @return int
     */
    public function getStepSize()
    {
        return $this->_stepSize;
    }

    /**
     * Sets the index length
     *
     * @param int $length Index length
     *
     * @return void
     */
    public function setIndexLength($length)
    {
        $this->_length = $length;
    }

    /**
     * Returns the index length
     *
     * @return int
     */
    public function getIndexLength()
    {
        return $this->_length;
    }

    /**
     * Returns the lowest key
     *
     * @return int
     */
    public function getMinimum()
    {
        return 0;
    }

    /**
     * Returns the highest key
     *
     * @return int
     */
    public function getMaximum()
    {
        return $this->_length * $this->_stepSize - $this->_stepSize;
    }

    /**
     * Returns if the key is in the index
     *
     * @param mixed $key Key
     *
     * @return bool
     */
    public function isKey($key)
    {
        if ($key < $this->getMinimum() || $key > $this->getMaximum()) {
            return false;

        }
        $offset = $this->getMinimum() % $this->_stepSize;
        return $key % $this->_stepSize == $offset;
    }

    /**
     * Gets an Index
     *
     * @return Index
     * @throws IndexTestException_CreateFile
     */
    public function getIndex()
    {
        $file = self::_getTestIndexVarPath() . "/" . $this->getIndexFileName();
        
        // Create only once the index file
        if (! \file_exists($file)) {
            $this->createIndexFile($file);

        }
        
        return $this->createIndex($file);
    }

}