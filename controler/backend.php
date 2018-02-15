<?php
declare(strict_types=1);
require_once 'model/UserManager.php';
require_once 'twig.php';
use model\UserManager;

function addNewUser(){
    $user = new UserManager();
    $newUser = $user->addUser();
    if($newUser)
    {
        header('Location:index.php?p=home');

    }
    else
    {
        throw new Exception('Une erreur est survenue, il est impossible de vous enregistrer');
    }
}

function signUp()
{
    twigRender('signUp.html.twig','','');
}

function home()
{
    $user= new UserManager();

    twigRender('home.html.twig','userData', $userData = $user->userData());
}

function connectUser()
{
    twigRender('connexion.html.twig','','');
}

function getUserData()
{
    $user = new UserManager();

    twigRender('hello.html.twig','userDatum',$userDatum = $user->getUserMainData());

}