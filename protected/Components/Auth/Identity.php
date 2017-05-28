<?php

namespace App\Components\Auth;


use App\Models\User;
use App\Models\UserSession;
use T4\Http\Helpers;

class Identity
{

    public function login($data)
    {
        $errors = new MultiException();
        if (empty($data->email)) {
            $errors->add( new Exception('Пустой email') );
        }
        if (empty($data->password)) {
            $errors->add( new Exception('Пустой пароль') );
        }
        if (!$errors->isEmpty()) {
            throw $errors;
        }
        $user = User::findByEmail($data->email);
        if (empty($user)) {
            $errors->add( new Exception('Пользователя с таким email не существует') );
            throw $errors;
        }
        if (!password_verify($data->password, $user->password)) {
            $errors->add( new Exception('Неверный пароль') );
            throw $errors;
        }
        $hash = sha1(microtime() . mt_rand());
        $session = new UserSession();
        $session->hash = $hash;
        $session->user = $user;
        $session->save();
        if (isset($data->remember) && 'on' == $data->remember) {
            Helpers::setCookie('auth', $hash, time() + 30*24*60*60);
        } else {
            Helpers::setCookie('auth', $hash);
        }
    }

}