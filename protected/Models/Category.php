<?php

namespace App\Models;

use T4\Core\Exception;
use T4\Orm\Model;

class Category
    extends Model
{

    protected static $schema = [
        'table' => 'categories',

        'columns' => [
            'name' => ['type' => 'string', 'length' => 50],
            'notDeleted' => ['type' => 'bool'],
        ],

        'relations' => [
            'user' => ['type' => self::BELONGS_TO, 'model' => User::class],
        ],
    ];

    protected static $extensions = ['tree'];

    protected function validateName($val)
    {
        if ('' == $val) {
            throw new Exception('Заполните название категории');
        }
        return true;
    }

}