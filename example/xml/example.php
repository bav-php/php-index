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

// Set the namespace
namespace de\malkusch\index;

// Include the index and its autoloader
require_once __DIR__ . "/../../index/Index.php";

try  {
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

// Key wasn't found
} catch (IndexException_NotFound $e) {
    echo $e->getMessage(), "\n";

// IO Error during opening or reading the index
} catch (IndexException_IO $e) {
    echo $e->getMessage(), "\n";

// Error while reading found data
} catch (IndexException_ReadData $e) {
    echo $e->getMessage(), "\n";

}