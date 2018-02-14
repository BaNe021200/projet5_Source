<?php
declare(strict_types=1);
require_once 'vendor/autoload.php';

function userData()
{
    $db = new \PDO('mysql:host=localhost;dbname=projet5;charset=utf8', 'root', 'nzB69yCSBDz9eK46');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
    $userData = $db->query('SELECT * FROM projet5_user');
    return $userData;
}


$loader =new Twig_Loader_Filesystem(__DIR__.'/templates');
$twig = new Twig_Environment($loader, [

    'cache' => false,//__DIR__.'/tmp',
    'debug'=> true
]);

$twig->addExtension(new Twig_Extension_Debug());

echo $twig->render('home.html.twig',['userData'=>userData()]);