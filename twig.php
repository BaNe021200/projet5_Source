<?php
declare(strict_types=1);
session_start();
require_once 'vendor/autoload.php';
require_once 'model/functions.php';
require_once 'model/UserManager.php';
require_once 'controler/backend.php';
//require_once 'vendor/twig/twig/lib/Twig/Extension/Session.php';
use model\UserManager;









        function twigRender($renderPath,$argument1,$argument2,$argument3,$argument4)
        {
            //$user = new UserManager();
            // $userData = $user->userData();
            // $userDatum = $user->getUserMainData();

            if (file_exists("users/img/user/" . @$_COOKIE['username'] . "/crop/img_001-cropped-center.jpg")) {
                @$src = "users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped-center.jpg";
            } else {
                @$src = "users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped.jpg";
            }






            $loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
            $twig = new Twig_Environment($loader, [

                'cache' => false,//__DIR__.'/tmp',
                'debug' => true
            ]);
            $twig->addExtension(new Twig_Extension_Debug());
            $twig->addExtension(new Twig_Extensions_Extension_Text());
           // $twig->addExtension(new Twig_Extension_Session());

            echo $twig->render($renderPath,[
                'userDatum' => $_SESSION,
                @'imageProfil'=>$src,

                $argument1 => $argument2,
                $argument3 => $argument4,



            ]);

        }



