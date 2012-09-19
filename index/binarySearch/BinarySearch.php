<?php

namespace malkusch\index;

/**
 * Binary search
 *
 * This class searches in a sorted index.
 *
 * @author   Markus Malkusch <markus@malkusch.de>
 * @link     https://github.com/malkusch/php-index
 */
class BinarySearch
{
    
    const DIRECTION_FORWARD  = 1;
    const DIRECTION_BACKWARD = -1;

    private
    /**
     * @var Index
     */
    $_index,
    /**
     * @var ByteRange
     */
    $_range;

    /**
     * Sets the index
     *
     * @param Index $index Index
     */
    public function __construct(Index $index)
    {
        $this->_index = $index;
        $this->_range = new ByteRange(0, $index->getFile()->getFileSize());
    }
    
    /**
     * Searches for a key or some neighbour
     *
     * If it doesn't find the key. A neighbour will be returned. The
     * neighbour mustn't be the closest neighbour. It's just a good hint
     * where the key should be expected.
     * 
     * Returns null if no key could be found at all.
     * 
     * @param string $key Key
     *
     * @return Result
     * @throws IndexException_IO
     */
    public function search($key)
    {
        // split the range
        $splitOffset = $this->_getSplitOffset();
        
        // search right side
        $keys = $this->_index->getKeyReader()->readKeys($splitOffset, self::DIRECTION_FORWARD);
        $foundKey = $this->_findKey($key, $keys);
        // found
        if (! is_null($foundKey)) {
            return $foundKey;
            
        }
        // check if search should terminate
        if ($this->_isKeyRange($key, $keys)) {
            return \reset($keys);
            
        }
        
        // If found keys are smaller continue in the right side
        if (! empty($keys) && \end($keys)->getKey() < $key) {
            $newOffset = $splitOffset + $this->_index->getKeyReader()->getReadLength();
            // Stop if beyond index
            if ($newOffset >= $this->_index->getFile()->getFileSize()) {
                return \end($keys);
                
            }
            $newLength = $this->_range->getLength()
                       - ($newOffset - $this->_range->getOffset());
            $this->_range->setOffset($newOffset);
            $this->_range->setLength($newLength);
            return $this->search($key);
            
        }
        
        // Look at the key, which lies in both sides
        $centerKeyOffset = empty($keys)
            ? $this->_range->getNextByteOffset()
            : \reset($keys)->getOffset();
        $keys = $this->_index->getKeyReader()->readKeys($centerKeyOffset, self::DIRECTION_BACKWARD);
        $foundKey = $this->_findKey($key, $keys);
        // found
        if (! is_null($foundKey)) {
            return $foundKey;
            
        }
        // terminate if no more keys in the index
        if (empty($keys)) {
            return null;
            
        }
        // check if search should terminate
        if ($this->_isKeyRange($key, $keys)) {
            return \reset($keys);
            
        }
        
        // Finally continue searching in the left side
        $newLength = \reset($keys)->getOffset() - $this->_range->getOffset() - 1;
        if ($newLength >= $this->_range->getLength()) {
            return \reset($keys);
            
        }
        $this->_range->setLength($newLength);
        return $this->search($key);
    }
    
    /**
     * Returns true if the key is expected to be in the key list
     * 
     * If the key list is a subset of the index, and the key sould not be in 
     * this list, the key is nowhere else in the index.
     *
     * @param type $key
     * @param array $keys
     * 
     * @return bool
     */
    private function _isKeyRange($key, Array $keys)
    {
        if (empty($keys)) {
            return false;
            
        }
        return \reset($keys)->getKey() <= $key
            && \end($keys)->getKey() >= $key;
    }
    
    /**
     * @param String $key
     * @param array $foundKeys
     * @return Result
     */
    private function _findKey($key, array $foundKeys)
    {
        foreach ($foundKeys as $foundKey) {
            if ($foundKey->getKey() == $key) {
                return $foundKey;
                
            }
        }
        return null;
    }
    
    /**
     * Returns the offset for the split
     * 
     * @return int 
     */
    private function _getSplitOffset()
    {
        $blocks = (int) $this->_range->getLength() / $this->_index->getKeyReader()->getReadLength();
        $centerBlock = (int) $blocks / 2;
        return $this->_range->getOffset() + $centerBlock * $this->_index->getKeyReader()->getReadLength();
    }

}