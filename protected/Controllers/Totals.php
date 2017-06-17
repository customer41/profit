<?php

namespace App\Controllers;

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

    public function actionSetValues($categoryName = null, $amount = 0, $delete = null)
    {
        $extra = $this->app->user->extra;
        switch ($categoryName) {
            case 'Доход':
                if ('yes' == $delete) {
                    $extra->profit -= $amount;
                } else {
                    $extra->profit += $amount;
                }
                break;
            case 'Долг+':
                if ('yes' == $delete) {
                    $extra->debtPlus -= $amount;
                } else {
                    $extra->debtPlus += $amount;
                }
                break;
            case 'Долг-':
                if ('yes' == $delete) {
                    $extra->debtMinus -= $amount;
                } else {
                    $extra->debtMinus += $amount;
                }
                break;
            case 'Списать долг+':
                if ('yes' == $delete) {
                    $extra->debtPlus += $amount;
                    $extra->profit -= $amount;
                } else {
                    $extra->debtPlus -= $amount;
                    $extra->profit += $amount;
                }
                break;
            case 'Списать долг-':
                if ('yes' == $delete) {
                    $extra->debtMinus += $amount;
                    $extra->costs -= $amount;
                } else {
                    $extra->debtMinus -= $amount;
                    $extra->costs += $amount;
                }
                break;
            default:
                if ('yes' == $delete) {
                    $extra->costs -= $amount;
                } else {
                    $extra->costs += $amount;
                }
        }
        $extra->save();
        $this->redirect('/operation/show/');
    }

}