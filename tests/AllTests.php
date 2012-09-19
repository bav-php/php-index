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
    static public function suite()
    {
        $suite = new self();

        $suite->addTestFile(__DIR__ . "/TestBinarySearch.php");
        $suite->addTestFile(__DIR__ . "/TestIndex.php");
        $suite->addTestFile(__DIR__ . "/TestIndex_XML.php");

        return $suite;
    }

}