<?php
declare(strict_types=1);


function userData()
{
    $db = new \PDO('mysql:host=localhost;dbname=projet5;charset=utf8', 'root', 'nzB69yCSBDz9eK46');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
    $userData = $db->query('SELECT * FROM projet5_user');
    return $userData;
}