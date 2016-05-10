<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\Components\Plugin;

use Symfony\Component\Config\Util\XmlUtils;

class XmlMenuReader
{
    public function read($file)
    {
        try {
            $dom = XmlUtils::loadFile($file, __DIR__.'/schema/menu.xsd');
        } catch (\Exception $e) {
            throw $e;
//            throw new MappingException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->parseMenu($dom);
    }

    /**
     * @param \DOMDocument $xml
     * @return array
     */
    private function parseMenu(\DOMDocument $xml)
    {
        $xpath = new \DOMXPath($xml);

        if (false === $entries = $xpath->query('//entries/entry')) {
            return;
        }

        $menu = [];
        $menuEntry = [];


        foreach ($entries as $entry) {
            if ($names = $this->getChildren($entry, 'name')) {
                $menuEntry['name'] = $names[0]->nodeValue;
            }

            if ($controllers = $this->getChildren($entry, 'controller')) {
                $menuEntry['controller'] = $controllers[0]->nodeValue;
            }

            if ($class = $this->getChildren($entry, 'class')) {
                $menuEntry['class'] = $class[0]->nodeValue;
            }

            if ($active = $this->getChildren($entry, 'active')) {
                $menuEntry['active'] = XmlUtils::phpize($active[0]->nodeValue);
            }

            if ($value = $this->getChildren($entry, 'parent')) {
                $key = ($value[0]->getAttribute('identifiedBy')) ? $value[0]->getAttribute('identifiedBy') : 'controller';
                $value = $value[0]->nodeValue;
                $menuEntry['parent'] = [$key => $value];
            }

            if ($position = $this->getChildren($entry, 'position')) {
                $menuEntry['position'] = (int) $position[0]->nodeValue;
            }

            foreach ($this->getChildren($entry, 'label') as $label) {
                $lang = ($label->getAttribute('lang')) ? $label->getAttribute('lang') : 'en';
                $menuEntry['label'][$lang] = $label->nodeValue;
            }
            $menu[] = $menuEntry;
        }

        return $menu;
    }

    /**
     * Get child elements by name.
     *
     * @param \DOMNode $node
     * @param mixed    $name
     *
     * @return \DOMElement[]
     */
    private function getChildren(\DOMNode $node, $name)
    {
        $children = array();
        foreach ($node->childNodes as $child) {
            if ($child instanceof \DOMElement && $child->localName === $name) {
                $children[] = $child;
            }
        }

        return $children;
    }
}
