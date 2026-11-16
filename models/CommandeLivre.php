<?php

namespace App\Models;

class CommandeLivre extends CRUD{
    protected $table = 'commande_livre';
    protected $primaryKey = 'id';
    protected $fillable = ['commande_id', 'livre_id', 'quantite'];

    public function selectParCommande($commandeId){ //Retourne les livres d’une commande avec leur titre et leur prix
        $sql = "SELECT commande_livre.*, livre.titre, livre.prix
                FROM commande_livre
                INNER JOIN livre ON commande_livre.livre_id = livre.id
                WHERE commande_livre.commande_id = :commande_id";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':commande_id', $commandeId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

