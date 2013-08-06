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