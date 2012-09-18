<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class Parser_XML
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
namespace malkusch\index;

/**
 * XML Parser to find an index in an attribute of a container element
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
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
     * Returns an array with FoundKey objects
     *
     * $data is parsed for keys. The found keys are returned.
     *
     * @param string $data   Parseable data
     * @param int    $offset The position where the date came from
     *
     * @return array
     * @see FoundKey
     */
    public function parseKeys($data, $offset)
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
     * @throws IndexException_ReadData
     */
    public function getData($offset)
    {
        $this->_parserPosition = NULL;
        $this->_parserLevel    = 0;
        $data        = "";
        $parser      = @\xml_parser_create();
        $filePointer = $this->getIndex()->getFile()->getFilePointer();

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
            && $chunk = \fread($filePointer, $this->getIndex()->getFile()->getBlockSize())
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