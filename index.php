<?php
require_once 'vendor/autoload.php';
require_once 'config.php';
session_start(); //Démarre la session PHP, aide a savoir qui est connecté.
$journal = new App\Models\Journal;
$journal->enregistrer(); //Enregistre chaque requete dans le journal avant de traiter la route.
require_once 'routes/web.php'; //Charge les route.



    
