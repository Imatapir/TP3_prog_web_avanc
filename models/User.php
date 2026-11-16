<?php

namespace App\Models;
use App\Models\CRUD;

class User extends CRUD {
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $fillable = ['username', 'password', 'privilege_id', 'client_id'];

    public function hashPassword(string $password, $cost = 10): string { //Hache le mot de passe avant de l'enregistrer
        $options = [ 'cost' => $cost];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    public function checkUser(string $username, string $password): bool{ //Verifie les identifiants et cree une session si tout est bon
        $user = $this->unique('username', $username);
        if($user){
            if(password_verify($password, $user['password'])){
                session_regenerate_id();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['username'];
                $_SESSION['user_privilege'] = $user['privilege_id'];
                $privilege = new Privilege;
                $nom = $privilege->selectId($user['privilege_id']);
                $_SESSION['privilege_name'] = $nom['privilege'];
                $_SESSION['fingerPrint'] = md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']); 
                return true;
            } else {
                return false;
            }
        } else {
        return false;
        }
    }
}