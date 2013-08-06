<?php
/**
 * Copyright (c) 2013, F. Lancer, SIA
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the
 * following conditions are met:
 *  - Redistributions of source code must retain the above copyright notice, this list of conditions and the following
 *      disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the
 *      following disclaimer in the documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
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