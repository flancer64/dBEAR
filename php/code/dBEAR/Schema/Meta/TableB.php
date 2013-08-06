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
 * Table to store database itself META data.
 * User: Alex Gusev <flancer64@gmail.com>
 */

namespace dBEAR\Schema\Meta;


use dBEAR\Schema\Base;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;

class TableB
{
    const COL_ID     = Base::XML_VERSION;
    const COL_SCHEMA = 'schema';
    const NAME       = '_b';

    public static function generate()
    {
        $result = new Table(self::NAME);
        $result->addColumn(self::COL_ID, Type::INTEGER, array("unsigned" => true, 'comment' => 'Version of the dBEAR structure (ID).'));
        $result->addColumn(self::COL_SCHEMA, Type::TEXT, array('comment' => 'XML schema of the appropriate version of the dBEAR structure.'));
        $result->setPrimaryKey(array(self::COL_ID));
        return $result;
    }

    public static function getName()
    {
        return self::NAME;
    }
}