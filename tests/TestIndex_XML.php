<?php

namespace malkusch\index\test;
use malkusch\index as index;

require_once __DIR__ . "/classes/AbstractTest.php";


/**
 * Tests the xml index
 *
 * @author   Markus Malkusch <markus@malkusch.de>
 * @link     https://github.com/malkusch/php-index
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
    /*
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
     */
    
    public function testIncomplete()
    {
        $this->markTestIncomplete();
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