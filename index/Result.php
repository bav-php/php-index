<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class Result
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
 * Result
 *
 * A search returns a Result object with the data, key and the offset
 * in the index file.
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
class Result
{
    
    private
    /**
     * @var String
     */
    $_key = "",
    /**
     * @var String
     */
    $_data = "",
    /**
     * @var int
     */
    $_offset = 0;
    
    /**
     * Sets the key
     *
     * @param String $key 
     * 
     * @return void
     */
    public function setKey($key)
    {
        $this->_key = $key;
    }
    
    /**
     * Returns the key
     *
     * @return String
     */
    public function getKey()
    {
        return $this->_key;
    }
    
    /**
     * Sets the data
     *
     * @param String $data 
     * 
     * @return void
     */
    public function setData($data)
    {
        $this->_data = $data;
    }
    
    /**
     * Returns the data
     *
     * @return String
     */
    public function getData()
    {
        return $this->_data;
    }
    
    /**
     * Sets the offset
     *
     * @param int $offset 
     * 
     * @return void
     */
    public function setOffset($offset)
    {
        $this->_offset = $offset;
    }
    
    /**
     * Returns the offset
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->_offset;
    }
    
}