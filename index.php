<?php
declare(strict_types=1);
require_once 'twig.php';
require_once 'controler/backend.php';

$page = 'home';
if (isset($_GET['p'])) {
    $page = $_GET['p'];
}

switch ($page){
    case 'home':
        twigRender('home.html.twig');
        break;
    case 'signUp':
        twigRender('signUp.html.twig');
        break;
}






