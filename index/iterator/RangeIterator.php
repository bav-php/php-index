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
    
}