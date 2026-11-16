<?php
namespace App\Providers;

class Validator {

    private $errors = array();
    private string $key;
    private string $value;
    private string $name;

    public function field(string $key, string $value, $name = null){
        $this->key = $key;
        $this->value = $value;
        if($name == null){
            $this->name = ucfirst($key);
        }else{
            $this->name = ucfirst($name);
        }
        return $this;
    }


    //regles

    public function required($message = null){ //Valide que le champ n'est pas vide
        if(empty($this->value)){
            $this->errors[$this->key] = $message ?? "$this->name is required";
        }
        return $this;
    }

    public function min(int $length){ //Valide que le champ a au moins un minimum de carateres
        if(strlen($this->value) < $length){
            $this->errors[$this->key]="$this->name must be equal or greater than $length characters";
        }
        return $this;
    }

      public function max(int $length){ //Valide que le champ n'excede pas un maximum de carateres
        if(strlen($this->value) > $length){
            $this->errors[$this->key]="$this->name must be equal or less than $length characters";
        }
        return $this;
    }

    public function int($message = null){ //Valide que le champ a comme type un "int"
        if(filter_var($this->value, FILTER_VALIDATE_INT) === false){
            $this->errors[$this->key] = $message ?? "$this->name must be an integer";
        }
        return $this;
    }

    public function email(){ //Valide que le champ est sous la forme d'un email
        if(!empty($this->value) && !filter_var($this->value, FILTER_VALIDATE_EMAIL)){
             $this->errors[$this->key]="Invalid $this->name format";
        }
        return $this;
    }

    public function numeric(){ //Valide que le champ est sous forme numerique
        if(!is_numeric($this->value)){
            $this->errors[$this->key]="$this->name must be a number";
        }
        return $this;
    }

    public function isSuccess(){ //Retourne true si les champs d'erreurs sont vides.
        if(empty($this->errors)) return true;
    }

    public function getErrors(){ //Retourne les erreurs si il y en a
        if(!$this->isSuccess()) return $this->errors;
    }

    public function unique(string $model){ //Valide que le champ n'a pas un doublons dans la base de données
        $model = 'App\\Models\\'.$model;
        $model = new $model;
        $unique = $model->unique($this->key, $this->value);
        if($unique){
            $this->errors[$this->key] = "$this->name must be unique";
        }
        return $this;
    }
}

