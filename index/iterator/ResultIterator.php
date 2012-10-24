<?php

namespace malkusch\index;

/**
 * Iterator for Result Objects
 * 
 * This iterator returns Result objects.
 *
 * @author   Markus Malkusch <markus@malkusch.de>
 * @link     https://github.com/malkusch/php-index
 */
interface ResultIterator extends \Iterator
{
    
    /**
     * @return Result 
     */
    public function current();

}
