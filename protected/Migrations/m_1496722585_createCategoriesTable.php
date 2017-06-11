<?php

namespace App\Migrations;

use T4\Orm\Migration;

class m_1496722585_createCategoriesTable
    extends Migration
{

    public function up()
    {
        $this->createTable('categories', [
            'name' => ['type' => 'string', 'length' => 50],
            'notDeleted' => ['type' => 'bool'],
            '__user_id' => ['type' => 'link'],
        ],
            [],
            ['tree']
        );
    }

    public function down()
    {
        $this->dropTable('categories');
    }
    
}