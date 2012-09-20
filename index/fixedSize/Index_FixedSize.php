<?php

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
 * @author   Markus Malkusch <markus@malkusch.de>
 * @link     https://github.com/malkusch/php-index
 */
class Index_FixedSize extends Index
{
    
    /**
     * @var int
     */
    private $indexFieldOffset = 0;
    /**
     * @var int
     */
    private $indexFieldLength = 0;
    
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
        
        $this->indexFieldOffset = $indexFieldOffset;
        $this->indexFieldLength = $indexFieldLength;
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
        return $this->indexFieldOffset;
    }
    
    /**
     * Returns the length of the index field
     * 
     * @return int
     */
    public function getIndexFieldLength()
    {
        return $this->indexFieldLength;
    }

}