<?php

namespace App\Models;

use T4\Orm\Model;

class Role
    extends Model
{

    protected static $schema = [
        'table' => '__user_roles',

        'columns' => [
            'name' => ['type' => 'string'],
            'title' => ['type' => 'string'],
        ],

        'relations' => [
            'users' => ['type' => self::MANY_TO_MANY, 'model' => User::class, 'on' => '__user_roles_to_users'],
        ],
    ];

}