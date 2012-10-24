<?php

namespace malkusch\index;

/**
 * Range iterator
 *
 * @author   Markus Malkusch <markus@malkusch.de>
 * @link     https://github.com/malkusch/php-index
 */
class RangeIterator extends \IteratorIterator
{
    
    /**
     * @var Range
     */
    private $range;
    
    public function __construct(IndexIterator $iterator, Range $range)
    {
        parent::__construct($iterator);
        $this->range    = $range;
    }
    
    public function valid()
    {
        if (! parent::valid()) {
            return false;
            
        }
        return $this->range->contains($this->current()->getKey());
    }
    
    /**
     * Returns an array with the found keys
     * 
     * @return array 
     */
    public function getKeys()
    {
        $keys = array();
        foreach ($this as $result) {
            $keys[] = $result->getKey();
            
        }
        return $keys;
    }
    
}