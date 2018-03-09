<?php
declare(strict_types=1);

require_once 'model/UserManager.php';





?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Carousel Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="public/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/tuto.cs">
    <link rel="stylesheet" href="public/css/home.css">

    <!-- Custom styles for this template -->
 <link href="public/css/carousel.css" rel="stylesheet">
</head>
<body>





    <div class="container">
        <div class="row">
        <?php foreach ($usersProfilPictures as $usersProfilPicture): ?>
        <!-- Three columns of text below the carousel -->



            <div class="col-lg-4">


                
                <img class="rounded-circle" src="users/img/user/<?= $usersProfilPicture['username'] ?>/profilPicture/<?= $usersProfilPicture['filename'] ?>.jpg" alt="Generic placeholder image" width="300" height="300">
                <h2><?= $usersProfilPicture['username']; ?></h2>
                <p>Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod. Nullam id dolor id nibh ultricies vehicula ut id elit. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Praesent commodo cursus magna.</p>
                <p><a class="btn btn-secondary" href="#" role="button">View details &raquo;</a></p>
            </div><!-- /.col-lg-4 -->

            <?php endforeach; ?>
    </div>

    </div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="../../../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
<script src="../../public/assets/js/vendor/popper.min.js"></script>
<script src="../../public/dist/js/bootstrap.min.js"></script>
<!-- Just to make our placeholder images work. Don't actually copy the next line! -->
<script src="../../public/assets/js/vendor/holder.min.js"></script>
</body>
</html>
