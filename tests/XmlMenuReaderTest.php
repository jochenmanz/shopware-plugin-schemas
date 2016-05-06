<?php
use Shopware\PluginConfig\XmlMenuReader;

class XmlMenuReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testCanReadAndVerify()
    {
        $reader = new XmlMenuReader();
        $result = $reader->read(__DIR__.'/_files/menu.xml');

        $this->assertInternalType('array', $result);
        fwrite(STDERR, print_r($result, true));
    }
}
