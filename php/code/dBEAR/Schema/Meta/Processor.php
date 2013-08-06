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