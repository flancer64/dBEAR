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