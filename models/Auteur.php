<?php

namespace App\Models;

class Auteur extends CRUD{ //Precise la table, la primaryKey et les fillables pour la class Auteur lorsqu'elle utilise les fonction CRUD
    protected $table = 'auteur';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'prenom'];
}

