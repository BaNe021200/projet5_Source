<?php
declare(strict_types=1);
require_once 'functionUpload.php';





function cropImages(){
    newFolderCrop();

    $images=glob('users/img/user/'.$_COOKIE['username'].'/*.jpg');
    foreach ($images as $image){


        $src= $image;
        $infoName= pathinfo($src);
        $cropName=$infoName['filename'];
        $image = imagecreatefromjpeg($src);
        $size = min(imagesx($image), imagesy($image));
        $im2 = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
        if ($im2 !== FALSE) {
            imagejpeg($im2, 'users/img/user/'.$_COOKIE['username'].'/crop/'.$cropName.'-cropped.jpg');
        }
    }




}