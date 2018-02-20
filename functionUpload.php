<?php
declare(strict_types=1);

function newFolder()
{
    $folder='users/img/user/'.$_POST['username'];

    mkdir($folder);
}

function resizeImage()
{
    $images= glob('users/img/user/'.$_POST['username'].'/*.jpg');
    foreach ($images as $image)
    {
        $src=$image;//var_dump($src);die;
        $image= imagecreatefromjpeg($src);
        $imageSize = getimagesize($src);

        if($imageSize[0]>700)
        {
            $newWidth = 700;
            $newHeight= intval(($imageSize[1] * (($newWidth)/$imageSize[0])));//var_dump($newHeight);

            $newImage = imagecreatetruecolor($newWidth,$newHeight);
            imagecopyresampled($newImage, $image,0,0,0,0,$newWidth, $newHeight,$imageSize[0],$imageSize[1]);

            imagejpeg($newImage,$src);
            imagedestroy($image);
        }


    }

}

//upload_max_filesize = 40M