<?php
namespace App\Models;

 abstract class CRUD extends \PDO {

    //Pour connexion DB N0C
    // final public function __construct(){ 
    //     parent::__construct('mysql:host=localhost; dbname=vqaskmunae_librairies; charset=utf8', 'vqaskmunae_David', '8emotdepasseQUIMARCHEPAS!');
    // }

    //Pour connexion DB Local
    final public function __construct(){ 
        parent::__construct('mysql:host=localhost; dbname=librairie; port=3306; charset=utf8', 'root', 'admin');
    }

    final public function select($field = null, $order = "ASC"):array{ // retourne toute les lignes de la table $field
        if($field == null){
            $field = $this->primaryKey;
        }
        $sql = "SELECT * FROM $this->table ORDER BY $field $order";
        // return $sql;
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }

    final public function selectId(int|string $value):bool|array{ //retourne une seule ligne de la table selon son ID
        $sql = "SELECT * FROM $this->table WHERE $this->primaryKey = :$this->primaryKey";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(":$this->primaryKey", $value);
        $stmt->execute();
        $count = $stmt->rowCount();
        if($count == 1){
            return $stmt->fetch();
        }else{
            return false;
        }
    }

    final public function insert(array $data){ //insere une ligne dans une table de donnee
        $data_keys = array_fill_keys($this->fillable, '');
        $data = array_intersect_key($data, $data_keys);
        $fieldName = implode(', ', array_keys($data));
        $fieldBindValue = ":".implode(', :', array_keys($data));
        $sql = "INSERT INTO $this->table ($fieldName) VALUES ($fieldBindValue);";


        $stmt = $this->prepare($sql);
        
        foreach($data as $key=>$value){
            $stmt->bindValue(":$key", $value);
        }
        if($stmt->execute()){
            return $this->lastInsertId();
        }else{
            return false;
        }
 
    }

    
    final public function update(array $data, $id){ //Modifie une ligne selon son ID

        $data_keys = array_fill_keys($this->fillable, '');
        $data = array_intersect_key($data, $data_keys);

        $data[$this->primaryKey] = $id;
        
        $fieldName = null;
        foreach($data as $key=>$value){
            $fieldName .= "$key = :$key, ";
        }
        $fieldName = rtrim($fieldName, ', ');
        $sql = "UPDATE $this->table SET $fieldName WHERE $this->primaryKey = :$this->primaryKey;";

        
        $stmt = $this->prepare($sql);
        foreach($data as $key=>$value){
            $stmt->bindValue(":$key", $value);
        }
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }

    final public function delete($id):bool{ //supprime une ligne selon son ID
        $sql = "DELETE FROM $this->table WHERE $this->primaryKey = :$this->primaryKey";        
        $stmt = $this->prepare($sql);
        $stmt->bindValue(":$this->primaryKey", $id);
        try{
        $stmt->execute();
            return true;
        }catch (\PDOException $e){
            return false;
        }
    }
    
    final public function selectWhere(string $field, $value):array{ //Retourne chaque lignes dans la colonne $field qui ont la valeur $value
        $sql = "SELECT * FROM $this->table WHERE $field = :$field";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(":$field", $value);
        $stmt->execute();
            return $stmt->fetchAll();
    }
    
    final public function unique(string $field, string $value):array|bool { //Retourne la ligne qui a la valeur $value si elle est unique sinon, retourne false
        $sql = "SELECT * FROM $this->table WHERE $field = :$field";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(":$field", $value);
        $stmt->execute();
        $count = $stmt->rowCount();
        if($count === 1){
            return $stmt->fetch();
        } else {
            return false;
        }
    }
}
