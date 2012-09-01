<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class TestIndex
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
 * Tests an index
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
class TestIndex extends AbstractTest
{

    /**
     * Tests searching in the index and its complexity
     *
     * @param IndexGenerator $generator  Index generator
     * @param bool           $reuseIndex Index will be reused
     *
     * @return void
     * @dataProvider provideTestSearch
     */
    public function testSearch(
        IndexGenerator $generator,
        $reuseIndex = true
    ) {
        $index = $generator->getIndex();
        for ($key = 0; $key < $generator->getIndexLength(); $key++) {
            if (! $reuseIndex) {
                $index = $generator->getIndex();

            }
            $counter = new SplitCounter();
            $data    = $index->search($key);
            $this->assertNotNull($data);
            
            $counter->stopCounting();
            $this->assertComplexity($generator, $counter);
            $this->assertRegExp(
                '/data_' . $key . '_.*\$/s',
                $data
            );

        }
    }

    /**
     * Test cases for testSearch()
     *
     * @return Array
     * @see TestIndex::testSearch()
     */
    public function provideTestSearch()
    {
        $cases  = array();

        // Large Container
        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(100);
        $generator->setMinimumDataSize(index\BinarySearch::SECTOR_SIZE * 3);
        $cases[] = array($generator);
        
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(100);
        $generator->setMinimumDataSize(index\BinarySearch::SECTOR_SIZE * 3);
        $cases[] = array($generator);
        
        // Large Container, with new index each key
        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(100);
        $generator->setMinimumDataSize(index\BinarySearch::SECTOR_SIZE * 3);
        $cases[] = array($generator, false);
        
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(100);
        $generator->setMinimumDataSize(index\BinarySearch::SECTOR_SIZE * 3);
        $cases[] = array($generator, false);
        
        // Large index
        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(10000);
        $generator->formatOutput(true);
        $cases[] = array($generator);
        
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(10000);
        $cases[] = array($generator);
        
        // Index in one line
        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(10000);
        $generator->formatOutput(false);
        $cases[] = array($generator);
        
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(10000);
        $cases[] = array($generator);

        // Index has only one element
        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(1);
        $generator->formatOutput(true);
        $cases[] = array($generator);
        
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(1);
        $cases[] = array($generator);
        
        // Index has only one element and is only one line
        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(1);
        $generator->formatOutput(false);
        $cases[] = array($generator);
        
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(1);
        $cases[] = array($generator);

        return $cases;
    }

    /**
     * Tests that failing terminates and its complexity
     *
     * @param IndexGenerator $generator Index generator
     *
     * @return void
     * @dataProvider provideTestFailSearch
     */
    public function testFailSearch(
        IndexGenerator $generator
    ) {
        $index = $generator->getIndex();
        $start = $generator->getMinimum() - 1;
        $end   = $generator->getMaximum() + 2 * $generator->getStepSize();
        for ($key = $start; $key <= $end; $key += 1) {
            if ($generator->isKey($key)) {
                continue;
                
            }
            $counter = new SplitCounter();
            
            $data = $index->search($key);
            $this->assertNull($data);
                
            $counter->stopCounting();
            $this->assertComplexity($generator, $counter);

        }
    }

    /**
     * Test cases for testFailSearch()
     *
     * @return void
     */
    public function provideTestFailSearch()
    {
        $cases  = array();
        
        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(10);
        $generator->formatOutput(true);
        $generator->setStepSize(2);
        $cases[] = array($generator);
        
        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(10);
        $generator->setStepSize(2);
        $cases[] = array($generator);
        
        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(1000);
        $generator->formatOutput(true);
        $generator->setStepSize(2);
        $cases[] = array($generator);

        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(1000);
        $generator->setStepSize(2);
        $cases[] = array($generator);

        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(10000);
        $generator->formatOutput(true);
        $generator->setStepSize(2);
        $cases[] = array($generator);

        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(10000);
        $generator->setStepSize(2);
        $cases[] = array($generator);

        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(10000);
        $generator->formatOutput(false);
        $generator->setStepSize(2);
        $cases[] = array($generator);

        $generator = new IndexGenerator_FixedSize();
        $generator->setIndexLength(10000);
        $generator->setStepSize(2);
        $cases[] = array($generator);

        return $cases;
    }

}