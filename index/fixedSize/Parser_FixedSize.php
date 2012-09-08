<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class Parser_FixedSize
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
 * Index_FixedSize parser
 *
 * The parser finds key and data in the index.
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
class Parser_FixedSize extends Parser
{

    /**
     * Returns an array with FoundKey objects
     *
     * $data is parsed for keys. The found keys are returned.
     *
     * @param string $data   Parseable data
     * @param int    $offset Position where the data came from
     *
     * @return array
     * @see FoundKey
     */
    public function parseKeys($data, $offset)
    {
        $pregExp = \sprintf(
            "/(%s).{%d}(.{%d})/",
            $offset == 0 ? '^|\n' : '\n',
            $this->getIndex()->getIndexFieldOffset(),
            $this->getIndex()->getIndexFieldLength()
        );
        \preg_match_all(
            $pregExp,
            $data,
            $matches,
            PREG_OFFSET_CAPTURE | PREG_SET_ORDER
        );
        
        $keys = array();

        foreach ($matches as $match) {
            $keyOffset = $offset + $match[0][1] + 1;
            $key = $match[2][0];
            $keys[] = new FoundKey($keyOffset, $key);

        }
        
        // The first match doesn't begin with \n
        if ($offset == 0 && ! empty($keys)) {
            $keys[0]->setOffset(0);
            
        }
        
        return $keys;
    }
    
    /**
     * Returns the data container which starts at $offset
     *
     * The offset is a result of parseKeys().
     *
     * @param int $offset Offset of the container
     *
     * @return string
     * @see Parser::parseKeys()
     * @throws IndexException_ReadData
     */
    public function getData($offset)
    {
        $filePointer = $this->getIndex()->getFile()->getFilePointer();
        \fseek($filePointer, $offset);
        $data = \fgets($filePointer);
        
        if ($data === false) {
            $error = \error_get_last();
            throw new IndexException_ReadData("Failed to read data: $error");
            
        }
        
        // strip the trailing \n
        if (! \feof($filePointer)) {
            $data = substr($data, 0, -1);
            
        }
        
        return $data;
    }
    
    /**
     * Returns the index
     *
     * @return Index_FixedSize
     */
    public function getIndex()
    {
        return parent::getIndex();
    }

}