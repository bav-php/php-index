<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class ByteRange
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
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
namespace malkusch\index;

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
class ByteRange
{

    private
    /**
     * @var int
     */
    $_offset = 0,
    /**
     * @var int
     */
    $_length = 0;

    /**
     * Sets the range
     *
     * @param int $offset Offset
     * @param int $length Length
     */
    public function  __construct($offset, $length)
    {
        $this->_offset = $offset;
        $this->_length = $length;
    }
    
    /**
     * Sets the offset
     *
     * @param int $offset Offset
     *
     * @return void
     */
    public function setOffset($offset)
    {
        $this->_offset = $offset;
    }

    /**
     * Sets a new length
     *
     * @param int $length Length
     *
     * @return void
     */
    public function setLength($length)
    {
        $this->_length = $length;
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
     * Returns the offset of the last byte + 1 of this range
     * 
     * @return int
     */
    public function getNextByteOffset()
    {
        return $this->_offset + $this->_length;
    }

}