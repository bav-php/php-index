<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class BinarySearch
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
 * @category  XML
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
namespace de\malkusch\index;

/**
 * Binary search
 *
 * This class searches in a sorted index.
 *
 * @category XML
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  GPL 3
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
class BinarySearch
{

    const
    /**
     * Sector size
     */
    SECTOR_SIZE = 512;

    private
    /**
     * @var bool
     */
    $_isLastData = false,
    /**
     * @var int
     */
    $_readSectorCount = 1,
    /**
     * @var Parser
     */
    $_indexParser,
    /**
     * @var resource
     */
    $_filePointer,
    /**
     * @var Range
     */
    $_range;

    /**
     * Sets the index
     *
     * @param Index $index Index
     */
    public function __construct(Index $index)
    {
        $this->_indexParser  = $index->getParser();
        $this->_range        = new Range(0, \filesize($index->getFile()));
        $this->_filePointer  = $index->getFilePointer();
    }

    /**
     * Returns the offset of a container for the searched key
     *
     * @param string $key Key
     *
     * @return int
     * @throws IndexException_NotFound Key wasn't found
     * @throws IndexException_IO
     */
    public function search($key)
    {
        $readLength = self::SECTOR_SIZE * $this->_readSectorCount;
        if ($readLength >= $this->_range->getLength()) {
            $readOffset = $this->_range->getOffset();

        } else {
            $readOffset = $this->_range->getMiddle();

        }

        // Read everything at the end
        if ($this->_isLastData) {
            $readOffset = $this->_range->getOffset();
            $readLength = $this->_range->getLength();

        }

        \fseek($this->_filePointer, $readOffset);
        $data = \fread($this->_filePointer, $readLength);

        // No data was read
        if ($data === FALSE) {
            if (\feof($this->_filePointer)) {
                throw new IndexException_NotFound("Did not find '$key'");

            } else {
                throw new IndexException_IO("Could not read file");

            }

        }

        // Parse the read data
        $foundKeys = $this->_indexParser->parseKeys($data);

        // No keys were found
        if (empty($foundKeys)) {
            // No more data to read
            if (\strlen($data) < $readLength) {
                throw new IndexException_NotFound("Did not find '$key'");

            // Too little sectors were read
            } else {
                $this->_readSectorCount = $this->_readSectorCount * 2;
                return $this->search($key);

            }
        }

        // Found
        foreach ($foundKeys as $foundKey) {
            if ($foundKey->getKey() == $key) {
                return $readOffset + $foundKey->getOffset();

            }

        }

        // Stop searching
        if ($this->_range->getLength() <= $readLength) {
            throw new IndexException_NotFound("Did not find '$key'");

        }

        // smallest found key is bigger than search key; search left
        if ($foundKeys[0]->getKey() > $key) {
            $this->_range->splitLeft();
            // Set the last read flag
            if ($this->_range->getLength() <= $readLength) {
                $this->_isLastData = true;

            }
            // Add a full read, because of chunked readings
            $this->_range->addLength($readLength);
            return $this->search($key);

        }

        // biggest found key is smaller than search key; search right
        if ($foundKeys[\count($foundKeys) - 1]->getKey() < $key) {
            $this->_range->splitRight();
            // Set the last read flag
            if ($this->_range->getLength() <= $readLength) {
                $this->_isLastData = true;

            }
            // Add a full read, because of chunked readings
            $this->_range->decreaseOffset($readLength);
            return $this->search($key);

        }

        // Not found
        throw new IndexException_NotFound("Did not find '$key'");
    }

}