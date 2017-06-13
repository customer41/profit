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

    public function actionDefault()
    {

    }

    public function actionAdd()
    {
        $this->data->current_number = date('j');
        $this->data->date = date('F Y, l');
        $this->data->categories = Category::findAll();
    }

    public function actionSave()
    {
        $post = $this->app->request->post;
        try {
            $operation = new \App\Models\Operation();
            $operation->date = date('Y-m-') . (strlen($post->number) == 2 ? $post->number : '0' . $post->number);
            $operation->amount = $post->amount;
            $operation->category = Category::findByName($post->category);
            $operation->comment = $post->comment;
            $operation->user = $this->app->user;
            $operation->save();
        } catch (Exception $error) {
            $this->app->flash->error = $error;
        }
        $this->redirect('/operation/');
    }

}