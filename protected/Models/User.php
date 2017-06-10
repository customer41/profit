<?php

namespace App\Models;

use T4\Core\Exception;
use T4\Orm\Model;

class User
    extends Model
{

    protected static $schema = [
        'table' => '__users',

        'columns' => [
            'email' => ['type' => 'string', 'length' => 50],
            'password' => ['type' => 'string', 'length' => 100],
            'registered' => ['type' => 'datetime'],
            'firstName' => ['type' => 'string', 'length' => 30],
            'lastName' => ['type' => 'string', 'length' => 30],
        ],

        'relations' => [
            'roles' => ['type' => self::MANY_TO_MANY, 'model' => Role::class, 'on' => '__user_roles_to_users'],
            'extra' => ['type' => self::HAS_ONE, 'model' => UserExtra::class],
            'categories' => ['type' => self::HAS_MANY, 'model' => Category::class],
        ]
    ];

    protected function validatePassword($val)
    {
        if (60 == strlen($val)) {
            return true;
        }
        if (!preg_match('~^[a-zA-Z0-9]{6,15}$~', $val)) {
            throw new Exception('Пароль может состоять только из цифр и латинских букв. Длина от 6 до 15 символов.');
        }
        return true;
    }

    protected function validateFirstName($val)
    {
        if (!preg_match('~^[a-zA-Zа-яA-ЯёЁ]{2,15}$~u', $val)) {
            throw new Exception('В имени могут быть только буквы. Длина от 2 до 15 символов.');
        }
        return true;
    }
    protected function validateLastName($val)
    {
        if (!preg_match('~^[a-zA-Zа-яA-ЯёЁ]{2,15}$~u', $val)) {
            throw new Exception('В фамилии могут быть только буквы. Длина от 2 до 15 символов.');
        }
        return true;
    }

    protected function sanitizePassword($val)
    {
        if (60 == strlen($val)) {
            return $val;
        }
        return password_hash($val, PASSWORD_DEFAULT);
    }

    protected function sanitizeFirstName($val)
    {
        return mb_convert_case($val, MB_CASE_TITLE);
    }

    protected function sanitizeLastName($val)
    {
        return mb_convert_case($val, MB_CASE_TITLE);
    }

}