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
 * Analyze Base structure, save it to META tables, created tables and views for the structure.
 * User: Alex Gusev <flancer64@gmail.com>
 */

namespace dBEAR\Schema\Meta;

use dBEAR\Schema\Domain\Attribute;
use dBEAR\Schema\Domain\Base;
use dBEAR\Schema\Domain\Entity;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\View;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Setup;

class Processor
{
    /** @var  Connection */
    private $connection;
    /** @var  Base */
    private $currentBase;
    /** @var  EntityManager */
    private $entityManager;
    /** @var array */
    private $metaAttr;
    /** @var array */
    private $metaBase;
    /** @var array */
    private $metaEntity;
    /** @var  EntityRepository */
    private $repoAttr;
    /** @var  EntityRepository */
    private $repoBase;
    /** @var  EntityRepository */
    private $repoEntity;
    /** @var  EntityRepository */
    private $repoRelation;
    /** @var AbstractSchemaManager */
    private $schemaMan;
    /** @var array */
    private $schemaTables;
    /** @var array */
    private $schemaViews;
    private $tblAttributeRegistries;
    private $tblEntityRegistries;

    function __construct(Connection $connection)
    {
        $this->connection    = $connection;
        $this->schemaMan     = $connection->getSchemaManager();
        $isDevMode           = true;
        $ds                  = DIRECTORY_SEPARATOR;
        $search              = $ds . 'dBEAR' . $ds . 'Schema' . $ds . 'Meta';
        $replace             = $ds . 'dBEAR' . $ds . 'Schema' . $ds . 'Domain';
        $path                = str_replace($search, $replace, __DIR__);
        $config              = Setup::createAnnotationMetadataConfiguration(array($path), $isDevMode);
        $this->entityManager = EntityManager::create($this->connection, $config);
        $this->initLoadMeta();
        /** load current schema */
        $tables             = $this->schemaMan->listTableNames();
        $this->schemaTables = array_flip($tables);
//        $views              = $this->schemaMan->listViews();
//        $this->schemaViews  = array_flip($views);
    }

    public function addBaseVersion(Base $base)
    {
        /** clear registries */
        $this->tblAttributeRegistries = array();
        $this->tblEntityRegistries    = array();
        $this->currentBase            = $base;
        /** check version */
        $existBase = $this->repoBase->find($base->getVersion());
        if (is_null($existBase)) {
            /** wrap all activity into transaction */
            $this->entityManager->getConnection()->beginTransaction();
            try {
                foreach ($this->currentBase->getEntities() as $oneEntity) {
                    /** @var $oneEntity Entity */
                    $this->addBaseEntity($oneEntity);
                }
                /** registry new version */
                $metaBase = new Base();
                $metaBase->setVersion($this->currentBase->getVersion());
                $metaBase->setXmlSchema('schema');
                $this->entityManager->persist($metaBase);
                $this->entityManager->flush();
                $this->entityManager->getConnection()->commit();
            } catch (\Exception $e) {
                echo $e->getMessage();
                $this->entityManager->getConnection()->rollBack();
            }
        }
    }

    private function addBaseEntity(Entity $entity)
    {
        $tblEntityRegistry = $this->generateEntityRegistry($entity);
        if (!isset($this->metaEntity[$entity->getAlias()])) {
            /** add new entity register to DB */
            $this->schemaMan->createTable($tblEntityRegistry);
            /** add new Entity to META */
            $metaEntity = new Entity();
            $metaEntity->setAlias($entity->getAlias());
            $metaEntity->setNotes($entity->getNotes());
            $this->entityManager->persist($metaEntity);
        }
        $this->tblEntityRegistries[$tblEntityRegistry->getName()] = $tblEntityRegistry;
        /** Generate Attributes for the Entity */
        foreach ($entity->getAttributes() as $oneAttr) {
            $this->addBaseEntityAttribute($oneAttr);
        }
        /** Generate view for the last version of the entity */
        $viewEntityLast = $this->generateEntityActual($entity);
        $this->schemaMan->dropAndCreateView($viewEntityLast);
    }

    private function addBaseEntityAttribute(Attribute $attr)
    {
        /** @var $attr Attribute */
        $tblAttrRegistry = $this->generateAttributeRegistry($attr);
        if (!isset($this->metaAttr[$attr->getAlias()])) {
            /** add new attribute register to DB */
            $this->schemaMan->createTable($tblAttrRegistry);
            /** add new Attribute to META */
            $metaAttr = new Attribute();
            $metaAttr->setAlias($attr->getAlias());
            /** @var  $metaEntity Entity */
            $metaEntity = $this->repoEntity->find($attr->getEntity());
            $metaAttr->setEntity($metaEntity->getAlias());
            $metaAttr->setIsRequired($attr->isRequired());
            $metaAttr->setIsTemporal($attr->isTemporal());
            $metaAttr->setName($attr->getName());
            $metaAttr->setType($attr->getType());
            $this->entityManager->persist($metaAttr);
        }
        $this->tblAttributeRegistries[$tblAttrRegistry->getName()] = $tblAttrRegistry;
        /** create views for temporal attributes */
        if ($attr->isTemporal()) {
            /** skip temporal attrs */
//                    $viewAttrTs = $this->generateAttributeViewTs($oneAttr);
//                    $this->schemaMan->dropAndCreateView($viewAttrTs);
//                    $viewAttrAct = $this->generateAttributeViewAct($oneAttr);
//                    $this->schemaMan->dropAndCreateView($viewAttrAct);
        }
    }

    private function generateAttributeRegistry(Attribute $attribute)
    {
        $name = TableA::getRegistryName($attribute->getAlias(), $attribute->getEntity());
        /** @var  $result Table */
        $result = new Table($name);
        $result->addColumn(TableA::getRegistryColEntity(), Type::INTEGER, array('unsigned' => true));
        $result->addColumn(TableA::getRegistryColValue(), Type::STRING, array());
        if ($attribute->isTemporal()) {
            $result->addColumn(TableA::getRegistryColUpdated(), Type::DATETIME);
            $result->setPrimaryKey(array(TableA::getRegistryColEntity(), TableA::getRegistryColUpdated()));
        } else {
            $result->setPrimaryKey(array(TableA::getRegistryColEntity()));
        }
        /** add constraints */
        $tblForeign = $this->tblEntityRegistries[TableE::getRegistryName($attribute->getEntity())];
        $result->addForeignKeyConstraint($tblForeign, array(TableA::getRegistryColEntity()), array(TableE::getRegistryColId()));
        return $result;
    }

    private function generateAttributeViewAct(Attribute $attribute)
    {
        $viewName        = TableA::getTemporalActName($attribute->getAlias(), $attribute->getEntity());
        $tblEntity       = TableE::getRegistryName($attribute->getEntity());
        $tblAttr         = TableA::getRegistryName($attribute->getAlias(), $attribute->getEntity());
        $colEntityId     = TableE::getRegistryColId();
        $colAttrEntityId = TableA::getRegistryColEntity();
        $colAttrUpdated  = TableA::getRegistryColUpdated();
        /** compose SQL statement */
        $sql = "SELECT $tblEntity.$colEntityId,  MAX($tblAttr.$colAttrUpdated) AS $colAttrUpdated ";
        $sql .= "FROM $tblEntity LEFT JOIN $tblAttr ON $tblEntity.$colEntityId=$tblAttr.$colAttrEntityId ";
        $sql .= "GROUP BY `$tblEntity`.`$colEntityId`";
        $result = new View($viewName, $sql);
        return $result;
    }

    /**
     * @param Attribute $attribute
     * @return View
     */
    private function generateAttributeViewTs(Attribute $attribute)
    {
        $viewName        = TableA::getTemporalTsName($attribute->getAlias(), $attribute->getEntity());
        $tblAttr         = TableA::getRegistryName($attribute->getAlias(), $attribute->getEntity());
        $colAttrEntityId = TableA::getRegistryColEntity();
        $colAttrUpdated  = TableA::getRegistryColUpdated();
        /** compose SQL statement */
        $sql = "SELECT $tblAttr.$colAttrEntityId,  MAX($tblAttr.$colAttrUpdated) AS $colAttrUpdated ";
        $sql .= "FROM $tblAttr ";
        $sql .= "GROUP BY `$tblAttr`.`$colAttrEntityId`";
        $result = new View($viewName, $sql);
        return $result;
    }

    private function generateEntityActual(Entity $entity)
    {
        $viewName    = TableE::getVersionViewName($entity->getAlias(), $this->currentBase->getVersion());
        $tblEntity   = TableE::getRegistryName($entity->getAlias());
        $colEntityId = TableE::getRegistryColId();
        /** compose SQL statement */
        $cols  = "$tblEntity.$colEntityId  AS " . TableE::getRegistryColId();
        $joins = '';
        foreach ($entity->getAttributes() as $attr) {
            /** @var  $attr Attribute */
            $tblAttr       = TableA::getRegistryName($attr->getAlias(), $attr->getEntity());
            $colAttrEntity = TableA::getRegistryColEntity();
            $colAttrValue  = TableA::getRegistryColValue();
            $colAttrName   = $attr->getName();
            $cols .= ", $tblAttr.$colAttrValue AS $colAttrName";
            $joins .= "LEFT OUTER JOIN $tblAttr ON $tblEntity.$colEntityId=$tblAttr.$colAttrEntity ";
        }
        $sql    = "SELECT $cols FROM $tblEntity $joins";
        $result = new View($viewName, $sql);
        return $result;
    }

    private function generateEntityRegistry(Entity $entity)
    {
        $name = TableE::getRegistryName($entity->getAlias());
        /** @var  $result Table */
        $result = new Table($name);
        $result->addColumn(TableE::getRegistryColId(), Type::INTEGER, array('unsigned' => true));
        $result->setPrimaryKey(array(TableE::getRegistryColId()));
        return $result;
    }

    private function initLoadMeta()
    {
        $this->repoAttr   = $this->entityManager->getRepository('\dBEAR\Schema\Domain\Attribute');
        $this->repoBase   = $this->entityManager->getRepository('\dBEAR\Schema\Domain\Base');
        $this->repoEntity = $this->entityManager->getRepository('\dBEAR\Schema\Domain\Entity');
        //$this->repoRelation= $this->entityManager->getRepository('\dBEAR\Schema\Domain\Relation');
        /** Load Attributes META data */
        $metaAttr       = $this->repoAttr->findAll();
        $this->metaAttr = array();
        foreach ($metaAttr as $oneAttr) {
            /** @var $oneAttr Attribute */
            $this->metaAttr[$oneAttr->getAlias()] = $oneAttr;
        }
        /** Load Base META data */
        $metaBase       = $this->repoBase->findAll();
        $this->metaBase = array();
        foreach ($metaBase as $oneBase) {
            /** @var $oneBase Base */
            $this->metaBase[$oneBase->getVersion()] = $oneBase;
        }
        /** Load Entities META data */
        $metaEntity       = $this->repoEntity->findAll();
        $this->metaEntity = array();
        foreach ($metaEntity as $oneEntity) {
            /** @var $oneEntity Entity */
            $this->metaEntity[$oneEntity->getAlias()] = $oneEntity;
        }
    }
}