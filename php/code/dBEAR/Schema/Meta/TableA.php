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
 * Table to store attributes META data.
 * User: Alex Gusev <flancer64@gmail.com>
 */

namespace dBEAR\Schema\Meta;


use dBear\Bear;
use dBEAR\Schema\Domain\Attribute;
use dBEAR\Schema\Domain\Entity;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;

class TableA
{
    const COL_ALIAS       = Attribute::XML_ALIAS;
    const COL_ENTITY      = Entity::NAME;
    const COL_IS_REQUIRED = Attribute::XML_REQUIRED;
    const COL_IS_TEMPORAL = Attribute::XML_TEMPORAL;
    const COL_NAME        = Attribute::XML_NAME;
    const COL_TYPE        = Attribute::XML_TYPE;
    const NAME            = '_a';

    public static function generate()
    {
        $result = new Table(self::NAME);
        $result->addColumn(self::COL_ENTITY, Type::STRING, array('length' => Bear::META_ALIAS_LENGTH, 'comment' => 'Entity alias.'));
        $result->addColumn(self::COL_ALIAS, Type::STRING, array('length' => Bear::META_ALIAS_LENGTH, 'comment' => 'Alias to be used in the tables and views names.'));
        $result->addColumn(self::COL_NAME, Type::STRING, array('length' => Bear::META_NAME_LENGTH, 'comment' => 'This value is used to name columns in views.'));
        $result->addColumn(self::COL_IS_TEMPORAL, Type::BOOLEAN, array('comment' => 'Is this attribute temporal (need save all updates)?'));
        $result->addColumn(self::COL_IS_REQUIRED, Type::BOOLEAN, array('comment' => 'Is this attribute should contain value for all instances of the entity (is not nullable)?'));
        $result->addColumn(self::COL_TYPE, Type::STRING, array('comment' => 'SQL data type to store attribute values.'));
        $result->setPrimaryKey(array(self::COL_ENTITY, self::COL_ALIAS));
        /** setup foreign keys */
        $tableE = TableE::generate();
        $result->addForeignKeyConstraint($tableE, array(self::COL_ENTITY), array(TableE::COL_ALIAS));
        return $result;
    }

    public static function getName()
    {
        return self::NAME;
    }
}