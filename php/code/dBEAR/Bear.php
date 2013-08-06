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

namespace dBEAR;

use dBEAR\Exception\BearException;
use dBEAR\Schema\Meta\Processor;
use dBEAR\Schema\Meta\TableA;
use dBEAR\Schema\Meta\TableB;
use dBEAR\Schema\Meta\TableE;
use dBEAR\Xml\Parser;
use Doctrine\DBAL\Schema\AbstractSchemaManager;

class Bear
{
    const META_ALIAS_LENGTH = 8;
    const META_NAME_LENGTH  = 64;
    /** @var  \dBEAR\Schema\Base */
    private $base;
    /** @var  \Doctrine\DBAL\Schema\AbstractSchemaManager */
    private $schemaMan;

    /**
     * Create META tables in the appropriate order.
     * @param AbstractSchemaManager $schemaMan
     */
    public static function metaTablesCreate(AbstractSchemaManager $schemaMan)
    {
        $schemaMan->createTable(TableB::generate());
        $schemaMan->createTable(TableE::generate());
        $schemaMan->createTable(TableA::generate());
    }

    /**
     * Drop META tables in the appropriate order.
     * @param AbstractSchemaManager $schemaMan
     */
    public static function metaTablesDrop(AbstractSchemaManager $schemaMan)
    {
        // TODO: move metaTablesDropCreate to private methods
        $tables = $schemaMan->listTableNames();
        $tables = array_flip($tables);
        if (isset($tables[TableA::getName()])) $schemaMan->dropTable(TableA::getName());
        if (isset($tables[TableE::getName()])) $schemaMan->dropTable(TableE::getName());
        if (isset($tables[TableB::getName()])) $schemaMan->dropTable(TableB::getName());
    }

    /**
     * @return \dBEAR\Schema\Base
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @param \dBEAR\Schema\Base $base
     */
    public function setBase($base)
    {
        $this->base = $base;
    }

    public function metaUpdateStructure()
    {
        if (is_null($this->schemaMan)) {
            $processor = new Processor($this->schemaMan);
            if (!is_null($this->base)) {
                $processor->bubuBase($this->base);
            } else {
                throw new BearException('Base structure is not initiated.', BearException::ERR_BASE_IS_NULL);
            }
        } else {
            throw new BearException('Doctrine schema manager is not initiated.', BearException::ERR_BASE_IS_NULL);
        }
    }

    public function schemaLoad($xmlFile)
    {
        /** @var  $base \dBEAR\Schema\Base */
        $this->base = Parser::parseXmlFile($xmlFile);
    }
}