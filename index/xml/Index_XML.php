<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class Index_XML
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
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
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