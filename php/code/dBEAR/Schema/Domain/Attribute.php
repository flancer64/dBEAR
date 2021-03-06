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

/**
 * @Entity
 * @Table(name="_a")
 */
class Attribute
{
    const NAME         = 'attribute';
    /** TODO: move XML related constants to XML Handlers */
    const XML_ALIAS    = 'alias';
    const XML_NAME     = 'name';
    const XML_NOTES    = 'notes';
    const XML_REQUIRED = 'required';
    const XML_TEMPORAL = 'temporal';
    const XML_TYPE     = 'type';
    /** @Id @Column(type="string", length=8) */
    private $alias;
    /** @Id @Column(type="string", length=8)
     * @ManyToOne(targetEntity="Entity", inversedBy="attributes")
     * @JoinColumn(name="entity", referencedColumnName="alias")
     */
    private $entity;
    /** @Column(type="boolean", name="required") */
    private $isRequired = false;
    /** @Column(type="boolean", name="temporal") */
    private $isTemporal = false;
    /** @Column(type="string", length=64) */
    private $name;
    /** @Column(type="string") */
    private $notes;
    /** @Column(type="string") */
    private $type;

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias)
    {
        $this->alias = strtolower(trim($alias));
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param mixed $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->isRequired;
    }

    /**
     * @return boolean
     */
    public function isTemporal()
    {
        return $this->isTemporal;
    }

    /**
     * @param boolean $isRequired
     */
    public function setIsRequired($isRequired)
    {
        $this->isRequired = filter_var($isRequired, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param boolean $isTemporal
     */
    public function setIsTemporal($isTemporal)
    {
        $this->isTemporal = filter_var($isTemporal, FILTER_VALIDATE_BOOLEAN);
    }


}