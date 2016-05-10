<?php

use Shopware\Components\Plugin\XmlConfigDefinitionReader;

class XmlConfigDefinitionReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testCanReadAndVerify()
    {
        $reader = new XmlConfigDefinitionReader();
        $result = $reader->read(__DIR__.'/../examples/config.xml');

        $this->assertInternalType('array', $result);
        fwrite(STDERR, print_r($result, true));
    }
}
