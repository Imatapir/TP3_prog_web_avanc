<?php
namespace App\Controllers;

use App\Providers\View;

class HomeController { //Affiche la page d'acceuil
    public function index(){
        return View::render('home');
    }
}


