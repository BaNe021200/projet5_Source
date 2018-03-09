<?php
declare(strict_types=1);

function newFolder()
{
    $folder='users/img/user/'.$_COOKIE['username'];

    mkdir($folder);
}

function newFolderCrop()
{
    $folderCrop='users/img/user/'.$_COOKIE['username'].'/crop';

    mkdir($folderCrop);
}

function newFolderThumbnails()
{
    $folderThumbnails='users/img/user/'.$_COOKIE['username'].'/thumbnails';

    mkdir($folderThumbnails);
}

function newFolderProfilPicture()
{
    $folderProfilPicture = 'users/img/user/profilPicture';
    mkdir($folderProfilPicture);
}

/*function resizeImage()
{
    $images= glob('users/img/user/'.$_POST['username'].'/*.jpg');
    foreach ($images as $image)
    {
        $src=$image;//
        $image= imagecreatefromjpeg($src);
        $imageSize = getimagesize($src);//var_dump($imageSize);die;

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

}*/

/*function resizeByHeight()
{
    $images= glob('users/img/user/'.$_POST['username'].'/*.jpg');
    foreach ($images as $image)
    {
        $src=$image;//var_dump($src);die;
        $image= imagecreatefromjpeg($src);
        $imageSize = getimagesize($src);



        $newHeight = 700;
        $newWidth= intval(($imageSize[0] * (($newHeight)/$imageSize[1])));//var_dump($newHeight);

        $newImage = imagecreatetruecolor($newWidth,$newHeight);
        imagecopyresampled($newImage, $image,0,0,0,0,$newWidth, $newHeight,$imageSize[0],$imageSize[1]);

        imagejpeg($newImage,$src);
        imagedestroy($image);



    }

}*/
function resizeByHeight()
{
    $images= glob('users/img/user/'.$_COOKIE['username'].'/*.jpg');
    foreach ($images as $image)
    {
        $src=$image;//var_dump($src);die;
        $image= imagecreatefromjpeg($src);
        $imageSize = getimagesize($src);



        $newHeight = 700;
        $newWidth= intval(($imageSize[0] * (($newHeight)/$imageSize[1])));//var_dump($newHeight);

        $newImage = imagecreatetruecolor($newWidth,$newHeight);
        imagecopyresampled($newImage, $image,0,0,0,0,$newWidth, $newHeight,$imageSize[0],$imageSize[1]);

        imagejpeg($newImage,$src);
        imagedestroy($image);



    }

}


//upload_max_filesize = 40M