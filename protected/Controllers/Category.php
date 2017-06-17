<?php

namespace App\Controllers;

use T4\Core\Exception;
use T4\Mvc\Controller;

class Category
    extends Controller
{

    protected function access($action)
    {
        if (null !== $this->app->user) {
            return true;
        }
        return false;
    }

    public function actionDefault()
    {
        $user = $this->app->user;
        if ($user->categories->isEmpty()) {
            $this->redirect('/category/createAll/?userId=' . $user->getPk());
        } else {
            $this->redirect('/category/edit/');
        }
    }

    public function actionCreateAll($userId = 0)
    {
        $user = $this->app->user;
        if ($user->getPk() == $userId && $user->categories->isEmpty()) {
            $articles = [
                'Доход', 'Питание', 'Транспорт', 'Медицина', 'Связь', 'Коммунальные услуги',
                'Товары и услуги для дома', 'Вещи и аксессуары', 'Здоровье и красота', 'Техника и ПО',
                'Обучение', 'Работа и бизнес', 'Подарки и помощь', 'Досуг и хобби', 'Штрафы и взносы',
                'Долг+', 'Долг-', 'Списать долг+', 'Списать долг-',
            ];
            $articlesNotDeleted = ['Доход', 'Долг+', 'Долг-', 'Списать долг+', 'Списать долг-'];
            foreach ($articles as $article) {
                $category = new \App\Models\Category();
                $category->name = $article;
                $category->notDeleted = (in_array($article, $articlesNotDeleted)) ? 1 : 0;
                $category->user= $user;
                $category->save();
            }
        }
        $this->redirect('/category/');
    }

    public function actionEdit()
    {
        $categoriesAll = \App\Models\Category::findAllTree();
        $categories = $categoriesAll->filter(function (\App\Models\Category $x) {
            return $x->__user_id == $this->app->user->getPk();
        });
        if ($categories->isEmpty()) {
            $this->redirect('/category/');
        }
        $this->data->categories = $categories;
    }

    public function actionAdd()
    {

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
        $this->redirect('/category/edit/');
    }

    public function actionDelete($id)
    {
        $validIds = $this->app->user->categories
            ->filter(function (\App\Models\Category $x) { return $x->notDeleted == 0; })
            ->collect('__id');

        if (in_array($id, $validIds)) {
            $category = \App\Models\Category::findByPK($id);
            if ($category->operations->isEmpty()) {
                $category->delete();
            }
        }
        $this->redirect('/category/edit/');
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
        $this->redirect('/category/edit/');
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
        $this->redirect('/category/edit/');
    }

}