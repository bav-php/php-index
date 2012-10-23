<?php

namespace malkusch\index;

/**
 * Range iterator
 *
 * @author   Markus Malkusch <markus@malkusch.de>
 * @link     https://github.com/malkusch/php-index
 */
class RangeIterator implements \Iterator
{
    
    /**
     * @var IndexIterator
     */
    private $iterator;
    
    /**
     * @var Range
     */
    private $range;
    
    public function __construct(IndexIterator $iterator, Range $range)
    {
        $this->iterator = $iterator;
        $this->range    = $range;
    }
    
    /**
     * @return Result
     */
    public function current()
    {
        return $this->iterator->current();
    }

    public function key()
    {
        return $this->iterator->key();
    }

    public function next()
    {
        $this->iterator->next();
    }

    public function rewind()
    {
        $this->iterator->rewind();
    }

    public function valid()
    {
        if (! $this->iterator->valid()) {
            return false;
            
        }
        return $this->range->contains($this->iterator->current()->getKey());
    }
    
}