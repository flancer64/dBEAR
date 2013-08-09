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
    private $tblAttributes;
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
        $this->repoAttr      = $this->entityManager->getRepository('\dBEAR\Schema\Domain\Attribute');
        $this->repoBase      = $this->entityManager->getRepository('\dBEAR\Schema\Domain\Base');
        $this->repoEntity    = $this->entityManager->getRepository('\dBEAR\Schema\Domain\Entity');
        //$this->repoRelation= $this->entityManager->getRepository('\dBEAR\Schema\Domain\Relation');
        /** Load META data. */
        /** TODO: create associative array to search entries by alias */
        $this->metaAttr   = $this->repoAttr->findAll();
        $this->metaBase   = $this->repoBase->findAll();
        $this->metaEntity = $this->repoEntity->findAll();
        /** load current schema */
        $tables             = $this->schemaMan->listTableNames();
        $this->schemaTables = array_flip($tables);
//        $views              = $this->schemaMan->listViews();
//        $this->schemaViews  = array_flip($views);
    }

    public function addBaseVersion(Base $base)
    {
        /** TODO: transaction should be used here */
        foreach ($base->getEntities() as $oneEntity) {
            /** @var $oneEntity Entity */
            $tblEntityRegistry = $this->generateEntityRegistry($oneEntity);
            if (!isset($this->metaEntity[$oneEntity->getAlias()])) {
                /** add new entity register to DB */
                try {
                    $this->schemaMan->createTable($tblEntityRegistry);
                } catch (\Exception $e) {
                    echo $e->getMessage() . "\n";
                }
            }
            $this->tblEntityRegistries[$tblEntityRegistry->getName()] = $tblEntityRegistry;
            /** Generate Attributes for the Entity */
            foreach ($oneEntity->getAttributes() as $oneAttr) {
                /** @var $oneAttr Attribute */
                $tblAttrRegistry = $this->generateAttributeRegistry($oneAttr);
                if (!isset($this->metaAttr[$oneAttr->getAlias()])) {
                    /** add new attribute register to DB */
                    try {
                        $this->schemaMan->createTable($tblAttrRegistry);
                    } catch (\Exception $e) {
                        echo $e->getMessage() . "\n";
                    }
                }
                $this->tblAttributes[$tblAttrRegistry->getName()] = $tblAttrRegistry;
                /** create views for temporal attributes */
                if ($oneAttr->isTemporal()) {
                    $viewAttrTs  = $this->generateAttributeViewTs($oneAttr);
                    $viewAttrAct = $this->generateAttributeViewAct($oneAttr);
                }
            }
            $q = 2;
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
        $this->schemaMan->createView($result);
        return $result;
    }

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
        $this->schemaMan->createView($result);
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
}