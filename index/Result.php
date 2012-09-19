<?php

namespace malkusch\index;

/**
 * Result
 *
 * A search returns a Result object with the data, key and the offset
 * in the index file.
 *
 * @author   Markus Malkusch <markus@malkusch.de>
 * @link     https://github.com/malkusch/php-index
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