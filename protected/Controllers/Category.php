<?php

namespace App\Controllers;

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
        $this->redirect('/account/showCategories');
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
        $this->redirect('/account/showCategories');
    }

}