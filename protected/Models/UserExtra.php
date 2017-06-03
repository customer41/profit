<?php

namespace App\Models;

use T4\Core\Exception;
use T4\Orm\Model;

class UserExtra
    extends Model
{

    protected static $schema = [
        'table' => '__users_extra',

        'columns' => [
            'startSum' => ['type' => 'int', 'length' => 'long'],
            'profit' => ['type' => 'int', 'length' => 'long'],
            'costs' => ['type' => 'int', 'length' => 'long'],
            'debt' => ['type' => 'int', 'length' => 'long'],
            'balance' => ['type' => 'int', 'length' => 'long'],
        ],

        'relations' => [
            'user' => ['type' => self::BELONGS_TO, 'model' => User::class],
        ],
    ];

    protected function validateStartSum($val)
    {
        if (!preg_match('~^(0|[1-9]{1}\d*)$~', $val)) {
            throw new Exception('Стартовая сумма должна быть больше или равна 0. Можно использовать только целые числа');
        }
        return true;
    }

    protected function validateDebt($val)
    {
        if (!preg_match('~^(0|-?[1-9]{1}\d*)$~', $val)) {
            throw new Exception('В величине долга можно использовать только целые числа');
        }
        return true;
    }

}