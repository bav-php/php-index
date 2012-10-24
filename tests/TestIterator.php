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
    public function testKeysInIndex(IndexGenerator $generator)
    {
        // each key should be in the generated index
        foreach ($generator->getIndex() as $result) {
            $this->assertTrue($generator->isKey($result->getKey()));
            
        }
    }
    
    /**
     * Tests defined cases
     * 
     * @dataProvider provideTestIteratorCases
     */
    public function testIteratorCases(index\IndexIterator $iterator, array $expectedKeys)
    {
        $this->assertEquals($expectedKeys, index\IteratorUtil::toKeysArray($iterator));
    }
    
    /**
     * Test cases 
     */
    public function provideTestIteratorCases()
    {
        $cases = array();
        
        // [0,9] -> [1,0]
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(10);
        $index = $generator->getIndex();
        $iterator = $index->getIterator();
        $iterator->setOffset($index->search(1)->getOffset(), index\Parser::HINT_RESULT_BOUNDARY);
        $iterator->setDirection(index\KeyReader::DIRECTION_BACKWARD);
        $cases[] = array($iterator, array(1, 0));
        
        return $cases;
    }
    
    /**
     * Tests backward iteration
     * 
     * @dataProvider provideTestCases
     */
    public function testBackward(IndexGenerator $generator)
    {
        $keys = array();
        foreach ($generator->getIndex() as $result) {
            $keys[] = $result->getKey();
            
        }
        
        $backwardIterator = $generator->getIndex()->getIterator();
        $backwardIterator->setDirection(index\KeyReader::DIRECTION_BACKWARD);
        
        // test that every found result was in the reversed forward iteration
        foreach($backwardIterator as $result) {
            $this->assertEquals(array_pop($keys), $result->getKey());
            
        }
        
        // No key should be left from the forward iteration
        $this->assertEmpty(
            $keys,
            sprintf(
                "missing keys (%s)",
                implode(", ", $keys)
            )
        );
    }
    
    /**
     * Tests that every key of the index will be found
     * 
     * @param IndexGenerator $generator 
     * @dataProvider provideTestCases
     */
    public function testFindAllKeys(IndexGenerator $generator)
    {
        $expectedKeys = $generator->getKeys();
        
        foreach($generator->getIndex() as $result) {
            $this->assertEquals(array_shift($expectedKeys), $result->getKey());
            
        }
        $this->assertEmpty($expectedKeys);
    }
    
    /**
     * Tests iterating with a different offset
     * 
     * @dataProvider provideTestCases
     */
    public function testOffset(IndexGenerator $generator)
    {
        $index = $generator->getIndex();
        
        $expectedResults = array();
        foreach ($index->getIterator() as $result) {
            $expectedResults[] = $result;
            
        }
        
        for (
            $offset = 0;
            $offset < $index->getFile()->getFileSize();
            $offset++
        ) {
                
            // shift $expectedResults after passing the first offset
            if (isset($expectedResults[0]) && $offset > $expectedResults[0]->getOffset()) {
                array_shift($expectedResults);
                
            }
            
            $iterator = $index->getIterator();
            if (isset($expectedResults[0]) && $offset == $expectedResults[0]->getOffset()) {
                $iterator->setOffset($offset, index\Parser::HINT_RESULT_BOUNDARY);
                
            } else {
                $iterator->setOffset($offset);
                
            }
            
            $results = array();
            foreach($iterator as $result) {
                $results[] = $result;
                
            }
            
            $expectedArray = $this->toPrimitiveResultArray($expectedResults);
            $resultArray = $this->toPrimitiveResultArray($results);
            
            $this->assertEquals($expectedArray, $resultArray, "not equal at offset $offset");
            
        }
    }
    
    private function toPrimitiveResultArray($results)
    {
        $primitiveResults = array();
        foreach ($results as $result) {
            $primitiveResults[] = array($result->getKey(), $result->getOffset(), $result->getData());
            
        }
        return $primitiveResults;
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
        
        // 10 entries
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(10);
        $cases[] = array($generator);
        
        // 1000 entries
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(1000);
        $cases[] = array($generator);
        
        // 10000 entries
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(10000);
        $cases[] = array($generator);
        
        return $cases;
    }
    
}