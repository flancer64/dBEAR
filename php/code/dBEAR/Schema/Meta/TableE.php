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
 * Table to store entities META data.
 * User: Alex Gusev <flancer64@gmail.com>
 */

namespace dBEAR\Schema\Meta;


use dBEAR\Bear;
use dBEAR\Schema\Domain\Entity;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;

class TableE
{
    const COL_ALIAS = Entity::XML_ALIAS;
    const COL_NOTES = Entity::XML_NOTES;

    public static function generate()
    {
        $result = new Table(self::getName());
        $result->addColumn(self::COL_ALIAS, Type::STRING, array('length' => Bear::META_ALIAS_LENGTH, 'comment' => 'Alias to be used in the tables and views names.'));
        $result->addColumn(self::COL_NOTES, Type::STRING, array('comment' => 'Human related description of the entity.'));
        $result->setPrimaryKey(array(self::COL_ALIAS));
        return $result;
    }

    public static function getActualName($alias)
    {
        return self::getRegistryPrefix() . $alias . '_act';
    }

    public static function getName()
    {
        return '_e';
    }

    public static function getRegistryColId()
    {
        return 'id';
    }

    public static function getRegistryName($alias)
    {
        return self::getRegistryPrefix() . $alias;
    }

    public static function getRegistryPrefix()
    {
        return 'e_';
    }
}