<?php

namespace App\Controllers;

use T4\Mvc\Controller;

class Settings
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

    }

}