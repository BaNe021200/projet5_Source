<?php
declare(strict_types=1);
require_once 'functionUpload.php';





function cropImages(){
    if (!file_exists('users/img/user/'.$_COOKIE['username'].'/crop'))
    { newFolderCrop();

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
    else
    {
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



}

function cropcenter($image){
    //$images=glob('users/img/user/'.$_COOKIE['username'].'/*.jpg');

        $src= $image;

        $infoName= pathinfo($src);
        $cropName=$infoName['filename'];
        $image = imagecreatefromjpeg($src);
        $crop_width = imagesx($image);
        $crop_height = imagesy($image);

        $size = min($crop_width, $crop_height);


        if($crop_width >= $crop_height) {
            $newx= ($crop_width-$crop_height)/2;

            $im2 = imagecrop($image, ['x' => $newx, 'y' => 0, 'width' => $size, 'height' => $size]);
        }
        else {
            $newy= ($crop_height-$crop_width)/2;

            $im2 = imagecrop($image, ['x' => 0, 'y' => $newy, 'width' => $size, 'height' => $size]);
        }


        imagejpeg($im2, 'users/img/user/'.$_COOKIE['username'].'/crop/'.$cropName.'-cropped-center.jpg',90);


}




