<?php

namespace App\Controllers;

use App\Models\UserExtra;
use T4\Core\Exception;
use T4\Mvc\Controller;

class Init
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
        if (false == $this->app->user->extra) {
            $this->redirect('/init/setValues/');
        } else {
            $this->redirect('/init/showValues');
        }
    }

    public function actionSetValues()
    {
        if (false != $this->app->user->extra) {
            $this->redirect('/init/');
        }
        if (null !== $this->app->request->post->startSum) {
            try {
                $extra = new UserExtra();
                $extra->startSum = $this->app->request->post->startSum;
                $extra->profit = 0;
                $extra->costs = 0;
                $extra->debtPlus = 0;
                $extra->debtMinus = 0;
                $extra->debtTotal = 0;
                $extra->balance = $extra->startSum;
                $extra->user = $this->app->user;
                $extra->save();
                $this->redirect('/init/');
            } catch (Exception $error) {
                $this->data->error = $error;
            }
        }
    }

    public function actionShowValues()
    {
        if (false == $this->app->user->extra) {
            $this->redirect('/init/');
        }
        $this->data->extra = $this->app->user->extra;
    }

}