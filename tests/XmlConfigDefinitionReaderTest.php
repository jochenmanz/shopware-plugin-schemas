<?php
use Shopware\PluginConfig\XmlConfigDefinitionReader;

class XmlConfigDefinitionReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testCanReadAndVerify()
    {
        $reader = new XmlConfigDefinitionReader();
        $result = $reader->read(__DIR__.'/_files/config.xml');

        $this->assertInternalType('array', $result);
        fwrite(STDERR, print_r($result, true));
    }
}
