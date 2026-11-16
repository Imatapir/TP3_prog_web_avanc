<?php

namespace App\Controllers;

use App\Providers\View;
use App\Providers\Validator;
use App\Models\User;
use App\Models\Client;
use App\Models\Privilege;

class UserController {
    public function create() { //Affiche le form de creation d'un User
        $privilege = new Privilege;
        $privileges = $privilege->select('privilege');
        return View::render('user/create', ['privileges' => $privileges]);
    }

    public function store(array $data) { //Enregistre dans la DB les infos du User
        $val = new Validator;
        $val->field('name', $data['name'], 'nom')->required()->max(50);
        $val->field('username', $data['username'], "nom d'utilisateur")->required()->max(30)->email()->unique('User');
        $val->field('password', $data['password'], 'mot de passe')->min(6)->max(50);
        $val->field('privilege_id', $data['privilege_id'], 'role')->required()->int();

        if(!in_array($data['privilege_id'], ['2', '3'])){ //On ne peut pas creer un compte admin a partir d'ici, on doit l'ajouter manuellement a la DB
            $data['privilege_id'] = 3;
        }

        if($val->isSuccess()){
            $data['email'] = $data['username']; //Le courriel devient le username

            $clientId = null;
            if($data['privilege_id'] == 3){
                $client = new Client;
                $clientId = $client->insert($data);
            }

            $user = new User;
            $insert = $user->insert([
                'username' => $data['username'],
                'password' => $user->hashPassword($data['password']),
                'privilege_id' => $data['privilege_id'],
                'client_id' => $clientId
            ]);
            if($insert){
                return View::redirect('login');
            }
            return View::render('error', ['msg' => "Le compte n'a pas pu être créé."]);
        } else {
            $errors = $val->getErrors();
            unset($data['password']);
            $privilege = new Privilege;
            $privileges = $privilege->select('privilege');
            return View::render('user/create', ['errors' => $errors, 'user' => $data, 'privileges' => $privileges]);
        }
    }
}