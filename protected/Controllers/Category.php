<?php

namespace App\Controllers;

use App\Models\User;
use T4\Core\Exception;
use T4\Mvc\Controller;

class Category
    extends Controller
{

    protected function access($action)
    {
        if (null !== $this->app->user || 'SaveAll' == $action) {
            return true;
        }
        return false;
    }

    public function actionAdd()
    {

    }

    public function actionSaveAll($userId)
    {
        if (false == User::findByPK($userId)) {
            $this->redirect('/');
        }

        if (User::findByPK($userId)->categories->isEmpty()) {
            $categories = [
                'Доход', 'Питание', 'Транспорт', 'Медицина', 'Связь', 'Коммунальные услуги',
                'Товары и услуги для дома', 'Вещи и аксессуары', 'Здоровье и красота', 'Техника и ПО',
                'Обучение', 'Работа и бизнес', 'Подарки и помощь', 'Досуг и хобби', 'Штрафы и взносы', 'Долги',
                'Долг+', 'Долг-', 'Списать долг+', 'Списать долг-',
            ];

            $categoriesNotDeleted = ['Доход', 'Долги', 'Долг+', 'Долг-', 'Списать долг+', 'Списать долг-'];

            foreach ($categories as $category) {
                $item = new \App\Models\Category();
                $item->name = $category;
                $item->notDeleted = (in_array($category, $categoriesNotDeleted)) ? 1 : 0;
                $item->__user_id = $userId;
                $item->save();
            }
        }

        $this->redirect('/');
    }

    public function actionSave($title = null)
    {
        try {
            $category = new \App\Models\Category();
            $category->name = $title;
            $category->notDeleted = 0;
            $category->user = $this->app->user;
            $category->save();
        } catch (Exception $error) {
            $this->app->flash->error = $error;
        }
        $this->redirect('/account/editCategories');
    }

    public function actionDelete($id)
    {
        $validIds = $this->app->user->categories
            ->filter(function (\App\Models\Category $x) { return $x->notDeleted == 0; })
            ->collect('__id');

        if (in_array($id, $validIds)) {
            \App\Models\Category::findByPK($id)->delete();
        }
        $this->redirect('/account/editCategories');
    }

    public function actionUp($id)
    {
        $user = $this->app->user;
        $validIds = $user->categories->collect('__id');
        if (in_array($id, $validIds)) {
            $category = \App\Models\Category::findByPK($id);
            $sibling = $category->getPrevSibling();
            if (!empty($sibling) && $sibling->user->getPk() == $user->getPk()) {
                $category->insertBefore($sibling);
            }
        }
        $this->redirect('/account/editCategories');
    }

    public function actionDown($id)
    {
        $user = $this->app->user;
        $validIds = $user->categories->collect('__id');
        if (in_array($id, $validIds)) {
            $category = \App\Models\Category::findByPK($id);
            $sibling = $category->getNextSibling();
            if (!empty($sibling) && $sibling->user->getPk() == $user->getPk()) {
                $category->insertAfter($sibling);
            }
        }
        $this->redirect('/account/editCategories');
    }

}