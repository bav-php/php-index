<?php

namespace malkusch\index\test;
use malkusch\index as index;

require_once __DIR__ . "/classes/AbstractTest.php";

/**
 * Tests the class BinarySearch
 *
 * @author   Markus Malkusch <markus@malkusch.de>
 * @link     https://github.com/malkusch/php-index
 * @see      BinarySearch
 */
class TestBinarySearch extends AbstractTest
{

    /**
     * Tests increasing the sector count on large containers
     *
     * @return void
     */
    public function testIncreaseSectorCount()
    {
        $generator = new IndexGenerator_FixedSize();
        $oldReadLength = $generator->getIndex()->getKeyReader()->getReadLength();
        $expectedFactor = 3;
        
        $generator->setIndexLength(100);
        $generator->setMinimumDataSize($oldReadLength * $expectedFactor);
        
        

        $index = $generator->getIndex();
        $binarySearch = new index\BinarySearch($index);
        $this->assertNotEmpty($binarySearch->search(3)->getData());
        
        $this->assertGreaterThanOrEqual(
            $oldReadLength * $expectedFactor,
            $index->getKeyReader()->getReadLength()
        );
    }

}