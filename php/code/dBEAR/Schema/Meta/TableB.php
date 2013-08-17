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
 * Table to store database itself META data.
 * User: Alex Gusev <flancer64@gmail.com>
 */

namespace dBEAR\Schema\Meta;


use dBEAR\Schema\Domain\Base;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;

class TableB
{
    const COL_ID       = Base::XML_VERSION;
    const COL_SCHEMA   = 'xmlSchema';
    const COL_VERSIONS = 'xmlVersions';
    const NAME         = '_b';

    public static function generate()
    {
        $result = new Table(self::NAME);
        $result->addColumn(self::COL_ID, Type::INTEGER, array("unsigned" => true, 'comment' => 'Version of the dBEAR structure (ID).'));
        $result->addColumn(self::COL_SCHEMA, Type::TEXT, array('comment' => 'XML schema of the appropriate version of the dBEAR structure.'));
        $result->addColumn(self::COL_VERSIONS, Type::TEXT, array('comment' => 'VersionsMap of the entities and relations to be used in the current schema.'));
        $result->setPrimaryKey(array(self::COL_ID));
        return $result;
    }

    public static function getName()
    {
        return self::NAME;
    }
}