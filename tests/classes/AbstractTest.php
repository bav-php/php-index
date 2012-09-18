<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class AbstractTest
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
namespace malkusch\index\test;
use malkusch\index as index;

/**
 * Setup autoloader
 */
require_once __DIR__ . "/autoloader/autoloader.php";
require_once __DIR__ . "/../../index/Index.php";


/**
 * Abstract test
 *
 * The purpose of this class is defining an autoloader.
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
abstract class AbstractTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Asserts the complexity for an index
     *
     * The complexity is always O(log(n)).
     *
     * @param IndexGenerator $generator Index generator
     * @param SplitCounter   $counter   Split counter in binary search
     *
     * @return float
     */
    protected function assertComplexity(
        IndexGenerator $generator,
        SplitCounter $counter
    ) {
        if (\count($counter) == 0) {
            return;
            
        }
        $this->assertLessThan(
            \log($generator->getIndexLength(), 2) * 2,
            \count($counter)
        );
    }

}