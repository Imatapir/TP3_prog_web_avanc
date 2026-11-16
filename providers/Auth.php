<?php
namespace App\Providers;

class Auth { 
    static public function session(){ //Verifie que l’utilisateur est connecté (fingerPrint), sinon redirige vers login.
        if(isset($_SESSION['fingerPrint']) and $_SESSION['fingerPrint'] == md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'])) {
            return true;
        }
        View::redirect('login');
        exit;
    }

    static public function privilege(int $id){ //Verifie le role de l’utilisateur (1 = admin, 2 = auteur, 3 = client)
        if($_SESSION['user_privilege'] == $id || $_SESSION['user_privilege'] == 1){
            return true;
        }
        View::redirect('login');
        exit;
    }
}