#! /usr/bin/php
<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * XML index example
 *
 * The example shows how to use an XML document with sorted elements.
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


// Set the namespace
namespace de\malkusch\index;


try  {
    /**
     * Setup up an autoloader which finds the index classes automatically.
     * This autoloader implementation is only a recommendation. You can use
     * any autoloader which will find the classes.
     *
     * @see http://php-autoloader.malkusch.de
     */
    require "autoloader/Autoloader.php";
    \Autoloader::getRegisteredAutoloader()->remove();
    $autoloader = new \Autoloader(__DIR__ . "/../..");
    $autoloader->register();


    // Define the index
    $index
        = new Index_XML(
            __DIR__ . "/index.xml", // Index file
            "container", // Container element
            "index" // Index attribute of the container element
        );

    // Search the data for the key 1234
    $data = $index->search(1234);

    /**
     * The returned data is the XML as string. You can use SimpleXML to browse
     * the data.
     *
     * @see SimpleXML
     */
    $xml = new \SimpleXMLElement($data);
    var_dump((string) $xml->payload);

    /* Search the data for the nonexistend key 12345
     * This will throw the IndexException_NotFound.
     */
    $data = $index->search(12345);

} catch (IndexException_NotFound $e) {
    // Key wasn't found
    echo $e->getMessage(), "\n";

}