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

use dBEAR\Bear;
use dBEAR\Exception\BearException;
use dBEAR\TestUnit;

require_once('./TestUnit.php');
class BearTest extends TestUnit
{
    public function test_dbDrop()
    {
        self::_getDbConnection()->exec('drop database dbear01');
        self::_getDbConnection()->exec('CREATE DATABASE `dbear01` character set utf8 COLLATE utf8_general_ci');
    }

    public function test_dbInitData()
    {
        for ($i = 1; $i <= 100; $i++) {
            self::_getDbConnection()->exec("insert into e_cust (id) VALUES ($i)");
            self::_getDbConnection()->exec("insert into a_cust_class (`entity_id`, `value`) VALUES ($i, 'class_$i')");
            self::_getDbConnection()->exec("insert into a_cust_email (`entity_id`, `value`) VALUES ($i, 'email_$i')");
            self::_getDbConnection()->exec("insert into a_cust_height (`entity_id`, `value`) VALUES ($i, 'height_$i')");
            self::_getDbConnection()->exec("insert into a_cust_nfirst (`entity_id`, `value`) VALUES ($i, 'nfirst_$i')");
            self::_getDbConnection()->exec("insert into a_cust_nlast (`entity_id`, `value`) VALUES ($i, 'nlast_$i')");
            self::_getDbConnection()->exec("insert into a_cust_phone (`entity_id`, `value`) VALUES ($i, 'phone_$i')");

        }
    }

    public function test_metaTablesDropCreate()
    {
        Bear::metaTablesDrop(self::_getDbSchemaManager());
        Bear::metaTablesCreate(self::_getDbSchemaManager());
    }

    public function test_metaUpdateStructure()
    {
        $bear = new Bear(self::_getDbConnection());
        try {
            $bear->metaUpdateStructure();
        } catch (BearException $e) {
            $this->assertEquals(BearException::ERR_BASE_IS_NULL, $e->getCode());
        }
        /** version 1 schema */
        $bear->schemaLoad(self::getXmlSchemaFile());
        $this->assertNotNull($bear->getBase());
        $bear->metaUpdateStructure();
        /** version 2 schema */
        $bear->schemaLoad(self::getXmlSchemaFileV2());
        $this->assertNotNull($bear->getBase());
        $bear->metaUpdateStructure();

    }

    public function test_schemaLoad()
    {
        $bear = new Bear(self::_getDbConnection());
        $bear->schemaLoad(self::getXmlSchemaFile());
        $this->assertNotNull($bear->getBase());
    }
}