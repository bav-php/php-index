<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class File
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
namespace de\malkusch\index;

/**
 * Wrapper for file operations and informations
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
class File
{
    
    const
    /**
     * Sector size
     */
    DEFAULT_BLOCK_SIZE = 512;
    
    private
    /**
     * @var int
     */
    $_blocksize = self::DEFAULT_BLOCK_SIZE,
    /**
     * @var String
     */
    $_path = "",
    /**
     * @var int
     */
    $_size = 0,
    /**
     * @var resource
     */
    $_filePointer;
    
    /**
     * Sets the index file and opens a file pointer
     *
     * @param string $path Index file
     *
     * @throws IndexException_IO_FileExists
     * @throws IndexException_IO
     */
    public function __construct($path)
    {
        $this->_path = $path;
        
        // Open the file
        $this->_filePointer = @\fopen($path, "rb");
        if (! \is_resource($this->_filePointer)) {
            if (! \file_exists($path)) {
                throw new IndexException_IO_FileExists(
                    "'$path' doesn't exist."
                );

            }
            $errors = \error_get_last();
            throw new IndexException_IO($errors["message"]);

        }
        
        // Read the filesystem's blocksize
        $stat = \stat($path);
        if (\is_array($stat)
            && isset($stat["blksize"])
            && $stat["blksize"] > 0
        ) {
            $this->_blocksize = $stat["blksize"];
            
        }
        
        // Read the size
        $this->_size = \filesize($path);
        if ($this->_size === false) {
            throw new IndexException_IO("Can't read size of '$path'");
            
        }
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
    
    /**
     * Returns the path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }
    
    /**
     * Returns the file size
     * 
     * @return int 
     */
    public function getFileSize()
    {
        return $this->_size;
    }
    
    /**
     * Returns the blocksize of the file's filesystem
     * 
     * @return int 
     */
    public function getBlockSize()
    {
        return $this->_blocksize;
    }
    
}