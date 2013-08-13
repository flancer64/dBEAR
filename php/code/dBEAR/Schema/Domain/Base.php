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
 * User: Alex Gusev <flancer64@gmail.com>
 */

namespace dBEAR\Schema\Domain;


use dBEAR\Schema\Domain\Entity;

/**
 * @Entity
 * @Table(name="_b")
 */
class Base
{
    const XML_ENTITIES = 'entities';
    const XML_VERSION  = 'version';
    /** @var \dBEAR\Schema\Domain\Entity[] */
    private $entities = array();
    /** @Id @Column(type="integer") */
    private $version;
    /** @Column(type="text") */
    private $xmlSchema;

    /**
     * @return array
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @param array $entities
     */
    public function setEntities($entities)
    {
        $this->entities = $entities;
    }

    /**
     * @param $alias
     * @return Entity
     */
    public function getEntity($alias)
    {
        $result = isset($this->entities[$alias]) ? $this->entities[$alias] : null;
        return $result;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getXmlSchema()
    {
        return $this->xmlSchema;
    }

    /**
     * @param mixed $schema
     */
    public function setXmlSchema($schema)
    {
        $this->xmlSchema = $schema;
    }
}