<?php

namespace App\Models;

class Journal extends CRUD {
    protected $table = 'journal';
    protected $primaryKey = 'id';
    protected $fillable = ['ip', 'date_visite', 'utilisateur', 'page'];

    public function enregistrer(){ //Enregistre la page demandée, l'IP, la date et le email de l'utilisateur(nom d'utilisateur)
        $page = $_SERVER['REQUEST_URI'];
        if(str_contains($page, 'favicon.ico')){ // Ignore la requête automatique du navigateur pour l’icône du site.

            return;
        }
        $this->insert([
            'ip' => $_SERVER['REMOTE_ADDR'],
            'date_visite' => date('Y-m-d H:i:s'),
            'utilisateur' => $_SESSION['user_name'] ?? 'Visiteur',
            'page' => $page
        ]);
    }

}