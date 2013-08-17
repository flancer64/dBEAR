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


use dBEAR\Schema\Meta\Data\VersionsMap;
use dBEAR\TestUnit;

require_once('../TestUnit.php');
class VersionsHandlerTest extends TestUnit
{
    public function test_asXml()
    {
        $versions = new VersionsMap();
        $versions->setSchemaVersion(13);
        $versions->addEntity('cust', 1);
        $versions->addEntity('addr', 2);
        $versions->addEntity('order', '3');
        $versions->addEntity('payment', 4);
        $versions->addRelation('cust_address', 5);
        $xmlHandler = new VersionsHandler();
        $xml        = $xmlHandler->asXml($versions);
        $this->assertTrue(strlen($xml) > 32);


    }

    public function test_parseXmlFile()
    {
        $file       = self::getXmlVersionsFile();
        $xmlHandler = new VersionsHandler();
        $versions   = $xmlHandler->parseXmlFile($file);
        $this->assertNotNull($versions);
        $this->assertNotNull($versions->getSchemaVersion());
        $this->assertTrue(is_array($versions->getEntities()));
    }

    public function test_parseXmlText()
    {
        $file       = self::getXmlVersionsFile();
        $text       = file_get_contents($file);
        $xmlHandler = new VersionsHandler();
        $versions   = $xmlHandler->parseXmlText($text);
        $this->assertNotNull($versions);
        $this->assertNotNull($versions->getSchemaVersion());
        $this->assertTrue(is_array($versions->getEntities()));
    }
}