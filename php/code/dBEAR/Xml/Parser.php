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
 * Parses XML structure and converts to PHP objects.
 * User: Alex Gusev <flancer64@gmail.com>
 */

namespace dBEAR\Xml;


use dBEAR\Schema\Attribute;
use dBEAR\Schema\Base;
use dBEAR\Schema\Entity;

class Parser
{
    /**
     * @param \DOMDocument $doc
     * @return Base
     */
    public static function parseXmlDocument(\DOMDocument $doc)
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
                    $entities = self::xmlParseAllEntities($child);
                    $result->setEntities($entities);
                    break;
            }
        }
        return $result;
    }

    /**
     * @param $filename
     * @return Base
     */
    public static function parseXmlFile($filename)
    {
        $doc = new \DOMDocument();
        $doc->load($filename);
        return self::parseXmlDocument($doc);
    }

    private static function xmlParseAllAttributes($node)
    {
        $result = array();
        /** @var  $children \DOMNodeList */
        $children = $node->childNodes;
        /** @var $child \DOMNode */
        foreach ($children as $child) {
            $localName = $child->localName;
            switch ($localName) {
                case Attribute::NAME:
                    $attribute                      = self::xmlParseAttribute($child);
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
    private static function xmlParseAllEntities($node)
    {
        $result = array();
        /** @var  $children \DOMNodeList */
        $children = $node->childNodes;
        /** @var $child \DOMNode */
        foreach ($children as $child) {
            $localName = $child->localName;
            switch ($localName) {
                case Entity::NAME:
                    $entity                      = self::xmlParseEntity($child);
                    $result[$entity->getAlias()] = $entity;
                    break;
            }
        }
        return $result;
    }

    private static function xmlParseAttribute(\DOMElement $node)
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

    private static function xmlParseEntity($node)
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
                    $attributes = self::xmlParseAllAttributes($child);
                    $result->setAttributes($attributes);
                    break;
                case Entity::XML_NOTES:
                    $val = $child->nodeValue;
                    $result->setNotes($val);
                    break;
            }
        }
        return $result;
    }
}