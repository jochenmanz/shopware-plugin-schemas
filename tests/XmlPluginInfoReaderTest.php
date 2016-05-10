<?php
use Shopware\Components\Plugin\XmlPluginInfoReader;

class XmlPluginInfoReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testCanReadAndVerify()
    {
        $reader = new XmlPluginInfoReader();
        $result = $reader->read(__DIR__.'/../examples/plugin.xml');

        $this->assertInternalType('array', $result);
        fwrite(STDERR, print_r($result, true));
    }

    public function testCanReadAndVerifyMinimalExample()
    {
        $reader = new XmlPluginInfoReader();
        $result = $reader->read(__DIR__.'/../examples/plugin_minimal.xml');

        $this->assertInternalType('array', $result);
        fwrite(STDERR, print_r($result, true));
    }
}
