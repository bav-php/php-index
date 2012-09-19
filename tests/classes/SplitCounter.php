<?php

namespace malkusch\index\test;
use malkusch\index as index;

/**
 * Counts the splits in a binary search
 *
 * @author   Markus Malkusch <markus@malkusch.de>
 * @link     https://github.com/malkusch/php-index
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