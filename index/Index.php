<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class Index
 *
 * This class is only an abstract index. You need to use the appropriate
 * implementation which fits your needs. Currently these implementations exists:
 *
 * Index_XML - Index is the attribute of a XML container
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
 * @see       Index_XML
 */

/**
 * Namespace
 */
namespace de\malkusch\index;

/**
 * Autoloader
 *
 * @see http://php-autoloader.malkusch.de
 */
require_once __DIR__ . "/autoloader/autoloader.php";

/**
 * Index
 *
 * The index does a binary search on a key. That means that the data needs to
 * have a sorted index. You simply call the method Index::search() to find the
 * container for the key.
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
abstract class Index
{

    private
    /**
     * @var string
     */
    $_file = "",
    /**
     * @var resource
     */
    $_filePointer;

    /**
     * Returns a parser for this index
     *
     * @return Parser
     */
    abstract public function getParser();

    /**
     * Sets the index file and opens a file pointer
     *
     * @param string $file Index file
     *
     * @throws IndexException_IO_FileExists
     * @throws IndexException_IO
     */
    public function __construct($file)
    {
        $this->_file        = $file;
        $this->_filePointer = @\fopen($file, "rb");

        if (! \is_resource($this->_filePointer)) {
            if (! \file_exists($file)) {
                throw new IndexException_IO_FileExists(
                    "'$file' doesn't exist."
                );

            }
            $errors = \error_get_last();
            throw new IndexException_IO($errors["message"]);

        }
    }

    /**
     * Searches for the container with that keyword
     *
     * @param string $key Key in the index
     *
     * @return string
     * @throws IndexException_NotFound
     * @throws IndexException_ReadData
     */
    public function search($key)
    {
        $binarySearch = new BinarySearch($this);
        $offset = $binarySearch->search($key);
        return $this->getParser()->getData($offset);
    }

    /**
     * Returns the index file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->_file;
    }

    /**
     * Returns an open file pointer for reading in binary mode
     *
     * @return resource
     */
    public function getFilePointer()
    {
        return $this->_filePointer;
    }
    
}