<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\UserExtra;
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
                $date = date('Y-m-') . (strlen($post->number) == 2 ? $post->number : '0' . $post->number);
                $amount = $post->amount;
                $category = Category::findByName($post->category);
                $comment = ('' == $post->comment) ? $post->category : $post->comment;

                $query  = 'SELECT * FROM operations WHERE date="' . $date . '" AND __category_id=' . $category->getPk();
                $query .= ' AND comment="' . $comment . '" AND __user_id=' . $this->app->user->getPk();
                $operation = \App\Models\Operation::findByQuery($query);
                if (false != $operation) {
                    $operation->amount += $amount;
                } else {
                    $operation = new \App\Models\Operation();
                    $operation->date = $date;
                    $operation->amount = $amount;
                    $operation->category = $category;
                    $operation->comment = $comment;
                    $operation->user = $this->app->user;
                }
                $operation->save();
                $this->redirect('/totals/setValues/?categoryName=' . urlencode($category->name) . '&amount=' . $amount);
            } catch (Exception $error) {
                $this->app->flash->error = $error;
            }
        }
        $this->redirect('/operation/add/');
    }

    public function actionDelete($id = 0)
    {
        $operation = \App\Models\Operation::findByPK($id);
        if (false != $operation) {
            $operation->delete();
        }
        $this->redirect('/totals/setValues/?categoryName=' . urlencode($operation->category->name) . '&amount=' . $operation->amount . '&delete=yes');
    }

    public function actionShowResults()
    {
        $this->data->extra = UserExtra::findByPK($this->app->user->getPk());

        $operations = \App\Models\Operation::findAll([
            'where' => 'date between "' . date('Y-m-01') . '" and "' . date('Y-m-d') . '" and __user_id=' . $this->app->user->getPk()
        ]);

        $this->data->profit = array_sum($operations->filter(function (\App\Models\Operation $x) {
            return $x->category->name == 'Доход';
        })->collect('amount'));

        $costsCategories = $operations->filter(function (\App\Models\Operation $x) {
            return 0 == $x->category->notDeleted;
        });
        $this->data->costs = array_sum($costsCategories->collect('amount'));

        $costsCategories = $costsCategories->group('__category_id');
        ksort($costsCategories);
        $categoriesAmounts = [];
        foreach ($costsCategories as $collection) {
            $categoriesAmounts[$collection[0]->category->name] = array_sum($collection->collect('amount'));
        }
        $this->data->categoriesAmounts = $categoriesAmounts;
    }

}