<?php
declare(strict_types=1);

require_once 'model/UserManager.php';
require_once 'twig.php';
require_once 'functionUpload.php';
require_once 'model/User.php';
require_once 'resize2.php';
require_once 'crop.php';

use model\UserManager;
use model\User;

/*function addNewUser(){
    $user = new UserManager();
    $newUser = $user->addUser();
    if($newUser)
    {
        header('Location:index.php?p=home');

    }
    else
    {
        throw new Exception('Une erreur est survenue, il est impossible de vous enregistrer');
    }
}*/

function controlUsername($username)
{
    $userManager= new UserManager();
    $getUsername = $userManager->controlUsername($username);
    if(is_null($getUsername))
    {
        calendarControl();
    }
    else
    {
        throw new Exception("Le pseudo ".$username." existe déjà !");
    }
}

//on contrôle la date

function calendarControl()
{
    $birth = strtotime($_POST['birthday']);
    $today = strtotime('NOW' );


    $birthYear = date('Y',$birth);
    $dateControl = date('Y',$today);

    $birthYearInt = intval($birthYear);
    $dateControlInt = intval($dateControl);

    if($birthYearInt<1900 || $birthYearInt>$dateControlInt)
    {
        throw new Exception('La date est incorrecte');
    }
    else
    {
        birthdayCount();
    }
}

//on controle l'âge
function birthdayCount()
{
    $birth = new DateTime($_POST['birthday']);
    $today = new DateTime(date('d-m-Y'));
    $old = $birth->diff($today);
    $age = $old->y;
    if($age>=18)
    {
        emailcontrol();//ensuite on controle le mail
    }
    else
    {
        throw new Exception('Désolé nous n\'avez pas l\'age requis !' );
    }
}

//controle l'adresse mail
function emailcontrol()
{
    if (!empty($_POST['email']))
    {
        $_POST['email'] = htmlspecialchars($_POST['email']);
        if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email']))
        {
            getLastEmail($_POST['email']);//on vérifie que le meail est bine unique

        }
        else
        {
            throw new Exception('L\'adresse mail n\'est pas correcte');
        }

    }

}

function getLastEmail($email)
{
    $veryEmail= new UserManager();
    $getEmail = $veryEmail->getEmail($email);
    if(is_null($getEmail))
    {
        controlPasswords();
    }
    else
    {
        throw new Exception("L'adresse mail ".$email." existe déjà. veuillez en saisir une autre !");
    }
}

function controlPasswords()
{
    if($_POST['password']===$_POST['password2'])
    {
        get_registry();

    }
    else
    {
        throw new Exception('Les deux mots de passe sont différents');
    }

}

function get_registry()
{

    $user = new UserManager();

    $newUser = $user->addUser();

    if($newUser)
    {
        header('location:index.php?action=welcome');

    }
    else
    {
        throw new Exception('Impossible d\'enregistrer votre profil');
    }
}

function signUp()
{
    twigRender('signUp.html.twig','','','','');
}

function home()
{
    $user=new UserManager();
    $usersProfilPictures=$user->getUserProfilePicture();


    //php
      foreach ($usersProfilPictures as $usersProfilPicture)
        {
            $pictureFilePathCenter= 'users/img/user/'.$usersProfilPicture['username'].'/crop/img_001-cropped-center.jpg';
            $pictureFilePathCrop= 'users/img/user/'.$usersProfilPicture['username'].'/crop/img_001-cropped.jpg';
            $profilPictureFolder="users/img/user/".$usersProfilPicture['username']."/profilPicture";

            if(!file_exists($profilPictureFolder))
            {
                mkdir($profilPictureFolder);


                if(file_exists($pictureFilePathCenter)&& file_exists($pictureFilePathCrop))
                {
                   copy($pictureFilePathCenter,"users/img/user/".$usersProfilPicture['username']."/profilPicture/img-profil.jpg");


                }
                elseif (file_exists($pictureFilePathCrop)&& !file_exists($pictureFilePathCenter))
                {
                    copy($pictureFilePathCrop,"users/img/user/".$usersProfilPicture['username']."/profilPicture/img-profil.jpg");
                }
            }else
            {
                if(file_exists($pictureFilePathCenter)&& file_exists($pictureFilePathCrop))
                {
                    copy($pictureFilePathCenter,"users/img/user/".$usersProfilPicture['username']."/profilPicture/img-profil.jpg");


                }
                elseif (file_exists($pictureFilePathCrop)&& !file_exists($pictureFilePathCenter))
                {
                    copy($pictureFilePathCrop,"users/img/user/".$usersProfilPicture['username']."/profilPicture/img-profil.jpg");
                }
            }
        }






   require_once 'templates/frontend/home2.php';

//twigRender('frontend/home.html.twig','userdata',$usersProfilPictures ,'file_exists',$file_exists);
}

function homeUser()
{
    if (file_exists("users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped-center.jpg")) {
        $src = "users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped-center.jpg";
    } else {
        $src = "users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped.jpg";
    }



    twigRender('homeUser.html.twig','imageProfil',$src,'','');

}

function galerie1()
{

    twigRender('galerie1.html.twig','','' ,'','');
}

function galerie2()
{
    $folder = glob('users/img/user/'.$_COOKIE['username'].'/crop/*-cropped.jpg');
    twigRender('galerie2.html.twig','image',$folder,'','');
}




function connectUser()
{
    twigRender('connexion.html.twig','','','','');
}

function authentificationConnexion()
{
    $userManager = new UserManager();
    $authentification = $userManager->getConnexion();
    $userDatum = $userManager->getUserMainData();//var_dump($userData['gender']);die;
    $pwd = $_POST['password'];
    if (!is_null($authentification)) {
        if (password_verify($pwd, $authentification)) {

            //session_start();
            $_SESSION['id'] = $userDatum['id'];//;
            $_SESSION['username'] = $userDatum['username'];
            $_SESSION['first_name'] = $userDatum['first_name'];
            $_SESSION['gender'] = $userDatum['gender'];

            setcookie("ID", $_SESSION['id'], time() + 3600 * 24 * 365, '', '', false, true);
            setcookie("username", $_SESSION['username'], time() + 3600 * 24 * 365, '', '', false, true);
            setcookie("first_name", $_SESSION['first_name'], time() + 3600 * 24 * 365, '', '', false, true);



            header('Location:index.php?p=homeUser');
            twigRender('homeUser.html.twig','','','','');


        } else {
            throw new Exception('Mauvais identifiant ou mot de passe !');
        }
    } else {
        throw new Exception('Cet identifiant n\'existe pas !');
    }
}


function disconnectUser()
{
    session_abort();
    setcookie("ID","", time()- 60);
    setcookie("username","", time()- 60);
    setcookie("first_name","", time()- 60);

   home();
}

/*function UploadPictures()
{

    $user=new UserManager();

    $messages = [];

    if(!file_exists('users/img/user/'.$_POST['username']))
    {   $i=1;
        newFolder();
        foreach ($_FILES as $file) {//var_dump($file['name']);


            if ($file['error'] == UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if (is_uploaded_file($file['tmp_name'])) {
                //on vérifie que le fichier est d'un type autorisé
                $typeMime = mime_content_type($file['tmp_name']);
                if ($typeMime == 'image/jpeg') {
                    //on verifie la taille du fichier
                    $size = filesize($file['tmp_name']);
                    if ($size > 1600000) {
                        $message = "le fichier est trop gros";
                    } else {

                        $beforeCleanning = $file['name'];
                        //on remplace les caractères qui ne sont ni des lettres ni des chiffres par des tirets

                        $onCleanning = preg_replace('~[^\\pL\d]+~u', '-', $beforeCleanning);
                        //on retire les tirets en début et en fin de chaine
                        $onCleanning = trim($onCleanning, '-');
                        //passage d'un encodage utf 8 à ascii afin d'éviter tous problème d'encodage dans le nom du fichier
                        $onCleanning = iconv('utf-8', 'us-ascii//TRANSLIT', $onCleanning);
                        //on met le nom de fichier en minuscule
                        $onCleanning = strtolower($onCleanning);
                        $afterCleanning = preg_replace('~[^-\w]+~', '', $onCleanning);


                        //var_dump($afterCleanning);


                        //$extension = substr(strchr($file['name'],"."),1);
                        $extension = strtolower(substr(strchr($file['name'], "."), 1));
                        //$destinationPath='upload/user/'.$file['name'];
                        $destinationPath = 'users/img/user/' . $_POST['username'] . '/' . 'img_00'.$i++ . '.' . $extension;
                        //var_dump($destinationPath);die;


                        $temporaryPath = $file['tmp_name'];

                        if (move_uploaded_file($temporaryPath, $destinationPath)) {
                            $messages[] = "le fichier " . $file['name'] . " a été correctement uploadé";


                        } else {
                            $messages[] = "le fichier " . $file['name'] . " n'a pas été correctement uploadé";

                        }

                    }
                } else {
                    $messages[] = 'type de fichiers non valide';
                }
            } else {
                $messages[] = 'un problème est survenu lors de l\'upload';
            }
            $destinationPath= $user->addUserFiles($_SESSION['id']);
        }//twigRender('homeUser.html.twig', 'message', $messages);

    }
    else
    { //$i=0;
      $countFiles = count(glob('users/img/user/'.$_POST['username'].'/*.jpg'))+1;
      $maxFiles = count(glob('users/img/user/'.$_POST['username'].'/*.jpg'));
        if ($maxFiles===6) {
            throw  new Exception(("vous ne pouvez pas uploader plus de 6 photos"));
        }


      /*if ($countFiles !=0 && $countFiles< 5) {
            for ($i === $countFiles ; $i < 5; $i++) {


                $i;  //var_dump($i);die;
            }
        }/*
        elseif ($countFiles===5) {
            throw  new Exception(("vous ne pouvez pas uploader plus de 5 photos"));
        }
        elseif($countFiles===0)
            {
                for($i=1;$i<=5; $i++)
                {
                    $i;
                }

            }
        foreach ($_FILES as $file) {//var_dump($file['name']);


            if ($file['error'] == UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if (is_uploaded_file($file['tmp_name'])) {
                //on vérifie que le fichier est d'un type autorisé
                $typeMime = mime_content_type($file['tmp_name']);
                if ($typeMime == 'image/jpeg') {
                    //on verifie la taille du fichier
                    $size = filesize($file['tmp_name']);
                    if ($size > 1600000) {
                        $message = "le fichier est trop gros";
                    } else {

                        $beforeCleanning = $file['name'];
                        //on remplace les caractères qui ne sont ni des lettres ni des chiffres par des tirets

                        $onCleanning = preg_replace('~[^\\pL\d]+~u', '-', $beforeCleanning);
                        //on retire les tirets en début et en fin de chaine
                        $onCleanning = trim($onCleanning, '-');
                        //passage d'un encodage utf 8 à ascii afin d'éviter tous problème d'encodage dans le nom du fichier
                        $onCleanning = iconv('utf-8', 'us-ascii//TRANSLIT', $onCleanning);
                        //on met le nom de fichier en minuscule
                        $onCleanning = strtolower($onCleanning);
                        $afterCleanning = preg_replace('~[^-\w]+~', '', $onCleanning);


                        //var_dump($afterCleanning);


                        //$extension = substr(strchr($file['name'],"."),1);
                        $extension = strtolower(substr(strchr($file['name'], "."), 1));
                        $i=$countFiles++;
                        $destinationPath = 'users/img/user/' . $_POST['username'] . '/' .  'img_00'.$i++ . '.' . $extension;



                       // $destinationPath=$user->addUserFiles($_SESSION['id']);

                        $temporaryPath = $file['tmp_name'];

                        if (move_uploaded_file($temporaryPath, $destinationPath)) {
                            $messages[] = "le fichier " . $file['name'] . " a été correctement uploadé";


                        } else {
                            $messages[] = "le fichier " . $file['name'] . " n'a pas été correctement uploadé";

                        }

                    }
                } else {
                    $messages[] = 'type de fichiers non valide';
                }
            } else {
                $messages[] = 'un problème est survenu lors de l\'upload';
            }
            $destinationPath= $user->addUserFiles($_SESSION['id']);
        }
    }
    //resizeImage();
    resizeByHeight();
    cropImagess();


twigRender('success.html.twig','message', $messages,'','');

}*/


function uploadPicture($userId,$img)
{


    $user=new UserManager();

    $messages = [];

    if(!file_exists('users/img/user/'.$_COOKIE['username']))
    {
        newFolder();
        foreach ($_FILES as $file) {//var_dump($file['name']);


            if ($file['error'] == UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if (is_uploaded_file($file['tmp_name'])) {
                //on vérifie que le fichier est d'un type autorisé
                $typeMime = mime_content_type($file['tmp_name']);
                if ($typeMime == 'image/jpeg') {
                    //on verifie la taille du fichier
                    $size = filesize($file['tmp_name']);
                    if ($size > 1600000) {
                        $messages[] = "le fichier est trop gros";
                    } else {


                        //$destinationPath='upload/user/'.$file['name'];
                        $destinationPath ="users/img/user/".$_COOKIE['username'].'/img_00'.$img.'.jpg';
                        $uploadimage= $user->addUserFiles($_COOKIE['ID'],$destinationPath);



                        $temporaryPath = $file['tmp_name'];

                        if (move_uploaded_file($temporaryPath, $destinationPath)) {
                            $messages[] = "le fichier " . $file['name'] . " a été correctement uploadé";


                        } else {
                            $messages[] = "le fichier " . $file['name'] . " n'a pas été correctement uploadé";

                        }

                    }
                } else {
                    $messages[] = 'type de fichiers non valide';
                }
            } else {

                if($file['error']==2){$messages[]= 'votre fichier est trop volumineux';}
                if($file['error']==1){$messages[]= 'votre fichier excède la taille de configuration du serveur';}

                //$messages[] = 'un problème est survenu lors de l\'upload';
            }
            //$destinationPath= $user->addUserFiles($_SESSION['id']);
        }//twigRender('homeUser.html.twig', 'message', $messages);

    }
    else
    {
        foreach ($_FILES as $file) {//var_dump($file['name']);


            if ($file['error'] == UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if (is_uploaded_file($file['tmp_name'])) {
                //on vérifie que le fichier est d'un type autorisé
                $typeMime = mime_content_type($file['tmp_name']);
                if ($typeMime == 'image/jpeg') {
                    //on verifie la taille du fichier
                    $size = filesize($file['tmp_name']);
                    if ($size > 1600000) {
                        $messages[] = "le fichier est trop gros";
                    } else {



                        $destinationPath ="users/img/user/".$_COOKIE['username'].'/img_00'.$img.'.jpg';
                        $uploadimage= $user->addUserFiles($_COOKIE['ID'],$destinationPath);


                        $temporaryPath = $file['tmp_name'];

                        if (move_uploaded_file($temporaryPath, $destinationPath)) {
                            $messages[] = "le fichier " . $file['name'] . " a été correctement uploadé";


                        } else {
                            $messages[] = "le fichier " . $file['name'] . " n'a pas été correctement uploadé";

                        }

                    }
                } else {
                    $messages[] = 'type de fichiers non valide';
                }
            } else {

                if($file['error']==2){$messages[]= 'votre fichier est trop volumineux';}
                if($file['error']==1){$messages[]= 'votre fichier excède la taille de configuration du serveur';}

                //$messages[] = 'un problème est survenu lors de l\'upload';
            }



        }
    }
    //resizeImage();


    twigRender('success.html.twig','message', $messages,'','');
    @$imageId= $uploadimage;
    @thumbNails2(525,700,$_COOKIE['ID'],$imageId);
    @resizeByHeight();
    @cropImages();

    $cropFiles = glob('users/img/user/'.$_COOKIE['username'].'/crop/*.jpg');
     $cropFiles = $user->addCropFiles($userId,$img);


}

function recropped($userId,$img){


    $user=new UserManager();
    $folder="users/img/user/".$_COOKIE['username'].'/img_00'.$img.'.jpg';
    $file2crop="users/img/user/".$_COOKIE['username'].'/crop/img_00'.$img.'-cropped.jpg';
    if(file_exists($folder))
    {
        $folderpart=pathinfo($folder);
        $folderfilename=$folderpart['filename'];
        cropcenter($folder);
        $cropCenterFile='users/img/user/'.$_COOKIE['username'].'/crop/img_00'.$img.'-crop-center.jpg';
        $cropCenterFile = $user->addCropCenterFiles($userId,$img);

        twigRender('recroppedView.html.twig','recrop',$cropCenterFile,'img2crop',$file2crop);
    }
    else
    {
        throw new Exception('Il n\'y a rien à recadrer');
    }

}

function croppedChoice($userId,$img){

       $user= new UserManager();
    $src="users/img/user/".$_COOKIE['username']."/crop/img_001-cropped-center.jpg";

    $croppedFile2Delete=$user->deleteImageCroppedCenter($userId,$img);var_dump($userId);
    if(file_exists($src))
    {

        unlink("users/img/user/".$_COOKIE['username']."/crop/img_001-cropped-center.jpg");

        header('Location: index.php?p=homeUser');
    }
    else
    {
        throw new Exception('Il n\'y a rien effacer');
    }


}

function getUserImages($userId)
{

   /* $folderThumbnails = glob('users/img/user/'.$_COOKIE['username'].'/thumbnails/*.jpg');
    $folder=glob('users/img/user/'.$_COOKIE['username'].'/*.jpg');*/
    $user=new UserManager();
    $folder=$user->getThumbnails($userId);

twigRender('galerie3.html.twig','images',$folder,'','');
    //require_once 'templates/photo.php';


}


function getAllImages()
{
    $user = new UserManager();
    $allImages = $user->getAllFiles();
    require_once 'templates/photo.php';
}

function pathInfosuserImages()
{
    $images=glob('users/img/user/'.$_COOKIE['username'].'/*.jpg');
    foreach ($images as $image){
        $path_parts= pathinfo($image);
        $path_parts['dirname'];
        $path_parts['basename'];
        $path_parts['filename'];
        $path_parts['extension'];
     }
}

function deleteImage($userId,$imageId)
{

    $user= new UserManager();
    $imageDeleted=$user->deleteImage($userId,$imageId);var_dump($userId);

    $folderThumbnails="users/img/user/".$_COOKIE['username'].'/thumbnails/img_00'.$imageId.'-thumb.jpg';
    $folderCroppedCenterToDelete = "users/img/user/".$_COOKIE['username'].'/crop/img_00'.$imageId.'-cropped-center.jpg';
    $folderCroppedToDelete = "users/img/user/".$_COOKIE['username'].'/crop/img_00'.$imageId.'-cropped.jpg';
    $folderToDelete = "users/img/user/".$_COOKIE['username'].'/img_00'.$imageId.'.jpg';
    if(file_exists($folderThumbnails)){
        unlink($folderToDelete);
        unlink($folderCroppedToDelete);
        unlink($folderCroppedCenterToDelete);
        unlink($folderThumbnails);

    }
    else {
        throw new Exception('Il N\'y a rien à effacer');
    }

    header('Location:index.php?p=galerie1');




}


function viewerGalerie($imageId)
{
    $user=new UserManager();
    $view = $user->getUserView($imageId);

    twigRender('galerieViewer.html.twig','view',$view,'','');
}

function viewerGalerie2($imageId)
{
    $user=new UserManager();
    $view = $user->getUserView($imageId);

    twigRender('galerieViewer.html.twig','view',$view,'','');
}

/*function slideShow($imageId)
{
    $folder='users/img/user/'.$_COOKIE['username'].'/';
    $slide= glob($folder.'*.jpg');
    usort($slide, 'strnatcmp');
    $nb=count($slide);

    if(isset($_GET['image']) && ctype_digit($_GET['image']))
    {
        $img=$_GET['image'];
    }
    else
    {
        $img=0;
    }
    if($img>0 && $img<$nb)
    {

    }




}*/



