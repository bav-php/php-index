<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class TestBinarySearch
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
 * Tests the class BinarySearch
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
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
        $generator->setMinimumDataSize(index\BinarySearch::SECTOR_SIZE * 3);

        $index = $generator->getIndex();
        $binarySearch = new index\BinarySearch($index);
        $binarySearch->search(3);

        $reflectedReadSectorCount
            = new \ReflectionProperty($binarySearch, "_readSectorCount");
        $reflectedReadSectorCount->setAccessible(true);
        $readSectorCount = $reflectedReadSectorCount->getValue($binarySearch);

        $this->assertGreaterThanOrEqual(3, $readSectorCount);
    }

}