<?php

namespace App\Controllers;

use App\Models\Category;
use T4\Core\Exception;
use T4\Mvc\Controller;

class Account
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
        $this->data->user = $this->app->user;
    }

    public function actionSettings()
    {
        $this->data->configured = (null == $this->app->user->extra->startSum) ? false : true;
    }

    public function actionSetStartSum($startSum = null)
    {
        if (null !== $startSum) {
            try {
                $extra = $this->app->user->extra;
                $extra->startSum = $startSum;
                $extra->save();
                $this->redirect('/account/settings/');
            } catch (Exception $error) {
                $this->data->error = $error;
            }
        }
    }

    public function actionShowCurrentValues()
    {
        $this->data->extra = $this->app->user->extra;
    }

    public function actionEditCategories()
    {
        $categories = Category::findAllTree();
        $this->data->categories = $categories->filter(function (\App\Models\Category $x) {
            return $x->__user_id == $this->app->user->getPk();
        });
    }

    public function actionAddUserExtra($userId)
    {
        if (false == User::findByPK($userId)) {
            $this->redirect('/');
        }

        (new \App\Models\UserExtra([
            'profit' => 0,
            'costs' => 0,
            'debtPlus' => 0,
            'debtMinus' => 0,
            '__user_id' => $userId,
        ]))->save();
        $this->redirect('/category/saveAll/?userId=' . $userId);
    }

}