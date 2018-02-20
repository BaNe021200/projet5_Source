<?php
declare(strict_types=1);
session_start();
require_once 'vendor/autoload.php';
require_once 'model/functions.php';
require_once 'model/UserManager.php';
//require_once 'vendor/twig/twig/lib/Twig/Extension/Session.php';
use model\UserManager;




        function twigRender($renderPath,$argument1,$argument2)
        {
            //$user = new UserManager();
            // $userData = $user->userData();
            // $userDatum = $user->getUserMainData();




            $loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
            $twig = new Twig_Environment($loader, [

                'cache' => false,//__DIR__.'/tmp',
                'debug' => true
            ]);
            $twig->addExtension(new Twig_Extension_Debug());
           // $twig->addExtension(new Twig_Extension_Session());

            echo $twig->render($renderPath,[
                'userDatum' => $_SESSION,
                $argument1 => $argument2

            ]);

        }



