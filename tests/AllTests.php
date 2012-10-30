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
        $suite->addTestFiles(new \GlobIterator(__DIR__ . "/Test*.php"));
        return $suite;
    }

}