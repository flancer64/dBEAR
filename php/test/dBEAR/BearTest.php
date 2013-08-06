<?php
/**
 * Copyright (c) 2013, Praxigento
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

use dBEAR\Bear;
use dBEAR\Exception\BearException;
use dBEAR\TestUnit;

require_once('./TestUnit.php');
class BearTest extends TestUnit
{
    public function test_metaTablesDropCreate()
    {
        Bear::metaTablesDrop(self::_getDbSchemaManager());
        Bear::metaTablesCreate(self::_getDbSchemaManager());
    }

    public function test_metaUpdateStructure()
    {
        $bear = new Bear();
        try {
            $bear->metaUpdateStructure();
        } catch (BearException $e) {
            $this->assertEquals(BearException::ERR_BASE_IS_NULL, $e->getCode());
        }
        $bear->schemaLoad(self::getXmlSchemaFile());
        $this->assertNotNull($bear->getBase());
        $bear->metaUpdateStructure();
    }

    public function test_schemaLoad()
    {
        $bear = new Bear();
        $bear->schemaLoad(self::getXmlSchemaFile());
        $this->assertNotNull($bear->getBase());
    }
}