#! /usr/bin/php
<?php

/**
 * Index in a plain text file example
 *
 * The example shows how to use a plain text file with a defined index in each
 * line.
 * 
 * @author    Markus Malkusch <markus@malkusch.de>
 * @link      https://github.com/malkusch/php-index
 */

use malkusch\index\Index_FixedSize;
use malkusch\index\IndexException_IO;
use malkusch\index\IndexException_ReadData;

// Include the index and its autoloader
require_once __DIR__ . "/../../index/Index.php";

try  {
    // Define the index
    $index = new Index_FixedSize(
        __DIR__ . "/index.txt", // Index file
        0, // offset of the index in each line
        8 // length of the index
    );

    // Search the data for the key 10077777
    $data = $index->search(10077777);

    if ($data != null) {
        \var_dump($data);
        
    } else {
        echo "Didn't find key 10020500\n";
        
    }

    // Search the data for the nonexistend key 12345.
    $data = $index->search(12345);
    
    if ($data != null) {
        \var_dump($data);
        
    } else {
        echo "Didn't find key 12345\n";
        
    }

// IO Error during opening or reading the index
} catch (IndexException_IO $e) {
    echo $e->getMessage(), "\n";

// Error while reading found data
} catch (IndexException_ReadData $e) {
    echo $e->getMessage(), "\n";

}