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

namespace dBEAR;


use Doctrine\Common\ClassLoader;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class TestUnit extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private static $_dbConnection;
    /**
     * @var  \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    private static $_dbSchemaManager;

    function __construct()
    {
        $path = self::getProjectRootFolder();
        require_once($path . 'lib/Doctrine/Common/ClassLoader.php');
        $clCode = new ClassLoader('dBEAR', $path . 'code');
        $clCode->register();
        $clLib = new ClassLoader('Doctrine', $path . 'lib');
        $clLib->register();
        $clTest = new ClassLoader('dBEAR', $path . 'test');
        $clTest->register();
    }

    public static function  getXmlSchemaFile()
    {
        $result = self::getProjectRootFolder() . 'etc/dBEAR.xml';
        return $result;
    }

    private static function getProjectRootFolder()
    {
        $result = str_replace('test' . DIRECTORY_SEPARATOR . 'dBEAR', '', __DIR__);
        return $result;
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected
    function _getDbConnection()
    {
        if (is_null(self::$_dbConnection)) {
            $config              = new Configuration();
            $connectionParams    = array(
                'dbname'   => 'dbear01',
                'user'     => 'root',
                'password' => 'MaryRoot',
                'host'     => 'localhost',
                'driver'   => 'pdo_mysql',
            );
            self::$_dbConnection = DriverManager::getConnection($connectionParams, $config);
        }
        return self::$_dbConnection;
    }

    /**
     * @return \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    protected
    function _getDbSchemaManager()
    {
        if (is_null(self::$_dbSchemaManager)) {
            self::$_dbSchemaManager = $this->_getDbConnection()->getSchemaManager();
        }
        return self::$_dbSchemaManager;

    }
}