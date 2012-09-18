<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class Parser
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
 * @link      https://github.com/malkusch/php-index
 */

/**
 * Namespace
 */
namespace malkusch\index;

/**
 * Index parser
 *
 * The parser finds key and data in the index.
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: 1.0
 * @link     https://github.com/malkusch/php-index
 */
abstract class Parser
{

    private
    /**
     * @var Index
     */
    $_index;

    /**
     * Returns an array with FoundKey objects
     *
     * $data is parsed for keys. The found keys are returned.
     *
     * @param string $data   Parseable data
     * @param int    $offset The position where the date came from
     *
     * @return array
     * @see Result
     */
    abstract public function parseKeys($data, $offset);
    /**
     * Returns the data container which starts at $offset
     *
     * The offset is a result of pareKeys().
     *
     * @param int $offset Offset of the container
     *
     * @return string
     * @see Parser::parseKeys()
     * @throws IndexException_ReadData
     */
    abstract public function getData($offset);

    /**
     * Sets the index
     *
     * @param Index $index Index
     */
    public function __construct(Index $index)
    {
        $this->_index = $index;
    }

    /**
     * Returns the index
     *
     * @return Index
     */
    public function getIndex()
    {
        return $this->_index;
    }

}