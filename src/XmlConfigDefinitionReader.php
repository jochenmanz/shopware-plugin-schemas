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

class XmlConfigDefinitionReader
{
    public function read($file)
    {
        try {
            $dom = XmlUtils::loadFile($file, __DIR__.'/schema/config.xsd');
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

        if (false === $entries = $xpath->query('//elements/element')) {
            return;
        }

        $elements = [];

        /** @var \DOMElement $entry */
        foreach ($entries as $entry) {
            $element = [];

            $isRequired = ($entry->getAttribute('required')) ? XmlUtils::phpize($entry->getAttribute('required')) : false;
            $type = ($entry->getAttribute('type')) ? $entry->getAttribute('type') : 'text';

            $scope = ($entry->getAttribute('scope')) ? $entry->getAttribute('scope') : 'locale';
            if ($scope === 'locale') {
                $scope = 0;
            } elseif ($scope === 'shop') {
                $scope = 1;
            } else {
                throw new \InvalidArgumentException(sprintf("Invalid scope '%s", $scope));
            }

            $element['isRequired'] = $isRequired;
            $element['type'] = $type;
            $element['scope'] = $scope;

            if ($position = $this->getChildren($entry, 'name')) {
                $element['name'] = $position[0]->nodeValue;
            }

            if ($position = $this->getChildren($entry, 'store')) {
                $element['store'] = $position[0]->nodeValue;
            }

            if ($position = $this->getChildren($entry, 'value')) {
                $element['value'] = XmlUtils::phpize($position[0]->nodeValue);
            } else {
                $element['value'] = null;
            }

            foreach ($this->getChildren($entry, 'description') as $label) {
                $lang = ($label->getAttribute('lang')) ? $label->getAttribute('lang') : 'en';
                $element['description'][$lang] = $label->nodeValue;
            }

            foreach ($this->getChildren($entry, 'label') as $label) {
                $lang = ($label->getAttribute('lang')) ? $label->getAttribute('lang') : 'en';
                $element['label'][$lang] = $label->nodeValue;
            }

            $element['options'] = [];
            foreach ($this->getChildren($entry, 'options') as $option) {
                foreach ($option->childNodes as $node) {
                    if (!$node instanceof \DOMElement) {
                        continue;
                    }
                    $element['options'][$node->nodeName] = XmlUtils::phpize($node->textContent);
                }
            }

            $elements[] = $element;
        }

        return $elements;
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
