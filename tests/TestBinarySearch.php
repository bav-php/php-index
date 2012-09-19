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
        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(100);
        $generator->setMinimumDataSize(
            $generator->getIndex()->getFile()->getBlockSize() * 3
        );

        $index = $generator->getIndex();
        $binarySearch = new index\BinarySearch($index);
        $binarySearch->search(3);

        $reflectedReadBlockCount
            = new \ReflectionProperty($binarySearch, "_readBlockCount");
        $reflectedReadBlockCount->setAccessible(true);
        $readBlockCount = $reflectedReadBlockCount->getValue($binarySearch);

        $this->assertGreaterThanOrEqual(3, $readBlockCount);
    }

}