<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class IndexGenerator_FixedSize
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
 */

/**
 * Namespace
 */
namespace malkusch\index\test;
use malkusch\index as index;

/**
 * Generates a plain text index
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: 1.0
 * @link     https://github.com/malkusch/php-index
 */
class IndexGenerator_FixedSize extends IndexGenerator
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
     * Sets the index offset
     *
     * @param int $indexFieldOffset offset of the index in each line
     * 
     * @return void
     */
    public function setIndexFieldOffset($indexFieldOffset)
    {
        $this->_indexFieldOffset = $indexFieldOffset;
    }
    
    /**
     * Sets the index length
     *
     * @param int $indexFieldLength length of the index
     * 
     * @return void
     */
    public function setIndexFieldLength($indexFieldLength)
    {
        $this->_indexFieldLength = $indexFieldLength;
    }
    
    /**
     * Returns the length of the index field
     * 
     * @return int
     */
    public function getIndexFieldLength()
    {
        return $this->_indexFieldLength < strlen($this->getMaximum())
            ? strlen($this->getMaximum())
            : $this->_indexFieldLength;
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
        $filepointer = @fopen($file, "w");
        if (!is_resource($filepointer)) {
            throw new IndexTestException_CreateFile(
                sprintf(
                    "Could not open '%s': %s",
                    $file,
                    error_get_last()
                )
            );
            
        }
        
        $padding = str_repeat(" ", $this->_indexFieldOffset);
        
        for (
            $key = 0;
            $key <= $this->getMaximum();
            $key += $this->getStepSize()
        ) {
            $indexKey = str_pad($key, $this->getIndexFieldLength());
            $line = $padding.$indexKey.$this->generateData($key)."\n";
            
            $bytes = @fputs($filepointer, $line);
            if ($bytes != strlen($line)) {
                throw new IndexTestException_CreateFile(
                    sprintf(
                        "Could not write line '%s': %s",
                        $line,
                        error_get_last()
                    )
                );
                
            }
        }
        fclose($filepointer);
    }
    
    /**
     * Creates a new Index
     *
     * @var string $file Path to the index
     *
     * @return Index_FixedSize
     */
    protected function createIndex($file)
    {
        return new index\Index_FixedSize(
            $file, $this->_indexFieldOffset, $this->getIndexFieldLength()
        );
    }
    
    /**
     * Returns the index file name without the directory path
     *
     * @return string
     */
    protected function getIndexFileName()
    {
        return sprintf(
            "%d.%d-%d-%d.txt",
            $this->_indexFieldOffset,
            $this->getIndexFieldLength(),
            $this->getIndexLength(),
            $this->getStepSize()
        );
    }

}