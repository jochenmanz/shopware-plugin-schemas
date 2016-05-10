<?php

use Shopware\Components\Plugin\XmlMenuReader;

class XmlMenuReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testCanReadAndVerify()
    {
        $reader = new XmlMenuReader();
        $result = $reader->read(__DIR__.'/../examples/menu.xml');

        $this->assertInternalType('array', $result);
        fwrite(STDERR, print_r($result, true));
    }
}
