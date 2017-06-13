<?php

namespace App\Models;

use T4\Core\Exception;
use T4\Orm\Model;

class Operation
    extends Model
{

    protected static $schema = [
        'columns' => [
            'date' => ['type' => 'date'],
            'amount' => ['type' => 'int', 'length' => 'long'],
            'comment' => ['type' => 'text'],
        ],

        'relations' => [
            'user' => ['type' => self::BELONGS_TO, 'model' => User::class],
            'category' => ['type' => self::BELONGS_TO, 'model' => Category::class],
        ],
    ];

    protected function validateAmount($val)
    {
        if (!preg_match('~^[1-9]{1}\d*$~', $val)) {
            throw new Exception('Сумма должна быть числом больше 0');
        }
        return true;
    }

}