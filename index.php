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

    elseif ($_GET['p'] == 'signUp'){
        signUp();
    }

    elseif ($_GET['p'] == 'register'){
        addNewUser();
    }

    elseif ($_GET['p'] == 'connexion'){
        connectUser();
    }

    elseif ($_GET['p'] == 'getConnexion'){
        authentificationConnexion();
    }

    elseif ($_GET['p'] == 'deconnexion'){
        disconnectUser();
    }

    elseif ($_GET['p'] == 'page2'){
        newPage();
    }

    elseif ($_GET['p'] == 'upload'){
        UploadPictures();
    }

    elseif ($_GET['p'] == 'uploadStatement'){
        uploadStatement();
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







