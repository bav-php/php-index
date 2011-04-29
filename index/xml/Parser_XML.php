<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class Parser_XML
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
 * @category  XML
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
namespace de\malkusch\index;

/**
 * XML Parser to find an index in an attribute of a container element
 *
 * @category XML
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  GPL 3
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
class Parser_XML extends Parser
{

    private
    /**
     * @var int
     */
    $_parserLevel = 0,
    /**
     * @var int
     */
    $_parserPosition = 0;

    /**
     * Returns the index
     *
     * @return Index_XML
     */
    public function getIndex()
    {
        return parent::getIndex();
    }

    /**
     * Finds keys in a data chunk
     *
     * @param string $data Data chunk of the index
     *
     * @return array
     * @see FoundKey
     */
    public function parseKeys($data)
    {
        $element   = \preg_quote($this->getIndex()->getElement());
        $attribute = \preg_quote($this->getIndex()->getAttribute());
        $pregExp
            = '/<' . $element . '\s([^>]*\s)?'
            . $attribute . '\s*=\s*([\'"])(.+?)\2[^>]*>/si';

        \preg_match_all(
            $pregExp,
            $data,
            $matches,
            PREG_OFFSET_CAPTURE | PREG_SET_ORDER
        );

        $keys = array();

        foreach ($matches as $match) {
            $keys[] = new FoundKey($match[0][1], $match[3][0]);

        }

        return $keys;
    }

    /**
     * Returns the XML container which begins at the specified offset
     *
     * This method uses the event based XML parser. So public handler methods
     * are defined.
     *
     * @param int $offset Offset of the XML container
     *
     * @return string
     */
    public function getData($offset)
    {
        $this->_parserPosition = NULL;
        $this->_parserLevel    = 0;
        $data        = "";
        $parser      = @\xml_parser_create();
        $filePointer = $this->getIndex()->getFilePointer();

        if (! \is_resource($parser)) {
            $error = \error_get_last();
            throw new IndexException_ReadData(
                "Could not create a xml parser: $error[message]"
            );

        }

        \xml_set_element_handler(
            $parser,
            array($this, "onStartElement"),
            array($this, "onEndElement")
        );

        \fseek($filePointer, $offset);
        while (
            \is_null($this->_parserPosition)
            && $chunk = \fread($filePointer, BinarySearch::SECTOR_SIZE)
        ) {
            $data .= $chunk;
            \xml_parse($parser, $chunk);

        }

        \xml_parser_free($parser);

        if (\is_null($this->_parserPosition)) {
            throw new IndexException_ReadData("Did not read any data");

        }
        return \substr($data, 0, $this->_parserPosition);
    }

    /**
     * Handler for an element start event
     *
     * This method is internally used by getData().
     *
     * @param resource $parser     XML Parser
     * @param string   $element    Element name
     * @param array    $attributes Element's attributes
     *
     * @return void
     * @see xml_set_element_handler()
     * @see Parser_XML::getData()
     */
    public function onStartElement($parser, $element, array $attributes)
    {
        $this->_parserLevel++;
    }

    /**
     * Handler for an element end event
     * 
     * This method is internally used by getData().
     *
     * @param resource $parser  XML Parser
     * @param string   $element Element name
     *
     * @return void
     * @throws IndexException_ReadData
     * @see Parser_XML::getData()
     * @see xml_set_element_handler()
     */
    public function onEndElement($parser, $element)
    {
        $this->_parserLevel--;
        if ($this->_parserLevel > 0) {
            return;

        }
        if ($element != \strtoupper($this->getIndex()->getElement())) {
            throw new IndexException_ReadData(
                "Unexpected closing of element '$element'."
            );

        }
        $this->_parserPosition = \xml_get_current_byte_index($parser);
    }

}