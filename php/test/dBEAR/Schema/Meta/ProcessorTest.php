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

namespace dBEAR\Xml;


use dBEAR\Schema\Meta\Processor;
use dBEAR\TestUnit;

require_once('../../TestUnit.php');
class ProcessorTest extends TestUnit
{

    public function test_addSchemaVersionFromXml()
    {
        $this->doDbRecreate();
        $file          = self::getXmlSchemaFileV1();
        $xml           = file_get_contents($file);
        $metaProcessor = new Processor(self::getDbConnection($renew = true));
        $metaProcessor->addSchemaVersionFromXml($xml);
    }

    public function test_addSchemaVersionsOneByOne()
    {
        $this->doDbRecreate();
        /** create schema V1 */
        $filename      = self::getXmlSchemaFileV1();
        $metaProcessor = new Processor(self::getDbConnection($renew = true));
        $metaProcessor->addSchemaVersionFromFile($filename);
        /** create schema V2 */
        $filename      = self::getXmlSchemaFileV2();
        $metaProcessor = new Processor(self::getDbConnection($renew = true));
        $metaProcessor->addSchemaVersionFromFile($filename);
        /** create schema V3 */
        $filename      = self::getXmlSchemaFileV3();
        $metaProcessor = new Processor(self::getDbConnection($renew = true));
        $metaProcessor->addSchemaVersionFromFile($filename);
    }

    private function doDbRecreate()
    {
        self::getDbConnection()->exec('drop database ' . self::TEST_DB);
        self::getDbConnection()->exec('CREATE DATABASE ' . self::TEST_DB . ' character set utf8 COLLATE utf8_general_ci');
    }
}