<?php
declare(strict_types=1);
require_once 'vendor/autoload.php';
require_once 'model/functions.php';
require_once 'model/UserManager.php';
use model\UserManager;


        function twigRender($renderPath,$argument1,$argument2)
        {
            $user = new UserManager();
            // $userData = $user->userData();
            // $userDatum = $user->getUserMainData();


            $loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
            $twig = new Twig_Environment($loader, [

                'cache' => false,//__DIR__.'/tmp',
                'debug' => true
            ]);
            $twig->addExtension(new Twig_Extension_Debug());
            echo $twig->render($renderPath,[$argument1 => $argument2]);

        }



