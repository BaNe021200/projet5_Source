<?php
declare(strict_types=1);

require_once 'model/UserManager.php';
require_once 'twig.php';
require_once 'functions.php';
require_once 'model/User.php';
require_once 'resize2.php';
require_once 'crop.php';

use model\UserManager;
use model\User;


function homeUserFront($userId)
{
    $user = new UserManager();
    $data= $user->getUserProfile($userId);


    twigRender('frontend/homeUserFront.html.twig','data',$data,'','');
}

function userGalerie($userId,$username)
{
    $user= new UserManager();
    $userGalerie= $user->frontUsergalerie($userId,$username);

    twigRender('frontend/userGalerie.html.twig','images',$userGalerie,'','');
}

function get_registry()
{

    $user = new UserManager();

    $newUser = $user->addUser();var_dump($newUser);
    $message=[];
    if($newUser)
    {

        $message[] = 'Bienvenue ! votre inscription à bien été prise en compte, vous pouvez désormais vous connecter avec vos idenfiants';
    }
    else
    {
        $message[]= 'Nous sommes navrés mais un erreur est survenue et votre inscription n\'a pas pu est prise en compte. vous êtes invitez à recommencer ultérieurement ';
    }

       mail($newUser['email'],'Confirmation d\'incription','bonjour'.$newUser['first_name'], 'votre inscription est confirmé. Notez votre pseudo : '.$newUser['username']. 'Votre mot de passe est celui que vous avez tapé pour vous inscrire. Merci et à bientôt' );

    twigRender('frontend/registrySucces.html.twig','message',$message,'','');

}

function home()
{
    $user=new UserManager();
    $usersProfilPictures=$user->getUserProfilePictures();

    twigRender('frontend/home.html.twig','userdata',$usersProfilPictures ,'','');
}

function signUp()
{
    twigRender('frontend/signUp.html.twig','','','','');
}

