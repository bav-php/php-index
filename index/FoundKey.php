<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class FoundKey
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
namespace de\malkusch\index;

/**
 * Found key in the index
 *
 * The FoundKey objects has the found key and its offset in the searched data
 * chunk.
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  GPL 3
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
class FoundKey
{

    private
    /**
     * @var int
     */
    $_offset = 0,
    /**
     * @var string
     */
    $_key;

    /**
     * Sets the offset and the found key
     *
     * @param int    $offset Offset in the data chunk
     * @param string $key    Found key
     */
    public function __construct($offset, $key)
    {
        $this->_offset = $offset;
        $this->_key    = $key;
    }

    /**
     * Returns the offset of the found key
     *
     * This offset is valid for the searched data chunk.
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->_offset;
    }

    /**
     * Returns the found key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->_key;
    }

}