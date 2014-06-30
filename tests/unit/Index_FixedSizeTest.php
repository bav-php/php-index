<?php

namespace malkusch\index\test;
use malkusch\index as index;

require_once __DIR__ . "/../classes/AbstractTest.php";


/**
 * Tests the xml index
 *
 * @author   Markus Malkusch <markus@malkusch.de>
 * @link     https://github.com/malkusch/php-index
 */
class Index_FixedSizeTest extends AbstractTest
{
    
    /**
     * Tests finding all keys with different index configurations 
     * 
     * @dataProvider provideTestIndexConfiguration
     */
    public function testIndexConfiguration($offset, $indexFieldLength, $length)
    {
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexFieldOffset($offset);
        $generator->setIndexFieldLength($indexFieldLength);
        $generator->setIndexLength($length);
        
        $index = $generator->getIndex();
        for ($key = 0; $key < $length; $key++) {
            $result  = $index->search($key);
            $this->assertNotNull($result, "key: $key");
            $this->assertRegExp(
                '/data_' . $key . '_.*\$/s',
                $result->getData(),
                "key: $key"
            );
            
        }
    }
    
    public function provideTestIndexConfiguration()
    {
        $cases = array();
        $lengths = array(1, 10, 100, index\File::DEFAULT_BLOCK_SIZE * 12);
        $offsets = array(0, 1, index\File::DEFAULT_BLOCK_SIZE * 12);
        foreach ($lengths as $length) {
            foreach ($offsets as $offset) {
                $indexFieldLength = 1;
                $cases = \array_merge($cases, array(
                    array($offset, $indexFieldLength, $length)
                ));
                
            }
        }
        return $cases;
    }
    
}