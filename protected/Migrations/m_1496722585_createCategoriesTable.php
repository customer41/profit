<?php

namespace App\Migrations;

use App\Models\Category;
use App\Models\User;
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

        $categories = [
            'Доход', 'Питание', 'Транспорт', 'Медицина', 'Связь', 'Коммунальные услуги',
            'Товары и услуги для дома', 'Вещи и аксессуары', 'Здоровье и красота', 'Техника и ПО',
            'Обучение', 'Работа и бизнес', 'Подарки и помощь', 'Досуг и хобби', 'Штрафы и взносы', 'Долги',
            'Долг+', 'Долг-', 'Списать долг+', 'Списать долг-',
        ];

        $categoriesNotDeleted = ['Доход', 'Долги', 'Долг+', 'Долг-', 'Списать долг+', 'Списать долг-'];

        foreach ($categories as $category) {
            $item = new Category();
            $item->name = $category;
            $item->notDeleted = (in_array($category, $categoriesNotDeleted)) ? 1 : 0;
            $item->__user_id = User::findByPK(1)->getPk();
            $item->save();
        }
    }

    public function down()
    {
        $this->dropTable('categories');
    }
    
}