<?php
declare(strict_types=1);
require_once 'twig.php';
require_once 'controler/backend.php';

try
{

if (isset($_GET['p']))
{
    if ($_GET['p'] == 'home'){
        home();
    }

    elseif ($_GET['p'] == 'homeUser'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            homeUser();
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'galerie1'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            galerie1();
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'galerie2'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            galerie2();
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }



    elseif ($_GET['p'] == 'signUp'){
        signUp();
    }

    elseif ($_GET['p'] == 'register'){
        addNewUser();
    }

    elseif ($_GET['p'] == 'connexion'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            homeUser();
        }
        else
            {
                connectUser();
            }
    }

    elseif ($_GET['p'] == 'getConnexion'){
        authentificationConnexion();
    }

    elseif ($_GET['p'] == 'deconnexion'){
        disconnectUser();
    }

    elseif ($_GET['p'] == 'page2'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            page2();
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'upload'){
        UploadPictures();
    }

    elseif ($_GET['p'] == 'uploadStatement'){
        uploadStatement();
    }

    elseif ($_GET['p'] == 'galerie1'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            getUserImages($_SESSION['id']);
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }


}


else
{
    home();
}

}


catch (Exception $e)
{
    $errorMessage= $e->getMessage();
    require('templates/404.html.twig');
}







