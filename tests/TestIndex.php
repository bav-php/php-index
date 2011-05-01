<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class TestIndex
 *
 * PHP version 5
 *
 * LICENSE: This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.
 *
 * @category  Structures
 * @package   index
 * @author    Markus Malkusch <markus@malkusch.de>
 * @copyright 2011 Markus Malkusch
 * @license   GPL 3
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
 * @license  GPL 3
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