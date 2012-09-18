<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class Range
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
 * Search range
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
     * @var String
     */
    $_min = "",
    /**
     * @var String
     */
    $_max = "",
    /**
     * @var bool
     */
    $_inclusive = true;
    
    /**
     * Sets the borders of the range
     *
     * @param int $min
     * @param int $max 
     */
    public function __construct($min, $max)
    {
        if ($min > $max) {
            $this->_min = $max;
            $this->_max = $min;
            
        } else {
            $this->_min = $min;
            $this->_max = $max;
            
        }
    }
    
    /**
     * Returns TRUE if key is inside this range regardless of the maximum border
     * 
     * @param String $key 
     * 
     * @return bool
     */
    public function isGreaterThanMinOuterBorder($key)
    {
        return $this->_inclusive
             ? $key >= $this->_min
             : $key > $this->_min;
    }
    
    /**
     * Returns TRUE if key is inside this range regardless of the minimum border
     * 
     * @param String $key 
     * 
     * @return bool
     */
    public function isLessesThanMaxOuterBorder($key)
    {
        return $this->_inclusive
             ? $key <= $this->_max
             : $key < $this->_max;
    }
    
    /**
     * Sets if the range is including or excluding the borders
     *
     * @param bool $inclusive 
     */
    public function setInclusive($inclusive)
    {
        $this->_inclusive = $inclusive;
    }
    
    /**
     * Returns if the range is including or excluding the borders
     *
     * @return bool
     */
    public function isInclusive()
    {
        return $this->_inclusive;
    }
    
    /**
     * Returns the greater border
     *
     * @return String
     */
    public function getMax()
    {
        return $this->_max;
    }
    
    /**
     * Returns the lesser border
     *
     * @return String
     */
    public function getMin()
    {
        return $this->_min;
    }
    
}