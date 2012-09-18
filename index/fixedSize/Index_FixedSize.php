<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class Index_FixedSize
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
 * @copyright 2012 Markus Malkusch
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   SVN: $Id$
 * @link      http://php-index.malkusch.de/en/
 */

/**
 * Namespace
 */
namespace malkusch\index;

/**
 * Index in a plain text file.
 * 
 * The index for this data structure resides always at the same offset in each
 * line and has a fixed length.
 * 
 * 001 Payload 1
 * 002 Payload 2
 * 003 Payload 3
 * 004 Payload 4
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
class Index_FixedSize extends Index
{
    
    private
    /**
     * @var int
     */
    $_indexFieldOffset = 0,
    /**
     * @var int
     */
    $_indexFieldLength = 0;
    
    /**
     * Sets the index file, index offset and the index length
     *
     * @param string $path             Index file
     * @param string $indexFieldOffset Index field offset
     * @param string $indexFieldLength Index field length
     *
     * @throws IndexException_IO_FileExists
     * @throws IndexException_IO
     */
    public function __construct($path, $indexFieldOffset, $indexFieldLength)
    {
        parent::__construct($path);
        
        $this->_indexFieldOffset = $indexFieldOffset;
        $this->_indexFieldLength = $indexFieldLength;
    }
    
   /**
     * Returns a parser for this index
     *
     * @return Parser_FixedSize
     */
    public function getParser()
    {
        return new Parser_FixedSize($this);
    }
    
    /**
     * Returns the offset of the index field
     * 
     * @return int
     */
    public function getIndexFieldOffset()
    {
        return $this->_indexFieldOffset;
    }
    
    /**
     * Returns the length of the index field
     * 
     * @return int
     */
    public function getIndexFieldLength()
    {
        return $this->_indexFieldLength;
    }

}