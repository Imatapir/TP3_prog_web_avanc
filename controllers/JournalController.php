<?php

namespace App\Controllers;

use App\Providers\View;
use App\Providers\Auth;
use App\Models\Journal;

class JournalController {

public function index(){ //Affiche le journal(log)
    Auth::session();
    Auth::privilege(1);
    $crud = new Journal;
    $visites = $crud->select('id', 'DESC');
    return View::render('journal/index', ['visites' => $visites]);
    }
}