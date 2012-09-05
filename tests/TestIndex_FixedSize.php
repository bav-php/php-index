<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class TestIndex_XML
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category  Structures
 * @package   index
 * @author    Markus Malkusch <markus@malkusch.de>
 * @copyright 2011 Markus Malkusch
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   SVN: $Id$
 * @link      http://php-index.malkusch.de/en/
 */

/**
 * Namespace
 */
namespace de\malkusch\index\test;
use de\malkusch\index as index;

/**
 * Includes
 */
require_once __DIR__ . "/classes/AbstractTest.php";


/**
 * Tests the xml index
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
class TestIndex_FixedSize extends AbstractTest
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
            $counter = new SplitCounter();
            $data    = $index->search($key);
            $this->assertNotNull($data, "key: $key");
            
            $counter->stopCounting();
            $this->assertComplexity($generator, $counter);
            $this->assertRegExp(
                '/data_' . $key . '_.*\$/s',
                $data,
                "key: $key"
            );
            
        }
    }
    
    public function provideTestIndexConfiguration()
    {
        return array(
            array(0, 10, 10)
            // array(index\File::DEFAULT_SECTOR_SIZE * 24, 1, 10)
        );
        
        $cases = array();
        $lengths = array(1, 10, 100, index\File::DEFAULT_SECTOR_SIZE * 24);
        $offsets = array(0, 1, index\File::DEFAULT_SECTOR_SIZE * 24);
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