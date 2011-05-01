<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class Range
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
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   SVN: $Id$
 * @link      http://php-index.malkusch.de/en/
 */

/**
 * Namespace
 */
namespace de\malkusch\index;

/**
 * Range for the binary search
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
class Range
{

    private
    /**
     * @var int
     */
    $_offset = 0,
    /**
     * @var int
     */
    $_length = 0,
    /**
     * @var int
     */
    $_minOffset = 0,
    /**
     * @var int
     */
    $_maxOffset = 0;

    /**
     * Sets the range
     *
     * $_minOffset and $_maxOffset will be the initial range.
     *
     * @param int $offset Offset
     * @param int $length Length
     */
    public function  __construct($offset, $length)
    {
        $this->_offset = $offset;
        $this->_length = $length;
        $this->_minOffset = $this->_offset;
        $this->_maxOffset = $this->_offset + $length;
    }
    
    /**
     * Sets the offset
     *
     * The offset will never be set below $_minOffset
     *
     * @param int $offset Offset
     *
     * @return void
     */
    public function setOffset($offset)
    {
        if ($offset < $this->_minOffset) {
            $offset = $this->_minOffset;

        }
        $this->_offset = $offset;
    }

    /**
     * Decreases the offset
     *
     * Decreasing the offset does automatically increase the length for the
     * same amount. This asserts that the last byte is still included in the
     * range.
     *
     * @param int $offset Amount for decreasing
     *
     * @return void
     */
    public function decreaseOffset($decreasment)
    {
        $this->setOffset($this->_offset - $decreasment);
        $this->addLength($decreasment);
    }

    /**
     * Sets a new length
     *
     * The last byte of the range will never be larger than the initial last
     * byte.
     *
     * @param int $length Length
     *
     * @return void
     */
    public function setLength($length)
    {
        if ($this->_offset + $length > $this->_maxOffset) {
            $length = $this->_maxOffset - $this->_offset;

        }
        $this->_length = $length;
    }

    /**
     * Increases the length
     *
     * @param int $increasement Amount for increasing
     *
     * @return void
     */
    public function addLength($increasement)
    {
        $this->setLength($this->_length + $increasement);
    }

    /**
     * Returns the beginning of the range
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->_offset;
    }

    /**
     * Returns the length of the range
     *
     * @return int
     */
    public function getLength()
    {
        return $this->_length;
    }

    /**
     * Returns the middle of this range
     *
     * @return int
     */
    public function getMiddle()
    {
        return $this->_offset + (int) ($this->_length / 2);
    }

    /**
     * The range shrinks to the left half
     *
     * @return void
     */
    public function splitLeft()
    {
        $this->_length = $this->getMiddle() - $this->_offset;
    }

    /**
     * The range shrinks to the right half
     *
     * @return void
     */
    public function splitRight()
    {
        $middle = $this->getMiddle();
        $this->setLength($this->_length - ($middle - $this->_offset));
        $this->_offset = $middle;
    }

}