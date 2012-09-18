<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class TestIndex_XML
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
namespace malkusch\index\test;
use malkusch\index as index;

/**
 * Includes
 */
require_once __DIR__ . "/classes/AbstractTest.php";


/**
 * Tests the xml index
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
class TestIndex_XML extends AbstractTest
{

    /**
     * Tests finding every key
     *
     * @param IndexGenerator_XML $generator Index generator
     *
     * @dataProvider provideTestSearch
     */
    public function testSearch(
        IndexGenerator_XML $generator
    ) {
        $index = $generator->getIndex();
        for ($key = 0; $key < $generator->getIndexLength(); $key++) {
            $data = $index->search($key);
            $xml  = new \SimpleXMLElement($data);

            // Container
            $this->assertEquals($index->getElement(), $xml->getName());

            // Index
            $attributes = $xml->attributes();
            $this->assertTrue(isset($attributes[$index->getAttribute()]));
            $this->assertEquals(
                $key,
                (string) $attributes[$index->getAttribute()]
            );

            // Data
            $this->assertEquals(
                1,
                count($xml->{IndexGenerator_XML::ELEMENT_PAYLOAD})
            );
            $this->assertRegExp(
                "/^data_{$key}_.+$/",
                (string) $xml->{IndexGenerator_XML::ELEMENT_PAYLOAD}[0]
            );

        }
    }

    /**
     * Test cases for testSearch()
     *
     * @return array
     */
    public function provideTestSearch()
    {
        $cases  = array();

        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(10000);
        $generator->formatOutput(true);
        $cases[] = array($generator);

        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(10000);
        $generator->formatOutput(false);
        $cases[] = array($generator);

        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(1);
        $generator->formatOutput(true);
        $cases[] = array($generator);

        $generator = new IndexGenerator_XML();
        $generator->setIndexLength(1);
        $generator->formatOutput(false);
        $cases[] = array($generator);

        return $cases;
    }

}