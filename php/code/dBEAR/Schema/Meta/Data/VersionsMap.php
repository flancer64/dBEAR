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
 * User: Alex Gusev <flancer64@gmail.com>
 */

namespace dBEAR\Schema\Meta\Data;


class VersionsMap
{
    /** TODO: move XML related constants to XML Handlers */
    const XML_ENTITIES       = 'entities';
    const XML_RELATIONS      = 'relations';
    const XML_ROOT           = 'versions';
    const XML_SCHEMA_VERSION = 'schemaVersion';
    /** @var VersionsMapEntry[] */
    private $entities = array();
    /** @var VersionsMapEntry[] */
    private $relations = array();
    private $schemaVersion;

    public function  addEntity($alias, $version)
    {
        $entry                              = new VersionsMapEntry ($alias, $version);
        $this->entities[$entry->getAlias()] = $entry;
    }

    public function  addRelation($alias, $version)
    {
        $entry                               = new VersionsMapEntry ($alias, $version);
        $this->relations[$entry->getAlias()] = $entry;
    }

    /**
     * @return array
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @param \dBEAR\Schema\Meta\Data\VersionsMapEntry[] $entities
     */
    public function setEntities($entities)
    {
        $this->entities = $entities;
    }

    public function getEntityVersion($alias)
    {
        $result = null;
        if (isset($this->entities[$alias])) {
            /** @var  $entry VersionsMapEntry */
            $entry  = $this->entities[$alias];
            $result = $entry->getVersion();
        }
        return $result;
    }

    public function getRelationVersion($alias)
    {
        $result = null;
        if (isset($this->relations[$alias])) {
            /** @var  $entry VersionsMapEntry */
            $entry  = $this->relations[$alias];
            $result = $entry->getVersion();
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * @param \dBEAR\Schema\Meta\Data\VersionsMapEntry[] $relations
     */
    public function setRelations($relations)
    {
        $this->relations = $relations;
    }

    /**
     * @return mixed
     */
    public function getSchemaVersion()
    {
        return $this->schemaVersion;
    }

    /**
     * @param mixed $version
     */
    public function setSchemaVersion($version)
    {
        $this->schemaVersion = $version;
    }
}