<?php

namespace App\Controllers;

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

    }

    public function actionSetInitVal($values = null)
    {
        $extra = $this->app->user->extra;
        if (null != $extra->startSum || null != $extra->debt) {
            $this->data->init = true;
            $this->data->startSum = $extra->startSum;
            $this->data->debt = $extra->debt;
        }

        if (null !== $values) {
            try {
                $this->data->values = $values;
                $extra->fill($values)->save();
                $this->redirect('/account/setInitVal/');
            } catch (MultiException $errors) {
                $this->data->errors = $errors;
            }
        }
    }

}