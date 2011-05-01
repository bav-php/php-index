<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class Index_XML
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
 * @category  Structures
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
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  GPL 3
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
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
     * @param string $file      Index file
     * @param string $element   Container name
     * @param string $attribute Index attribute name
     *
     * @throws IndexException_IO_FileExists
     * @throws IndexException_IO
     */
    public function __construct($file, $element, $attribute)
    {
        parent::__construct($file);
        
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