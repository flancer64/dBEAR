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
 * Analyze Base structure, save it to META tables, created tables and views for the structure.
 * User: Alex Gusev <flancer64@gmail.com>
 */

namespace dBEAR\Schema\Meta;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
class Processor
{
    /** @var  AbstractSchemaManager */
    private $schemaMan;

    function __construct()
    {

    }

    public function bubuBase($base)
    {
        // obtaining the entity manager
        $isDevMode = true;
        $config = Setup::createAnnotationMetadataConfiguration(array("C:/work/projects/dBEAR/dbear.ws/php/code/dBEAR/Schema"), $isDevMode);
        $connectionParams    = array(
            'dbname'   => 'dbear01',
            'user'     => 'root',
            'password' => 'MaryRoot',
            'host'     => 'localhost',
            'driver'   => 'pdo_mysql',
        );
        $conn = DriverManager::getConnection($connectionParams, $config);
        $entityManager = EntityManager::create($conn, $config);

        $productRepository = $entityManager->getRepository('\dBEAR\Schema\Entity');
        $products = $productRepository->findAll();
        1+1;
    }
}