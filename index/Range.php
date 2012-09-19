<?php

namespace malkusch\index;

/**
 * Search range
 *
 * @author   Markus Malkusch <markus@malkusch.de>
 * @link     https://github.com/malkusch/php-index
 */
class Range
{
    
    /**
     * @var String
     */
    private $_min = "";
    
    /**
     * @var String
     */
    private $_max = "";
    
    /**
     * @var bool
     */
    private $_inclusive = true;
    
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
     * Returns true if key is inside this range regardless of the maximum border
     * 
     * @param String $key 
     * 
     * @return bool
     */
    public function isGreaterThanMinOuterBorder($key)
    {
        return $this->_inclusive ? $key >= $this->_min : $key > $this->_min;
    }
    
    /**
     * Returns true if key is inside this range regardless of the minimum border
     * 
     * @param String $key 
     * 
     * @return bool
     */
    public function isLessesThanMaxOuterBorder($key)
    {
        return $this->_inclusive ? $key <= $this->_max : $key < $this->_max;
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