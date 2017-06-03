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
        if (null !== $values) {
            try {
                $this->app->user->extra->fill($values)->save();
            } catch (MultiException $errors) {
                $this->app->flash->errors = $errors;
            }
            $this->redirect('/account/settings/');
        }
    }

}