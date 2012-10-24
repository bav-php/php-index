<?php

namespace malkusch\index\test;
use malkusch\index as index;

require_once __DIR__ . "/classes/autoloader/autoloader.php";
require_once __DIR__ . "/../index/Index.php";

/**
 * Tests searching a range
 *
 * @author   Markus Malkusch <markus@malkusch.de>
 * @link     https://github.com/malkusch/php-index
 */
class TestSearchRange extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @dataProvider provideTestAllValidRanges
     */
    public function testAllValidRanges(IndexGenerator $generator)
    {
        $index = $generator->getIndex();
        for ($length = 0; $length <= $generator->getIndexLength(); $length++) {
            for ($min = $generator->getMinimum(); $min + $length <= $generator->getMaximum(); $min++) {
                $range = new index\Range($min, $min + $length);
                $range->setInclusive(true);
                
                $foundKeys = $index->searchRange($range)->getKeys();
                
                $exptectedKeys = array();
                for ($key = $range->getMin(); $key <= $range->getMax(); $key++) {
                    $exptectedKeys[] = $key;
                    
                }
                
                $this->assertEquals(
                    $exptectedKeys,
                    $foundKeys,
                    "failed range[{$range->getMin()}, {$range->getMax()}] for index[{$generator->getMinimum()}, {$generator->getMaximum()}]"
                );
            }
        }
    }
    
    /**
     * Test cases for testAllValidRanges()
     */
    public function provideTestAllValidRanges()
    {
        $cases = array();
        
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(0);
        $cases[] = array($generator);
        
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(1);
        $cases[] = array($generator);
        
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(10);
        $cases[] = array($generator);
        
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(100);
        $cases[] = array($generator);
        
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(1000);
        $cases[] = array($generator);
        
        return $cases;
    }
    
}