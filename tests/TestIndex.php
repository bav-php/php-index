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
     * Tests the an index is search with O(log(n))
     *
     * @param Index $index     Index
     * @param int   $indexSize Index size
     *
     * @return void
     * @dataProvider provideTestComplexity
     */
    public function testComplexity(
        index\Index $index,
        $indexSize
    ) {
        for ($i = 0; $i < $indexSize; $i++) {
            $counter = new SplitCounter();
            $index->search($i);
            $counter->stopCounting();

            $this->assertLessThan(
                log($indexSize, 2) * 2,
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
        $helper = new TestHelper();

        $cases[]
            = array(
                $helper->getIndex_XML("container", "index", 10000, true),
                10000
            );

        $cases[]
            = array(
                $helper->getIndex_XML("container", "index", 10000, false),
                10000
            );

        return $cases;
    }

}