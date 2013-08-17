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


use dBEAR\Schema\Meta\Data\VersionsMap;
use dBEAR\Schema\Meta\Data\VersionsMapEntry;

class VersionsHandler
{
    public function asXml(VersionsMap $versions)
    {
        $dom  = new \DOMDocument();
        $root = $dom->createElement(VersionsMap::XML_ROOT);
        $dom->appendChild($root);
        /** 'schemaVersion' attribute */
        $attrSchemaVersion = $dom->createAttribute(VersionsMap::XML_SCHEMA_VERSION);
        $attrValue         = $dom->createTextNode($versions->getSchemaVersion());
        $attrSchemaVersion->appendChild($attrValue);
        $root->appendChild($attrSchemaVersion);
        /**
         * Add entities.
         */
        $entities = $dom->createElement(VersionsMap::XML_ENTITIES);
        $root->appendChild($entities);
        foreach ($versions->getEntities() as $oneEntity) {
            /** @var $oneEntity VersionsMapEntry */
            $entry = $this->generateEntry($oneEntity, $dom);
            $entities->appendChild($entry);
        }
        /**
         * Add relations.
         */
        $relations = $dom->createElement(VersionsMap::XML_RELATIONS);
        $root->appendChild($relations);
        foreach ($versions->getRelations() as $oneRelation) {
            /** @var $oneRelation VersionsMapEntry */
            $entry = $this->generateEntry($oneRelation, $dom);
            $relations->appendChild($entry);
        }
        //
        return $dom->saveXML();
    }

    /**
     * @param \DOMDocument $doc
     * @return VersionsMap
     */
    public function parseXmlDocument(\DOMDocument $doc)
    {
        $result = new VersionsMap();
        /** root node */
        $root = $doc->documentElement;
        /** parse node's attributes */
        $version = $root->getAttribute(VersionsMap::XML_SCHEMA_VERSION);
        $result->setSchemaVersion($version);
        /** @var  $children \DOMNodeList */
        $children = $root->childNodes;
        /** @var $child \DOMNode */
        foreach ($children as $child) {
            $localName = $child->localName;
            switch ($localName) {
                case VersionsMap::XML_ENTITIES:
                    $entities = $this->xmlParseAllEntries($child);
                    $result->setEntities($entities);
                    break;
                case VersionsMap::XML_RELATIONS:
                    $relations = $this->xmlParseAllEntries($child);
                    $result->setRelations($relations);
                    break;
            }
        }
        return $result;
    }

    /**
     * @param $filename
     * @return VersionsMap
     */
    public function parseXmlFile($filename)
    {
        $doc = new \DOMDocument();
        $doc->load($filename);
        return $this->parseXmlDocument($doc);
    }

    /**
     * @param $text
     * @return VersionsMap
     */
    public function parseXmlText($text)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($text);
        return $this->parseXmlDocument($doc);
    }

    private function generateEntry(VersionsMapEntry $entryA, \DOMDocument $dom)
    {
        $result = $dom->createElement(VersionsMapEntry::XML_ENTRY);
        /** 'alias' attribute */
        $attrAlias      = $dom->createAttribute(VersionsMapEntry::XML_ALIAS);
        $attrAliasValue = $dom->createTextNode($entryA->getAlias());
        $attrAlias->appendChild($attrAliasValue);
        $result->appendChild($attrAlias);
        /** 'version' attribute */
        $attrVersion      = $dom->createAttribute(VersionsMapEntry::XML_VERSION);
        $attrVersionValue = $dom->createTextNode($entryA->getVersion());
        $attrVersion->appendChild($attrVersionValue);
        $result->appendChild($attrVersion);
        return $result;
    }

    /**
     * @param $node
     * @return array
     */
    private function xmlParseAllEntries($node)
    {
        $result = array();
        /** @var  $children \DOMNodeList */
        $children = $node->childNodes;
        /** @var $child \DOMNode */
        foreach ($children as $child) {
            $localName = $child->localName;
            switch ($localName) {
                case VersionsMapEntry::XML_ENTRY:
                    /** @var  $entry VersionsMapEntry */
                    $entry                      = $this->xmlParseEntry($child);
                    $result[$entry->getAlias()] = $entry;
                    break;
            }
        }
        return $result;
    }

    private function xmlParseEntry(\DOMElement $node)
    {
        /** parse node's attributes */
        $alias   = $node->getAttribute(VersionsMapEntry::XML_ALIAS);
        $version = $node->getAttribute(VersionsMapEntry::XML_VERSION);
        $result  = new VersionsMapEntry($alias, $version);
        return $result;
    }
}