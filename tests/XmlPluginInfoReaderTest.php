<?php
use Shopware\PluginConfig\XmlPluginInfoReader;

class XmlPluginInfoReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testCanReadAndVerify()
    {
        $reader = new XmlPluginInfoReader();
        $result = $reader->read(__DIR__.'/_files/plugin.xml');

        $this->assertInternalType('array', $result);
        fwrite(STDERR, print_r($result, true));
    }
}
