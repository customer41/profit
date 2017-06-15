<?php

namespace App\Controllers;

use App\Models\Operation;
use App\Models\UserExtra;
use T4\Core\Exception;
use T4\Mvc\Controller;

class Totals
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
            $this->redirect('/totals/setInitValues/');
        } else {
            $this->redirect('/totals/showInitValues');
        }
    }

    public function actionSetInitValues()
    {
        if (false != $this->app->user->extra) {
            $this->redirect('/totals/');
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
                $this->redirect('/totals/');
            } catch (Exception $error) {
                $this->data->error = $error;
            }
        }
    }

    public function actionShowInitValues()
    {
        if (false == $this->app->user->extra) {
            $this->redirect('/totals/');
        }
        $this->data->startSum = $this->app->user->extra->startSum;
    }

    public function actionSetValues($operationId = 0)
    {
        $operation = Operation::findByPK($operationId);
        $categoryName = $operation->category->name;
        $amount = $operation->amount;
        $extra = $this->app->user->extra;

        switch ($categoryName) {
            case 'Доход':
                $extra->profit += $amount;
                break;
            case 'Долг+':
                $extra->debtPlus += $amount;
                break;
            case 'Долг-':
                $extra->debtMinus += $amount;
                break;
            case 'Списать долг+':

                break;
            case 'Списать долг-':
                break;
            default:
                $extra->costs += $amount;
        }
        $extra->save();

        $this->redirect('/operation/show/');
    }

}