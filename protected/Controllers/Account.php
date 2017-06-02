<?php

namespace App\Controllers;

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

    public function actionSetStartSum($startSum = null)
    {
        if (null != $startSum) {
            $user = $this->app->user;
            $user->startSum = $startSum;
            $user->save();
            $this->redirect('/account/settings');
        }
        $this->data->startSum = $this->app->user->startSum ?? 0;
    }

}