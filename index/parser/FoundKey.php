<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class FoundKey
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
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
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
     * @param int    $offset Offset in the index
     * @param string $key    Found key
     */
    public function __construct($offset, $key)
    {
        $this->_offset = $offset;
        $this->_key    = $key;
    }

    /**
     * Sets the offset of the found key
     *
     * @param int $offset
     */
    public function setOffset($offset)
    {
        return $this->_offset = $offset;
    }

    /**
     * Returns the offset of the found key
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