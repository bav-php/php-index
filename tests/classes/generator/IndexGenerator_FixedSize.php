<?php

namespace malkusch\index\test;
use malkusch\index as index;

/**
 * Generates a plain text index
 *
 * @author   Markus Malkusch <markus@malkusch.de>
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