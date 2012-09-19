<?php

namespace malkusch\index;

/**
 * Index in XML
 *
 * The index is an attribute of a sorted container element.
 *
 * <code>
 * <index>
 *   <container key="a">
 *      Payload A
 *   </container>
 *   <container key="b">
 *      Payload B
 *   </container>
 *   <container key="c">
 *      Payload C
 *   </container>
 * </index>
 * </code>
 *
 * @author   Markus Malkusch <markus@malkusch.de>
 * @link     https://github.com/malkusch/php-index
 */
class Index_XML extends Index
{
    
    private
    /**
     * @var string
     */
    $_element = "",
    /**
     * @var string
     */
    $_attribute = "";

    /**
     * Sets the index file, container name and the index attribute
     *
     * @param string $path      Index file
     * @param string $element   Container name
     * @param string $attribute Index attribute name
     *
     * @throws IndexException_IO_FileExists
     * @throws IndexException_IO
     */
    public function __construct($path, $element, $attribute)
    {
        parent::__construct($path);
        
        $this->_element   = $element;
        $this->_attribute = $attribute;
    }

    /**
     * Returns the container element name
     *
     * @return string
     */
    public function getElement()
    {
        return $this->_element;
    }

    /**
     * Returns the index attribute name
     *
     * @return string
     */
    public function getAttribute()
    {
        return $this->_attribute;
    }

    /**
     * Returns a parser for this index
     *
     * @return Parser_XML
     */
    public function getParser()
    {
        return new Parser_XML($this);
    }

}