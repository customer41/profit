<?php

namespace App\Migrations;

use T4\Orm\Migration;

class m_1495963812_createBasicTables
    extends Migration
{

    public function up()
    {

        $this->createTable('__users', [
            'email' => ['type' => 'string', 'length' => 50],
            'password' => ['type' => 'string', 'length' => 100],
            'registered' => ['type' => 'datetime', 'default' => 'current_timestamp'],
            'firstName' => ['type' => 'string', 'length' => 30],
            'lastName' => ['type' => 'string', 'length' => 30],
            'startSum' => ['type' => 'int', 'length' => 'long', 'attributes' => 'unsigned'],
        ], [
            'email_idx' => ['type' => 'unique', 'columns' => ['email']],
        ]);

        $adminId = $this->insert('__users', [
            'email' => 'alien1986cs@gmail.com',
            'password' => '$2y$10$yLr5bIET8VZj3c4sDwgLp.sCZCJK42HDvs8MYoi/2wu2Jv7dTQPzm',
            'firstName' => 'Александр',
            'lastName' => 'Попов',
        ]);

        $this->createTable('__user_roles', [
            'name' => ['type' => 'string'],
            'title' => ['type' => 'string'],
        ], [
            ['type' => 'unique', 'columns' => ['name']],
        ]);

        $adminRoleId = $this->insert('__user_roles', [
            'name' => 'Admin',
            'title' => 'Администратор',
        ]);

        $this->createTable('__user_roles_to_users', [
            '__user_id' => ['type' => 'link'],
            '__role_id' => ['type' => 'link'],
        ]);

        $this->insert('__user_roles_to_users', [
            '__user_id' => $adminId,
            '__role_id' => $adminRoleId,
        ]);

        $this->createTable('__user_sessions', [
            'hash' => ['type' => 'string'],
            '__user_id' => ['type' => 'link'],
        ], [
            'hash' => ['columns' => ['hash']],
        ]);
    }

    public function down()
    {
        $this->dropTable('__user_sessions');
        $this->dropTable('__user_roles_to_users');
        $this->dropTable('__user_roles');
        $this->dropTable('__users');
    }
    
}