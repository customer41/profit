<?php

namespace App\Models;


use T4\Orm\Model;

class User
    extends Model
{

    static protected $schema = [
        'table' => '__users',

        'columns' => [
            'email' => ['type' => 'string', 'length' => 50],
            'password' => ['type' => 'string', 'length' => 100],
            'registered' => ['type' => 'datetime'],
            'firstName' => ['type' => 'string', 'length' => 30],
            'lastName' => ['type' => 'string', 'length' => 30],
            'startSum' => ['type' => 'int', 'length' => 'long'],
        ],

        'relations' => [
            'roles' => ['type' => self::MANY_TO_MANY, 'model' => Role::class, 'on' => '__user_roles_to_users'],
        ]
    ];

}