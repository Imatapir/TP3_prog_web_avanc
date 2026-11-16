<?php

namespace App\Controllers;

use App\Providers\View;
use App\Providers\Validator;
use App\Models\User;

class AuthController {
    public function create(){ //Affiche le form de connexion
        return View::render('auth/create');
    }

    public function store(array $data){ //Fait la validation et la connexion de l'utilisateur
        $validator = new Validator();
        $validator->field('username', $data['username'], "nom d'utilisateur")->required()->max(30)->email();
        $validator->field('password', $data['password'], "mot de passe")->min(6)->max(50);

        if($validator->isSuccess()) {
            $user = new User;
            if($user->checkUser($data['username'], $data['password'])) {
                return View::redirect('');
            }

            $errors['login'] = "Nom d'utilisateur ou mot de passe invalide";
        } else {
            $errors = $validator->getErrors();
        }
        return View::render ('auth/create', ['errors' => $errors, 'user' => $data]);
    }

        public function delete(){ //Detruit la session
            session_destroy();
            return View::redirect('login');
    }
}