<?php

namespace App\Controllers;

use T4\Mvc\Controller;

class Report
    extends Controller
{

    protected function access($action)
    {
        if (null !== $this->app->user) {
            return true;
        }
        return false;
    }

    public function actionFindOperations()
    {
        $this->data->startDate = date('01.m.Y');
        $this->data->finishDate = date('d.m.Y');
        $pattern  = '^(((0[1-9]|[12]\d|3[01])\.(0[13578]|1[02])\.((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\.(0[13456789]|1[012])\.((19|[2-9]\d)\d{2}))|';
        $pattern .= '((0[1-9]|1\d|2[0-8])\.02\.((19|[2-9]\d)\d{2}))|(29\.02\.((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$';
        $this->data->pattern = $pattern;
        $this->data->categories = \App\Models\Category::findAll(['where' => '__user_id=' . $this->app->user->getPk()]);
    }

    public function actionShowOperations()
    {
        if (!empty($_POST)) {
            $where  = '__user_id=' . $this->app->user->getPk();

            $startDate = ('' == $_POST['startDate']) ? '1900-01-01' : date('Y-m-d', strtotime($_POST['startDate']));
            $finishDate = ('' == $_POST['finishDate']) ? date('Y-m-d') : date('Y-m-d', strtotime($_POST['finishDate']));
            $where .= ' and date between "' . $startDate . '" and "' . $finishDate . '"';
            if ($startDate > $finishDate) {
                $this->app->flash->error = 'Начальная дата больше конечной';
                $this->redirect('/report/findOperations/');
            }

            if (null != $_POST['categories']) {
                $where .= ' and __category_id in (' . implode(', ', $_POST['categories']) . ')';
            }

            if ('' != $_POST['description']) {
                $where .= ' and comment like "%' . addslashes($_POST['description']) . '%"';
            }

            $this->data->operations = \App\Models\Operation::findAll([
                'where' => $where
            ]);
        } else {
            $this->redirect('/report/findOperations/');
        }
    }

    public function actionFindCategories()
    {
        $this->data->startDate = date('01.m.Y');
        $this->data->finishDate = date('d.m.Y');
        $pattern  = '^(((0[1-9]|[12]\d|3[01])\.(0[13578]|1[02])\.((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\.(0[13456789]|1[012])\.((19|[2-9]\d)\d{2}))|';
        $pattern .= '((0[1-9]|1\d|2[0-8])\.02\.((19|[2-9]\d)\d{2}))|(29\.02\.((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$';
        $this->data->pattern = $pattern;
    }

    public function actionShowCategories()
    {
        if (!empty($_POST)) {
            $where  = '__user_id=' . $this->app->user->getPk();

            $startDate = ('' == $_POST['startDate']) ? '1900-01-01' : date('Y-m-d', strtotime($_POST['startDate']));
            $finishDate = ('' == $_POST['finishDate']) ? date('Y-m-d') : date('Y-m-d', strtotime($_POST['finishDate']));
            $where .= ' and date between "' . $startDate . '" and "' . $finishDate . '"';
            if ($startDate > $finishDate) {
                $this->app->flash->error = 'Начальная дата больше конечной';
                $this->redirect('/report/findCategories/');
            }

            $operations = \App\Models\Operation::findAll(['where' => $where]);
            $categoriesCollection = $operations->group('__category_id');
            ksort($categoriesCollection);
            $categoriesAmounts = [];
            foreach ($categoriesCollection as $collection) {
                $categoriesAmounts[$collection[0]->category->name] = array_sum($collection->collect('amount'));
            }
            $this->data->categoriesAmounts = $categoriesAmounts;
        } else {
            $this->redirect('/report/findCategories/');
        }
    }

}