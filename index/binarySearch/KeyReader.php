<?php

namespace malkusch\index;

/**
 * Helper for doing file operations in a file
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link   https://github.com/malkusch/php-index
 */
class KeyReader 
{
    
    const DIRECTION_FORWARD  = 1;
    const DIRECTION_BACKWARD = -1;
    
    /**
     * @var int
     */
    private $_readBlockCount = 1;
    /**
     * @var Index
     */
    private $_index;
    
    /**
     * Sets the index
     */
    public function setIndex(Index $index)
    {
        $this->_index = $index;
    }
    
    /**
     * Returns keys from a offset
     * 
     * The read range will be increased until at least one key will be found
     * or the end of file was reached.
     *
     * @param int $offset
     * @param int $direction
     * @return array
     * @throws IndexException_IO
     */
    public function readKeys($offset, $direction)
    {
        // If reading backwards, shift the offset left
        $shiftedOffset = $direction == self::DIRECTION_BACKWARD
                ? $offset - $this->getReadLength()
                : $offset;
        
        //TODO shift to a blocksize chunk
        
        // Don't shift too far
        if ($shiftedOffset < 0 ) {
            $shiftedOffset = 0;

        }
        
        // Read data
        \fseek($this->_index->getFile()->getFilePointer(), $shiftedOffset);
        $data = \fread(
            $this->_index->getFile()->getFilePointer(),
            $this->getReadLength()
        );
        if ($data === false) {
            if (\feof($this->_index->getFile()->getFilePointer())) {
                return array();

            } else {
                throw new IndexException_IO("Could not read file");

            }
        }
        
        // Parse the read data
        $keys = $this->_index->getParser()->parseKeys($data, $shiftedOffset);
        
        // Read more data if no keys were found
        if (empty($keys)) {
            // Only increase if there exists more data
            if ($direction == self::DIRECTION_BACKWARD && $shiftedOffset == 0) {
                return array();
                
            } elseif (
                $direction == self::DIRECTION_FORWARD
                && $shiftedOffset + $this->getReadLength()
                   >= $this->_index->getFile()->getFileSize()
            ) {
                return array();
                
            }
            
            $this->_increaseReadLength();
            return $this->readKeys($offset, $direction);
            
        }
        
        return $keys;
    }
    
    /**
     * Returns the length of one read operation 
     * 
     * @return int
     */
    public function getReadLength()
    {
        return $this->_index->getFile()->getBlockSize() * $this->_readBlockCount;
    }
    
    /**
     * Increases the read size
     */
    private function _increaseReadLength()
    {
        $this->_readBlockCount = $this->_readBlockCount * 2;
    }
    
}