<?php

namespace malkusch\index\test;
use malkusch\index as index;

/**
 * Generates a XML index
 *
 * @author   Markus Malkusch <markus@malkusch.de>
 * @link     https://github.com/malkusch/php-index
 */
class IndexGenerator_XML extends IndexGenerator
{

    const
    /**
     * Payload element
     */
    ELEMENT_PAYLOAD = "payload";

    private
    /**
     * @var bool
     */
    $_formatOutput = true,
    /**
     * @var string
     */
    $_element = "",
    /**
     * @var string
     */
    $_attribute = "";

    /**
     * Sets the element's name and the index attribute
     *
     * @param string $element   Element's name
     * @param string $attribute Index attribute
     */
    public function __construct($element = "container", $attribute = "index")
    {
        $this->_element   = $element;
        $this->_attribute = $attribute;
    }
    
    /**
     * Sets if the output will be nice XML
     *
     * Per default it is nice XML.
     *
     * @param bool $isFormatted Is output formatted
     * 
     * @return void
     */
    public function formatOutput($isFormatted)
    {
        $this->_formatOutput = $isFormatted;
    }

    /**
     * Returns the container name
     *
     * @return string
     */
    public function getElement()
    {
        return $this->_element;
    }

    /**
     * Returns the index attribute
     *
     * @return string
     */
    public function getAttribute()
    {
        return $this->_attribute;
    }

    /**
     * Creates a new Index
     *
     * @var string $file Path to the index
     *
     * @return Index_XML
     */
    protected function createIndex($file)
    {
        return new index\Index_XML($file, $this->_element, $this->_attribute);
    }

    /**
     * Returns the index file name without the directory path
     *
     * @return string
     */
    protected function getIndexFileName()
    {
        return
            "$this->_element.$this->_attribute-{$this->getIndexLength()}"
            . "-{$this->getStepSize()}-$this->_formatOutput.xml";
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
        $document = new \DOMDocument();
        $document->formatOutput = $this->_formatOutput;

        $root = $document->createElement("document");
        $document->appendChild($root);

        for (
            $key = 0;
            $key < $this->getIndexLength();
            $key += $this->getStepSize()
        ) {
            $container = $document->createElement($this->_element);
            $root->appendChild($container);

            // Append the index
            $attribute = $document->createAttribute($this->_attribute);
            $container->appendChild($attribute);
            $attribute->value = $key;

            // Append some payload
            $payload = $document->createElement(self::ELEMENT_PAYLOAD);
            $container->appendChild($payload);
            $payload->appendChild(
                $document->createCDATASection($this->generateData($key))
            );

        }

        $bytes = $document->save($file);
        if ($bytes === FALSE) {
            throw new IndexTestException_CreateFile(
                "Could not create test file"
            );

        }
    }

}