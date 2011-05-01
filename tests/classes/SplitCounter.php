<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class SplitCounter
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
 * Counts the splits in a binary search
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
class SplitCounter implements \Countable
{

    const
    /**
     * Splitting context
     */
    CONTEXT_SPLITTING = "splitting",
    /**
     * Searching context
     */
    CONTEXT_SEARCHING = "searching";

    private
    /**
     * @var string
     */
    $_context = self::CONTEXT_SEARCHING,
    /**
     * @var int
     */
    $_count = 0;

    /**
     * Starts the counting for the splits
     */
    public function __construct()
    {
        \register_tick_function(array($this, "countSplit"));
        declare(ticks=1);
    }

    /**
     * Tick handler for counting splits
     *
     * @return void
     */
    public function countSplit()
    {
        $backtrace = \debug_backtrace(false);
        if (\strpos($backtrace[1]["function"], "split") !== false) {
            if ($this->_context == self::CONTEXT_SEARCHING) {
                $this->_context = self::CONTEXT_SPLITTING;
                $this->_count++;

            }
        } elseif (\strpos($backtrace[1]["function"], "search") !== false) {
            $this->_context = self::CONTEXT_SEARCHING;

        }
    }

    /**
     * Returns the counted splits
     *
     * @return int
     */
    public function count()
    {
        return $this->_count;
    }

    /**
     * Stops counting
     *
     * @return void
     */
    public function stopCounting()
    {
        \unregister_tick_function(array($this, "countSplit"));
    }

}