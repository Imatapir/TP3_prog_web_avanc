<?php

namespace App\Controllers;

use App\Models\Commande;
use App\Models\CommandeLivre;
use App\Models\Client;
use App\Models\Livre;
use App\Providers\View;
use App\Providers\Validator;
use App\Providers\Auth;
use App\Providers\Facture;

class CommandeController{

    public function index(){ //Liste les commandes
        Auth::session();
        Auth::privilege(3);
        $crud = new Commande;
        $commandes = $crud->selectAvecClient();
        return View::render('commande/index', ['commandes' => $commandes]);
    }

    private function chargerCommande($id){ //Load les detail d'une commande
        $crud = new Commande;
        $commande = $crud->selectId($id);
        if(!$commande){
            return false;
        }

        $crudClient = new Client;
        $client = $crudClient->selectId($commande['client_id']);
        $crudLigne = new CommandeLivre;
        $lignes = $crudLigne->selectParCommande($id);
        $total = 0;
        foreach($lignes as $i => $ligne){
            $lignes[$i]['sous_total'] = $ligne['prix'] * $ligne['quantite'];
            $total += $lignes[$i]['sous_total'];
        }
        return ['commande' => $commande, 'client' => $client, 'lignes' => $lignes, 'total' => $total];
    }

    public function show(array $data = []){ //Affiche une commande et son client
        Auth::session();
        Auth::privilege(3);
        if(isset($data['id'])){
            $donnees = $this->chargerCommande($data['id']);
            if($donnees){
                return View::render('commande/show', $donnees);
            }
        }
        return View::redirect('commandes');
    }

    public function pdf(array $data = []){ //Genere et enregistre un pdf de la facture selectionnée
        Auth::session();
        Auth::privilege(3);
        if(isset($data['id'])){
            $donnees = $this->chargerCommande($data['id']);
            if($donnees){
                return Facture::generer($donnees['commande'], $donnees['client'], $donnees['lignes'], $donnees['total']);
            }
        }
        return View::redirect('commandes');
    }

            
            

    private function donneesPanier(){ // Prepare les data du paniers gardé en session
        if(!isset($_SESSION['panier'])){
            $_SESSION['panier'] = [];
        }
        $crudLivre = new Livre;
        $lignes = [];
        $total = 0;
        foreach($_SESSION['panier'] as $livreId => $quantite){
            $livre = $crudLivre->selectId($livreId);
            if($livre){
                $sousTotal = $livre['prix'] * $quantite;
                $total += $sousTotal;
                $lignes[] = [
                    'titre' => $livre['titre'],
                    'prix' => $livre['prix'],
                    'quantite' => $quantite,
                    'sous_total' => $sousTotal
                ];
            }
        }
        $crudClient = new Client;
        return [
            'lignes' => $lignes,
            'total' => $total,
            'livres' => $crudLivre->select('titre'),
            'clients' => $crudClient->select('name')
        ];
    }

    public function create(){ //Affiche le formulaire de creation de commande
        Auth::session(); 
        Auth::privilege(3);
        return View::render('commande/create', $this->donneesPanier());
    }

    public function ajouter(array $data){ //Valide et ajoute un livre au panier
        Auth::session();
        Auth::privilege(3);
        $val = new Validator;
        $val->field('livre_id', $data['livre_id'], 'livre')->int()->required('Vous devez choisir un livre');
        $val->field('quantite', $data['quantite'], 'quantité')->required()->int();

        if($val->isSuccess()){
            if(!isset($_SESSION['panier'][$data['livre_id']])){
                $_SESSION['panier'][$data['livre_id']] = 0;
            }
            $_SESSION['panier'][$data['livre_id']] += $data['quantite'];
            return View::redirect('commande/create');
        }
        $errors = $val->getErrors();
        return View::render('commande/create', array_merge($this->donneesPanier(), ['errors' => $errors]));
    }

    public function vider(){ //Vide le panier
        Auth::session();
        Auth::privilege(3);
        $_SESSION['panier'] = [];
        return View::redirect('commande/create');
    }

    public function store(array $data){ //Enregistre dans la DB la commande et ses details
        Auth::session();
        Auth::privilege(3);
        $val = new Validator;
        $val->field('client_id', $data['client_id'], 'client')->int()->required('Vous devez choisir un client');

        $errors = [];
        if(!$val->isSuccess()){
            $errors = $val->getErrors();
        }
        if(empty($_SESSION['panier'])){
            $errors['panier'] = 'Le panier est vide.';
        }
        if(!empty($errors)){
            return View::render('commande/create', array_merge(
                $this->donneesPanier(),
                ['errors' => $errors, 'client_id' => $data['client_id']]
            ));
        }

        $crud = new Commande;
        $commandeId = $crud->insert([
            'client_id' => $data['client_id'],
            'date_commande' => date('Y-m-d')
        ]);
        if($commandeId){
            $crudLigne = new CommandeLivre;
            foreach($_SESSION['panier'] as $livreId => $quantite){
                $crudLigne->insert([
                    'commande_id' => $commandeId,
                    'livre_id' => $livreId,
                    'quantite' => $quantite
                ]);
            }
            $_SESSION['panier'] = [];
            return View::redirect('commande/show?id='.$commandeId);
        }
        return View::redirect('commandes');
    }
}


