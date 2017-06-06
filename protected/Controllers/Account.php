<?php

namespace App\Controllers;

use App\Models\Category;
use T4\Core\MultiException;
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
        $extra = $this->app->user->extra;
        $init = false;
        foreach ($extra as $value) {
            if (null == $value) {
                $init = true;
                break;
            }
        }
        $this->data->init = $init;
    }

    public function actionSetInitVal($values = null)
    {
        $extra = $this->app->user->extra;
        if (null != $extra->startSum || null != $extra->borrowed || null != $extra->loaned) {
            $this->data->init = true;
            $this->data->extra = $extra;
        }

        if (null !== $values) {
            try {
                $this->data->values = $values;
                $values->profit = 0;
                $values->costs = 0;
                $extra->fill($values)->save();
                $this->redirect('/account/setInitVal/');
            } catch (MultiException $errors) {
                $this->data->errors = $errors;
            }
        }
    }

    public function actionShowCategories()
    {
        $this->data->categories = Category::findAllTree(['where' => $this->app->user->getPk()]);
    }

}