<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class BinarySearch
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
 * Binary search
 *
 * This class searches in a sorted index.
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
class BinarySearch
{
    
    const DIRECTION_FORWARD  = 1;
    const DIRECTION_BACKWARD = -1;

    private
    /**
     * @var int 
     */
    $_offset = 0,
    /**
     * @var int
     */
    $_readBlockCount = 1,
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
     * Returns the last read offset
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->_offset;
    }

    /**
     * Returns the offset of a container for the searched key
     *
     * Returns NULL if the key wasn't found.
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
        $keys = $this->_findFirstKeys($splitOffset, self::DIRECTION_FORWARD);
        $foundKey = $this->_findKey($key, $keys);
        // found
        if (! is_null($foundKey)) {
            return $foundKey;
            
        }
        // check if search should terminate
        if ($this->_isKeyRange($key, $keys)) {
            return NULL;
            
        }
        
        // If found keys are smaller continue in the right side
        if (! empty($keys) && \end($keys)->getKey() < $key) {
            $newOffset = $splitOffset + $this->_getReadLength();
            // Stop if beyond index
            if ($newOffset >= $this->_index->getFile()->getFileSize()) {
                return NULL;
                
            }
            $newLength = $this->_range->getLength()
                       - ($newOffset - $this->_range->getOffset());
            $this->_range->setOffset($newOffset);
            $this->_range->setLength($newLength);
            return $this->search($key);
            
        }
        
        // Look at the key, which lies in both sides
        $centerKeyOffset
            = empty($keys)
            ? $this->_range->getNextByteOffset()
            : \reset($keys)->getOffset();
        $keys
            = $this->_findFirstKeys(
                $centerKeyOffset,
                self::DIRECTION_BACKWARD
            );
        $foundKey = $this->_findKey($key, $keys);
        // found
        if (! is_null($foundKey)) {
            return $foundKey;
            
        }
        // terminate if no more keys in the index
        if (empty($keys)) {
            return NULL;
            
        }
        // check if search should terminate
        if ($this->_isKeyRange($key, $keys)) {
            return NULL;
            
        }
        
        // Finally continue searching in the left side
        $newLength = \reset($keys)->getOffset() - $this->_range->getOffset() - 1;
        if ($newLength >= $this->_range->getLength()) {
            return NULL;
            
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
     * Returns the first key
     *
     * @param int $offset
     * @param int $direction
     * @return array
     * @throws IndexException_IO 
     */
    private function _findFirstKeys($offset, $direction)
    {
        if ($direction == self::DIRECTION_FORWARD) {
            $this->_offset = $offset;
            
        } elseif ($direction == self::DIRECTION_BACKWARD) {
            $this->_offset = $offset - $this->_getReadLength();
            if ($this->_offset < 0 ) {
                $this->_offset = 0;
                
            }
        }
        
        // Read data
        \fseek($this->_index->getFile()->getFilePointer(), $this->_offset);
        $data = \fread(
            $this->_index->getFile()->getFilePointer(),
            $this->_getReadLength()
        );
        if ($data === FALSE) {
            if (\feof($this->_index->getFile()->getFilePointer())) {
                return array();

            } else {
                throw new IndexException_IO("Could not read file");

            }

        }
        
        // Parse the read data
        $keys = $this->_index->getParser()->parseKeys($data, $this->_offset);
        
        // Read more data
        if (empty($keys)) {
            // Only increase if there exists more data
            if ($direction == self::DIRECTION_BACKWARD && $this->_offset == 0) {
                return array();
                
            } elseif (
                $direction == self::DIRECTION_FORWARD
                && $this->_offset + $this->_getReadLength()
                   >= $this->_index->getFile()->getFileSize()
            ) {
                return array();
                
            }
            
            $this->_increaseReadLength();
            return $this->_findFirstKeys($offset, $direction);
            
        }
        
        return $keys;
    }
    
    /**
     * Returns the offset for the split
     * 
     * @return int 
     */
    private function _getSplitOffset()
    {
        $blocks = (int) $this->_range->getLength() / $this->_getReadLength();
        $centerBlock = (int) $blocks / 2;
        return $this->_range->getOffset() + $centerBlock * $this->_getReadLength();
    }
    
    /**
     * Returns the length of one read operation 
     * 
     * @return int
     */
    private function _getReadLength()
    {
        return
            $this->_index->getFile()->getBlockSize() * $this->_readBlockCount;
    }
    
    /**
     * Increases the read size
     */
    private function _increaseReadLength()
    {
        $this->_readBlockCount = $this->_readBlockCount * 2;
    }

}