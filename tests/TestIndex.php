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
 * Tests the xml index
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
     * Returns the expected complexity for a gernerated index
     *
     * The complexity is always O(log(n)).
     *
     * @param IndexGenerator $generator Index generator
     *
     * @return float
     */
    private function _getComplexity(IndexGenerator $generator)
    {
        return \log($generator->getIndexLength(), 2) * 2;
    }

    /**
     * Tests that a successfull index search is in O(log(n))
     *
     * @param IndexGenerator $generator Index generator
     *
     * @return void
     * @dataProvider provideTestComplexity
     */
    public function testComplexity(
        IndexGenerator $generator
    ) {
        $index = $generator->getIndex();
        for ($key = 0; $key < $generator->getIndexLength(); $key++) {
            $counter = new SplitCounter();
            $index->search($key);
            $counter->stopCounting();

            $this->assertLessThan(
                $this->_getComplexity($generator),
                \count($counter)
            );

        }
    }

    /**
     * Test cases for testComplexity()
     *
     * @return void
     */
    public function provideTestComplexity()
    {
        $cases  = array();

        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(10000);
        $generator->formatOutput(true);
        $cases[] = array($generator);

        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(10000);
        $generator->formatOutput(false);
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
        $stepSize = $generator->getStepSize();
        for ($key = $start; $key <= $end; $key += 1) {
            if ($generator->isKey($key)) {
                continue;
                
            }
            $counter = new SplitCounter();
            try {
                $index->search($key);
                $this->fail("Positive search not expected");

            } catch (index\IndexException_NotFound $e) {
                $counter->stopCounting();
                $this->assertLessThan(
                    $this->_getComplexity($generator),
                    \count($counter)
                );

            }
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
        $generator->setIndexLength(10000);
        $generator->formatOutput(true);
        $generator->setStepSize(2);
        $cases[] = array($generator);

        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(10000);
        $generator->formatOutput(false);
        $generator->setStepSize(2);
        $cases[] = array($generator);

        return $cases;
    }

}