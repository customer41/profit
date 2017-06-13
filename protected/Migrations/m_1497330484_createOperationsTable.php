<?php

namespace App\Migrations;

use T4\Orm\Migration;

class m_1497330484_createOperationsTable
    extends Migration
{

    public function up()
    {
        $this->createTable('operations', [
            'date' => ['type' => 'date'],
            'amount' => ['type' => 'int', 'length' => 'long'],
            '__category_id' => ['type' => 'link'],
            'comment' => ['type' => 'text'],
            '__user_id' => ['type' => 'link'],
        ]);
    }

    public function down()
    {
        $this->dropTable('operations');
    }
    
}