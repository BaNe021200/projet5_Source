<?php
declare(strict_types=1);

$path_parts = pathinfo('/www/htdocs/inc/lib.inc.php');
/*
echo nl2br($path_parts['dirname']);
echo nl2br($path_parts['basename']);
echo nl2br($path_parts['extension']);
echo nl2br($path_parts['filename']); // depuis PHP 5.2.0
*/
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>path info</h1>
<p>adresse complète : <strong><?= '/www/htdocs/inc/lib.inc.php' ?></strong> </p>
<p>dirname : <strong><?= $path_parts['dirname'] ?></strong></p>
<p>basename :<strong><?= $path_parts['basename'] ?></strong></p>
<p>extension : <strong><?= $path_parts['extension'] ?></strong></p>
<p> filename : <strong><?= $path_parts['filename'] ?></strong></p>

</body>
</html>

