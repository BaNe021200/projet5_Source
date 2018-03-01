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

    elseif($_GET['p']== 'register')
    {
        //;
        if(empty($_POST['first_name']) || empty($_POST['last_name'])){
            throw new Exception('Les champs noms et prénoms doivent être remplis');
        }
        elseif (empty($_POST['username']) || empty($_POST['birthday'])){
            throw new Exception('Les champs pseudos et anniversaire doivent être remplis');
        }
        elseif (empty($_POST['email']) || empty($_POST['password'])){
            throw new Exception('Les champs email et mot de passe doivent être remplis');
        }

        else
        {
            controlUsername($_POST['username']);
        }



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

        uploadPicture($_COOKIE['ID'],$_GET['id']);
    }

    elseif ($_GET['p'] == 'galerie3'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            getUserImages($_COOKIE['ID']);
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'deleteImage'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){

            deleteImage($_COOKIE['ID'],$_GET['id']);
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'recropped'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            recropped($_COOKIE['ID'],$_GET['id']);
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'CroppedChoice'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            CroppedChoice($_COOKIE['ID'],$_GET['id']);
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
    $bg_ramdom = mt_rand(1, 3);


   // require('templates/404.html.twig');
    twigRender('404.html.twig','errorMessage',$errorMessage,'bgRamdom',$bg_ramdom);



}







