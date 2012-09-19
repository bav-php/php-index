<?php

namespace malkusch\index;

/**
 * Range for the binary search
 *
 * @author   Markus Malkusch <markus@malkusch.de>
 * @link     https://github.com/malkusch/php-index
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