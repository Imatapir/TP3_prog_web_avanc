<?php

namespace App\Models;

class Client extends CRUD{ //Precise la table, la primaryKey et les fillables pour la class Client lorsqu'elle utilise les fonction CRUD
    protected $table = 'client';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'address', 'zip_code', 'phone', 'email'];
}

