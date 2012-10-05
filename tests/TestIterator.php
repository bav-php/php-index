<?php

namespace malkusch\index\test;
use malkusch\index as index;

require_once __DIR__ . "/classes/autoloader/autoloader.php";
require_once __DIR__ . "/../index/Index.php";

/**
 * Test for IndexIterator
 *
 * @author   Markus Malkusch <markus@malkusch.de>
 * @link     https://github.com/malkusch/php-index
 */
class TestIterator extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Tests that every found key is in the index
     * 
     * @param IndexGenerator $generator 
     * @dataProvider provideTestCases
     */
    public function testIteration(IndexGenerator $generator)
    {
        $resultsIterators = new \AppendIterator();
        
        // forward
        $resultsIterators->append($generator->getIndex()->getIterator());
        
        //backward
        $backward = $generator->getIndex()->getIterator();
        $backward->setDirection(index\KeyReader::DIRECTION_BACKWARD);
        $resultsIterators->append($backward);
        
        // each key should be in the generated index
        foreach ($resultsIterators as $result) {
            $this->assertTrue($generator->isKey($result->getKey()));
            $this->assertEquals($generator->generateData($result->getKey()), $result->getData());
            
        }
    }
    
    /**
     * Tests that every key of the index will be found
     * 
     * @param IndexGenerator $generator 
     * @dataProvider provideTestCases
     */
    public function testFindAllKeys(IndexGenerator $generator)
    {
        // TODO
        $this->markTestIncomplete();
    }
    
    /**
     * Provides test cases
     * 
     * @return array
     */
    public function provideTestCases()
    {
        $cases = array();
        
        // empty Index
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(0);
        $cases[] = array($generator);
        
        // 1 entry
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(1);
        $cases[] = array($generator);
        
        // 2 entries
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(2);
        $cases[] = array($generator);
        
        // 1000 entries
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(1000);
        $cases[] = array($generator);
        
        return $cases;
    }
    
}