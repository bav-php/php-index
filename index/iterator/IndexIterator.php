<?php

namespace malkusch\index;

/**
 * Index Iterator
 *
 * @author malkusch
 */
class IndexIterator implements \Iterator
{
    
    /**
     * @var int
     */
    private $offset = 0;
    
    /**
     * @var int 
     */
    private $direction = KeyReader::DIRECTION_FORWARD;
    
    /**
     * @var Index 
     */
    private $index;
    
    /**
     * @var ArrayIterator 
     */
    private $iterator;
    
    /**
     * Sets the index
     */
    public function __construct(Index $index)
    {
        $this->index = $index;
        $this->iterator = new \ArrayIterator();
    }

    /**
     * Sets the iteration direction
     *
     * @param int $direction Direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    }
    
    /**
     * Sets a offset
     *
     * @param int $offset Offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
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
        return $this->current()->getKey();
    }

    public function next()
    {
        $this->iterator->next();
    }

    public function rewind()
    {
        $this->iterator = new \ArrayIterator();
    }

    public function valid()
    {
        if (! $this->iterator->valid()) {
            // terminate at the left end
            if ($this->direction == KeyReader::DIRECTION_BACKWARD && $this->offset <= 0) {
                return false;

            }
            
            // iterate in the index
            $results = $this->index->getKeyReader()->readKeys(
                $this->offset,
                $this->direction
            );
            
            if (! empty($results)) {
                // reverse order if iterating backwards
                if ($this->direction == KeyReader::DIRECTION_BACKWARD) {
                    $results = array_reverse($results);
                    
                }
                
                // shift offset
                $end = end($results);
                switch ($this->direction) {
                    
                    case KeyReader::DIRECTION_FORWARD:
                        $this->offset = $end->getOffset() + strlen($end->getData());
                        break;
                        
                    case KeyReader::DIRECTION_BACKWARD:
                        $this->offset = $end->getOffset() - 1;
                        break;
                        
                    default:
                        throw new \LogicException("invalid direction");
                    
                }
                reset($results);
                
            }
            $this->iterator = new \ArrayIterator($results);
            
        }
        return $this->iterator->valid();
    }
    
}