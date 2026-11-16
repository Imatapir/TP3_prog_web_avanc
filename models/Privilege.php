<?php
namespace App\Models;
use App\Models\CRUD;

class Privilege extends CRUD{  //Precise la table et la primaryKey pour la class Privilege lorsqu'elle utilise les fonctions CRUD
    protected $table = 'privilege';
    protected $primaryKey = 'id';
}