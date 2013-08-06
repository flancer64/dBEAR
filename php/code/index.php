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

