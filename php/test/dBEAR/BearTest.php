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

use dBEAR\TestUnit;

require_once('./TestUnit.php');
class BearTest extends TestUnit
{


    public function test_dbInitData()
    {
        /** Customer data */
        for ($i = 1; $i <= 100; $i++) {
            self::getDbConnection()->exec("insert into e_cust (id) VALUES ($i)");
            self::getDbConnection()->exec("insert into a_cust_email (`entity_id`, `value`) VALUES ($i, 'email_$i')");
            self::getDbConnection()->exec("insert into a_cust_nfirst (`entity_id`, `value`) VALUES ($i, 'nfirst_$i')");
            self::getDbConnection()->exec("insert into a_cust_nlast (`entity_id`, `value`) VALUES ($i, 'nlast_$i')");
        }
        /** Address data */
        for ($i = 1; $i <= 100; $i++) {
            self::getDbConnection()->exec("insert into e_addr (id) VALUES ($i)");
            self::getDbConnection()->exec("insert into a_addr_country (`entity_id`, `value`) VALUES ($i, 'country_$i')");
            self::getDbConnection()->exec("insert into a_addr_zip (`entity_id`, `value`) VALUES ($i, 'zip_$i')");
        }
    }

    public function test_tmp()
    {
        $conn = $this->getDbConnection();
        $stmt = $conn->query("select * from e_cust_2");
        while ($row = $stmt->fetch()) {
            print_r($row);
        }
    }
}