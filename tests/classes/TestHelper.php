<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class TestHelper
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
namespace de\malkusch\index\test;
use de\malkusch\index as index;

/**
 * Helper for tests
 *
 * @category XML
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  GPL 3
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
class TestHelper
{

    const
        /**
         * Payload element
         */
        ELEMENT_PAYLOAD = "payload";

    /**
     * Returns the path to a sample xml file
     *
     * @param Index_XML $index        Index
     * @param int       $indexSize    Elements in the index
     * @param bool      $formatOutput Format output
     *
     * @return Index_XML
     * @throws IndexTestException_CreateFile
     */
    public function getIndex_XML(
        $element,
        $attribute,
        $indexSize = 1000000,
        $formatOutput = true
    ) {
        $file
            = __DIR__
            . "/../var/$element.$attribute-$indexSize-$formatOutput.xml";

        // reuse an existing file
        if (\file_exists($file)) {
            return new index\Index_XML($file, $element, $attribute);

        }
        
        $document = new \DOMDocument();
        $document->formatOutput = $formatOutput;

        $root = $document->createElement("document");
        $document->appendChild($root);

        for ($i = 0; $i < $indexSize; $i++) {
            $container = $document->createElement($element);
            $root->appendChild($container);

            // Append the index
            $attribute = $document->createAttribute($attribute);
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
        return new index\Index_XML($file, $element, $attribute);
    }

}