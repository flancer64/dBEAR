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
use dBEAR\Bear;
use Doctrine\Common\ClassLoader;

/**
 * User: Alex Gusev <flancer64@gmail.com>
 */

require '../lib/Doctrine/Common/ClassLoader.php';

$classLoader = new ClassLoader('Doctrine', '../lib');
$classLoader->register();

$classLoader2 = new ClassLoader('dBEAR', '.');
$classLoader2->register();

$config = new \Doctrine\DBAL\Configuration();
//..
$connectionParams = array(
    'dbname'   => 'dbear01',
    'user'     => 'root',
    'password' => 'MaryRoot',
    'host'     => 'localhost',
    'driver'   => 'pdo_mysql',
);

$conn      = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
$schemaMan = $conn->getSchemaManager();
//Bear::metaTablesDrop($schemaMan);
//Bear::metaTablesCreate($schemaMan);

Bear::schemaLoad('C:\work\projects\dBEAR\dbear.ws\php\code\dBEAR.xml');

