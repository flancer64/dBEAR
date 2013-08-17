<?php
/**
 * Copyright (c) 2013, F. Lancer, SIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/**
 * Perform dual parsing and generation between XML schema and PHP objects.
 * User: Alex Gusev <flancer64@gmail.com>
 */

namespace dBEAR\Xml;


use dBEAR\Schema\Domain\Attribute;
use dBEAR\Schema\Domain\Base;
use dBEAR\Schema\Domain\Entity;

class SchemaHandler
{
    /**
     * Load dBEAR schema from DOMDocument.
     * @param \DOMDocument $doc
     * @return Base
     */
    public function parseXmlDocument(\DOMDocument $doc)
    {
        $result = new Base();
        /** dBEAR: root node */
        $root = $doc->documentElement;
        /** parse node's attributes */
        $version = $root->getAttribute(Base::XML_VERSION);
        $result->setVersion($version);
        /** @var  $children \DOMNodeList */
        $children = $root->childNodes;
        /** @var $child \DOMNode */
        foreach ($children as $child) {
            $localName = $child->localName;
            switch ($localName) {
                case Base::XML_ENTITIES:
                    $entities = $this->xmlParseAllEntities($child);
                    $result->setEntities($entities);
                    break;
            }
        }
        /** save current schema as attribute */
        $result->setXmlSchema($doc->saveXML());
        return $result;
    }

    /**
     * Load dBEAR schema from XML file.
     * @param $filename
     * @return Base
     */
    public function parseXmlFile($filename)
    {
        $doc = new \DOMDocument();
        $doc->load($filename);
        return $this->parseXmlDocument($doc);
    }

    /**
     * Load dBEAR schema from XML text.
     * @param $text
     * @return Base
     */
    public function parseXmlText($text)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($text);
        return $this->parseXmlDocument($doc);
    }

    private function xmlParseAllAttributes($node)
    {
        $result = array();
        /** @var  $children \DOMNodeList */
        $children = $node->childNodes;
        /** @var $child \DOMNode */
        foreach ($children as $child) {
            $localName = $child->localName;
            switch ($localName) {
                case Attribute::NAME:
                    $attribute                      = $this->xmlParseAttribute($child);
                    $result[$attribute->getAlias()] = $attribute;
                    break;
            }
        }
        return $result;
    }

    /**
     * @param $node
     * @return array
     */
    private function xmlParseAllEntities($node)
    {
        $result = array();
        /** @var  $children \DOMNodeList */
        $children = $node->childNodes;
        /** @var $child \DOMNode */
        foreach ($children as $child) {
            $localName = $child->localName;
            switch ($localName) {
                case Entity::NAME:
                    $entity                      = $this->xmlParseEntity($child);
                    $result[$entity->getAlias()] = $entity;
                    break;
            }
        }
        return $result;
    }

    private function xmlParseAttribute(\DOMElement $node)
    {
        $result = new Attribute();
        /** parse node's attributes */
        $alias = $node->getAttribute(Attribute::XML_ALIAS);
        $result->setAlias($alias);
        $required = $node->getAttribute(Attribute::XML_REQUIRED);
        $result->setIsRequired($required);
        $temporal = $node->getAttribute(Attribute::XML_TEMPORAL);
        $result->setIsTemporal($temporal);
        /** @var  $children \DOMNodeList */
        $children = $node->childNodes;
        /** @var $child \DOMNode */
        foreach ($children as $child) {
            $localName = $child->localName;
            switch ($localName) {
                case Attribute::XML_NAME:
                    $val = $child->nodeValue;
                    $result->setName($val);
                    break;
                case Attribute::XML_NOTES:
                    $val = $child->nodeValue;
                    $result->setNotes($val);
                    break;

                case Attribute::XML_TYPE:
                    $val = $child->nodeValue;
                    $result->setType($val);
                    break;
            }
        }
        return $result;
    }

    private function xmlParseEntity($node)
    {
        $result = new Entity();
        /** parse node's attributes */
        $alias = $node->getAttribute(Entity::XML_ALIAS);
        $result->setAlias($alias);
        /** @var  $children \DOMNodeList */
        $children = $node->childNodes;
        /** @var $child \DOMNode */
        foreach ($children as $child) {
            $localName = $child->localName;
            switch ($localName) {
                case Entity::XML_ATTRIBUTES:
                    $attributes = $this->xmlParseAllAttributes($child);
                    $result->setAttributes($attributes);
                    break;
                case Entity::XML_NOTES:
                    $val = $child->nodeValue;
                    $result->setNotes($val);
                    break;
            }
        }
        /** link attributes to the entity */
        foreach ($result->getAttributes() as $one) {
            /** @var $one Attribute */
            $one->setEntity($result->getAlias());
        }
        return $result;
    }
}