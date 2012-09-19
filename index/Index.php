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
 * @link      https://github.com/malkusch/php-index
 * @see       Index_XML
 */

/**
 * Namespace
 */
namespace malkusch\index;

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
 * @link     https://github.com/malkusch/php-index
 */
abstract class Index
{

    /**
     * @var File
     */
    private $_file;
    /**
     * @var KeyReader
     */
    private $_keyReader;
    

    /**
     * Returns a parser for this index
     *
     * @return Parser
     */
    abstract public function getParser();

    /**
     * Sets the index file and inits the index
     *
     * @param string $path Index file
     *
     * @throws IndexException_IO_FileExists
     * @throws IndexException_IO
     */
    public function __construct($path)
    {
        $this->_file = new File($path);
        
        $this->_keyReader = new KeyReader();
        $this->_keyReader->setIndex($this);
    }

    /**
     * Searches for the container with that key
     *
     * Returns null if the key wasn't found.
     * 
     * @param string $key Key in the index
     *
     * @return Result
     * @throws IndexException_ReadData
     */
    public function search($key)
    {
        $binarySearch = new BinarySearch($this);
        $result = $binarySearch->search($key);
        if (\is_null($result) || $result->getKey() != $key) {
            return null;
            
        }
        $result->setData($this->getParser()->getData($result->getOffset()));
        
        return $result;
    }

    /**
     * Returns the index file
     *
     * @return File
     */
    public function getFile()
    {
        return $this->_file;
    }

    /**
     * Returns the KeyReader
     *
     * @return KeyReader
     */
    public function getKeyReader()
    {
        return $this->_keyReader;
    }

}