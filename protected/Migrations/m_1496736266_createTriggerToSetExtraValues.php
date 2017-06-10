<?php

namespace App\Migrations;

use T4\Orm\Migration;

class m_1496736266_createTriggerToSetExtraValues
    extends Migration
{

    public function up()
    {
        $query = <<<LABEL
            CREATE TRIGGER `set_values` BEFORE UPDATE ON `__users_extra` FOR EACH ROW 
            BEGIN 
            SET NEW.`debtTotal` = NEW.`debtPlus` - NEW.`debtMinus`, 
            NEW.`balance` = NEW.`startSum` + NEW.`profit` - NEW.`costs` + NEW.`debtTotal`; 
            END;
LABEL;
        $this->db->execute($query);
    }

    public function down()
    {
        $this->db->execute('DROP TRIGGER `set_values`');
    }
    
}