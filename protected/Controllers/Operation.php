<?php

namespace App\Controllers;

use App\Models\Category;
use T4\Core\Exception;
use T4\Mvc\Controller;

class Operation
    extends Controller
{

    protected function access($action)
    {
        if (null !== $this->app->user) {
            return true;
        }
        return false;
    }

    public function actionShow()
    {
        $operations = \App\Models\Operation::findAll([
            'where' => 'date between "' . date('Y-m-01') . '" and "' . date('Y-m-d') . '" and __user_id=' . $this->app->user->getPk(),
            'order' => 'date asc'
        ]);
        $this->data->operations = $operations;
    }

    public function actionAdd()
    {
        $this->data->current_number = date('j');
        $this->data->date = date('F Y, l');
        $categoriesAll = Category::findAllTree();
        $categories = $categoriesAll->filter(function (\App\Models\Category $x) {
            return $x->__user_id == $this->app->user->getPk();
        });
        $this->data->categories = $categories;
    }

    public function actionSave()
    {
        $post = $this->app->request->post;
        if (!empty($post->getData())) {
            try {
                $operation = new \App\Models\Operation();
                $operation->date = date('Y-m-') . (strlen($post->number) == 2 ? $post->number : '0' . $post->number);
                $operation->amount = $post->amount;
                $operation->category = Category::findByName($post->category);
                $operation->comment = $post->comment;
                $operation->user = $this->app->user;
                $operation->save();
                $this->redirect('/totals/setValues/?operationId=' . $operation->getPk());
            } catch (Exception $error) {
                $this->app->flash->error = $error;

            }
        }
        $this->redirect('/operation/add/');
    }

}