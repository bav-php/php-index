<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class IndexGenerator_XML
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
 * Generates a XML index
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
class IndexGenerator_XML extends IndexGenerator
{

    const
    /**
     * Payload element
     */
    ELEMENT_PAYLOAD = "payload";

    private
    /**
     * @var bool
     */
    $_formatOutput = true,
    /**
     * @var string
     */
    $_element = "",
    /**
     * @var string
     */
    $_attribute = "";

    /**
     * Sets the element's name and the index attribute
     *
     * @param string $element   Element's name
     * @param string $attribute Index attribute
     */
    public function __construct($element = "container", $attribute = "index")
    {
        $this->_element   = $element;
        $this->_attribute = $attribute;
    }
    
    /**
     * Sets if the output will be nice XML
     *
     * Per default it is nice XML.
     *
     * @param bool $isFormatted Is output formatted
     * 
     * @return void
     */
    public function formatOutput($isFormatted)
    {
        $this->_formatOutput = $isFormatted;
    }

    /**
     * Returns the container name
     *
     * @return string
     */
    public function getElement()
    {
        return $this->_element;
    }

    /**
     * Returns the index attribute
     *
     * @return string
     */
    public function getAttribute()
    {
        return $this->_attribute;
    }

    /**
     * Creates a new Index
     *
     * @var string $file Path to the index
     *
     * @return Index_XML
     */
    protected function createIndex($file)
    {
        return new index\Index_XML($file, $this->_element, $this->_attribute);
    }

    /**
     * Returns the index file name without the directory path
     *
     * @return string
     */
    protected function getIndexFileName()
    {
        return
            "$this->_element.$this->_attribute-{$this->getIndexLength()}"
            . "-{$this->getStepSize()}-$this->_formatOutput.xml";
    }

    /**
     * Creates a new Index file
     *
     * @var string $file Path to the index
     *
     * @return void
     * @throws IndexTestException_CreateFile
     */
    protected function createIndexFile($file)
    {
        $document = new \DOMDocument();
        $document->formatOutput = $this->_formatOutput;

        $root = $document->createElement("document");
        $document->appendChild($root);

        for ($i = 0; $i < $this->getIndexLength(); $i += $this->getStepSize()) {
            $container = $document->createElement($this->_element);
            $root->appendChild($container);

            // Append the index
            $attribute = $document->createAttribute($this->_attribute);
            $container->appendChild($attribute);
            $attribute->value = $i;

            // Append some payload
            $payload = $document->createElement(self::ELEMENT_PAYLOAD);
            $container->appendChild($payload);
            $payload->appendChild(
                $document->createCDATASection(\uniqid("data_{$i}_", true))
            );

        }

        $bytes = $document->save($file);
        if ($bytes === FALSE) {
            throw new IndexTestException_CreateFile(
                "Could not create test file"
            );

        }
    }

}