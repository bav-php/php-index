<?php

namespace malkusch\index;

/**
 * Index parser
 *
 * The parser finds key and data in the index.
 *
 * @author   Markus Malkusch <markus@malkusch.de>
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