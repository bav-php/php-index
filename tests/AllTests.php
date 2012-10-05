<?php

namespace malkusch\index\test;

/**
 * Test suite with all tests
 *
 * @author   Markus Malkusch <markus@malkusch.de>
 * @link     https://github.com/malkusch/php-index
 */
class AllTests extends \PHPUnit_Framework_TestSuite
{

    /**
     * Returns all tests
     *
     * @return AllTests
     */
    public static function suite()
    {
        $suite = new self();

        $suite->addTestFile(__DIR__ . "/TestBinarySearch.php");
        $suite->addTestFile(__DIR__ . "/TestIndex.php");
        $suite->addTestFile(__DIR__ . "/TestIndex_XML.php");
        $suite->addTestFile(__DIR__ . "/TestIterator.php");

        return $suite;
    }

}