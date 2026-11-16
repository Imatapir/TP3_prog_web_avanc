<?php
namespace App\Providers;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class View {
    static public function render(string $template, $data = []){
        $loader = new FilesystemLoader('views');
        $twig = new Environment($loader);
        $twig->addGlobal('asset', ASSET);
        $twig->addGlobal('base', BASE);
        $twig->addGlobal('session', $_SESSION);
        if(isset($_SESSION['fingerPrint']) and $_SESSION['fingerPrint'] == md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'])){ //
            $guest = false;
        } else {
            $guest = true; //Si personne ne match le fingerPrint precedent, l'utilisateur est labeled "guest"
        }
        $twig->addGlobal('guest', $guest);
        echo $twig->render($template.".php", $data);

    }

    static public function redirect(string $url){ //Redirige le navigateur sur une autre page en parametre.
        header('location:'.BASE.'/'.$url);
    }

}
