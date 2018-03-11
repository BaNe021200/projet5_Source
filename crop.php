<?php
declare(strict_types=1);
require_once 'functionUpload.php';
require_once 'model/UserManager.php';


use model\UserManager;




function cropImages(){
    if (!file_exists('users/img/user/'.$_COOKIE['username'].'/crop'))
    { newFolderCrop();

    $image='users/img/user/'.$_COOKIE['username'].'/img_001.jpg';



        $src= $image;
        $infoName= pathinfo($src);
        $cropName=$infoName['filename'];
        $image = imagecreatefromjpeg($src);var_dump($src);
        $size = min(imagesx($image), imagesy($image));
        $im2 = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
        if ($im2 !== FALSE) {
            imagejpeg($im2, 'users/img/user/'.$_COOKIE['username'].'/crop/img-profil.jpg');
        }
    }

    else
    {
        $images='users/img/user/'.$_COOKIE['username'].'img_001.jpg';



            $src= $images;
            $infoName= pathinfo($src);
            $cropName=$infoName['filename'];
            $image = imagecreatefromjpeg($src);
            $size = min(imagesx($image), imagesy($image));
            $im2 = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
            if ($im2 !== FALSE) {
                imagejpeg($im2, 'users/img/user/'.$_COOKIE['username'].'/crop/img-profil.jpg');
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


        imagejpeg($im2, 'users/img/user/'.$_COOKIE['username'].'/crop/'.$cropName.'-img-profil.jpg',90);


}

/*function thumbNails($width,$height){
    if (!file_exists('users/img/user/'.$_COOKIE['username'].'/thumbnails'))
    { newFolderThumbnails();

        $images=glob('users/img/user/'.$_COOKIE['username'].'/*.jpg');
        foreach ($images as $image){


            $src= $image;
            $infoName= pathinfo($src);
            $cropName=$infoName['filename'];
            $image = imagecreatefromjpeg($src);
            $size = getimagesize($src);
            $thumb=imagecreatetruecolor($width,$height);
            // On va gérer la position et le redimensionnement de la grande image
            if($size[0]>($width/$height)*$size[1] ){ $dimY=$height; $dimX=$height*$size[0]/$size[1]; $decalX=-($dimX-$width)/2; $decalY=0;}
            if($size[0]<($width/$height)*$size[1]){ $dimX=$width; $dimY=$width*$size[1]/$size[0]; $decalY=-($dimY-$height)/2; $decalX=0;}
            if($size[0]==($width/$height)*$size[1]){ $dimX=$width; $dimY=$height; $decalX=0; $decalY=0;}
            // on modifie l'image crée en y plaçant la grande image redimensionné et décalée
            imagecopyresampled($thumb,$image,intval($decalX),intval($decalY),0,0,intval($dimX),intval($dimY),$size[0],$size[1]);
            // On sauvegarde le tout
            imagejpeg($thumb, 'users/img/user/'.$_COOKIE['username'].'/thumbnails/'.$cropName.'-thumb.jpg');

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
            $size = getimagesize($src);
            $thumb=imagecreatetruecolor($width,$height);
            // On va gérer la position et le redimensionnement de la grande image
            if($size[0]>($width/$height)*$size[1] ){ $dimY=$height; $dimX=$height*$size[0]/$size[1]; $decalX=-($dimX-$width)/2; $decalY=0;}
            if($size[0]<($width/$height)*$size[1]){ $dimX=$width; $dimY=$width*$size[1]/$size[0]; $decalY=-($dimY-$height)/2; $decalX=0;}
            if($size[0]==($width/$height)*$size[1]){ $dimX=$width; $dimY=$height; $decalX=0; $decalY=0;}
            // on modifie l'image crée en y plaçant la grande image redimensionné et décalée
            imagecopyresampled($thumb,$image,intval($decalX),intval($decalY),0,0,intval($dimX),intval($dimY),$size[0],$size[1]);
            // On sauvegarde le tout
            imagejpeg($thumb, 'users/img/user/'.$_COOKIE['username'].'/thumbnails/'.$cropName.'-thumb.jpg');

        }
    }



}*/

function thumbNails2($width,$height,$userId,$photoId){
    $user= new UserManager();
    $images=$user->getPhoto2Thumb($userId,$photoId);

    if (!file_exists('users/img/user/'.$_COOKIE['username'].'/thumbnails'))
    { newFolderThumbnails();





                $src= $images[0]['dirname'].'/'.$images[0]['filename'].'.'.$images[0]['extension'];
                $infoName= pathinfo($src);
                $dirname=$infoName['dirname'];
                $fileName=$infoName['filename'];
                $image = imagecreatefromjpeg($src);
                $size = getimagesize($src);
                $thumb=imagecreatetruecolor($width,$height);
                // On va gérer la position et le redimensionnement de la grande image
                if($size[0]>($width/$height)*$size[1] ){ $dimY=$height; $dimX=$height*$size[0]/$size[1]; $decalX=-($dimX-$width)/2; $decalY=0;}
                if($size[0]<($width/$height)*$size[1]){ $dimX=$width; $dimY=$width*$size[1]/$size[0]; $decalY=-($dimY-$height)/2; $decalX=0;}
                if($size[0]==($width/$height)*$size[1]){ $dimX=$width; $dimY=$height; $decalX=0; $decalY=0;}
                // on modifie l'image crée en y plaçant la grande image redimensionné et décalée
                @imagecopyresampled($thumb,$image,intval($decalX),intval($decalY),0,0,intval($dimX),intval($dimY),$size[0],$size[1]);
            // On sauvegarde le tout
                $imageThumbnail= imagejpeg($thumb, 'users/img/user/'.$_COOKIE['username'].'/thumbnails/'.$fileName.'-thumb.jpg');

        $saveThumbnail=$user->addThumbnails($userId,$photoId,$dirname,$fileName);

    }
    else
    {
        //$images=glob('users/img/user/'.$_COOKIE['username'].'/*.jpg');


                $src=$images[0]['dirname'].'/'.$images[0]['filename'].'.'.$images[0]['extension'];
                $infoName= pathinfo($src);
                $dirname=$infoName['dirname'];
                $fileName=$infoName['filename'];
                $image = imagecreatefromjpeg($src);
                $size = getimagesize($src);
                $thumb=imagecreatetruecolor($width,$height);
                // On va gérer la position et le redimensionnement de la grande image
                if($size[0]>($width/$height)*$size[1] ){ $dimY=$height; $dimX=$height*$size[0]/$size[1]; $decalX=-($dimX-$width)/2; $decalY=0;}
                if($size[0]<($width/$height)*$size[1]){ $dimX=$width; $dimY=$width*$size[1]/$size[0]; $decalY=-($dimY-$height)/2; $decalX=0;}
                if($size[0]==($width/$height)*$size[1]){ $dimX=$width; $dimY=$height; $decalX=0; $decalY=0;}
                // on modifie l'image crée en y plaçant la grande image redimensionné et décalée
                @imagecopyresampled($thumb,$image,intval($decalX),intval($decalY),0,0,intval($dimX),intval($dimY),$size[0],$size[1]);
                // On sauvegarde le tout
        $imageThumbnail=imagejpeg($thumb, 'users/img/user/'.$_COOKIE['username'].'/thumbnails/'.$fileName.'-thumb.jpg');

                $saveThumbnail=$user->addThumbnails($userId,$photoId,$dirname,$fileName);
    }



}




