<?php
declare(strict_types=1);



require_once 'model/UserManager.php';
require_once 'twig.php';
require_once 'functions.php';
require_once 'model/User.php';
require_once 'resize2.php';
require_once 'crop.php';
//require_once 'listProfils.php';

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
        /*$_POST['email'] = htmlspecialchars($_POST['email']);
        if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email']))
        {
            getLastEmail($_POST['email']);//on vérifie que le meail est bine unique

        }*/

        if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        {
            getLastEmail($_POST['email']);
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

function homeUser()
{
    if (file_exists("users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped-center.jpg")) {
        $src = "users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped-center.jpg";
    } else {
        $src = "users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped.jpg";
    }

    $user= new UserManager();
    $isConnected = $user->getUserName($_COOKIE['ID']);


    if(($isConnected['id']===$_COOKIE['ID']) && ($isConnected['username']===$_COOKIE['username']))
    {
        $user= new UserManager();
        $connectedSelf=$user->isConnectedSelf($_COOKIE['ID']);
    }

    $iNLine=$user->whoIsOnLine($_COOKIE['ID'],$_COOKIE['ip']);



    twigRender('homeUser.html.twig','imageProfil',$src,'','');

}

/*function listProfile()
{
    $user=new UserManager();
    //$userProfileNbx=$user->getUserProfileNbx();

    $data= $user->homeDisplay();
    $nbUsers=$data['nbUsers'];

    $perPage=6;
    $nbPage= ceil($nbUsers/$perPage);var_dump($nbPage);



    if (isset($_GET['p'])&& $_GET['p']>0 && $_GET['p']<=$nbPage)
    {
        $currentPage=$_GET['p'];
    }
    else{
        $currentPage='1';
    }
    if(!isset ($_GET['p'])){

        for($i=1; $i<=$nbPage; $i++){
            $_GET['p']== $i;
        }
    }

    $infos=[

        'currentPage' => $currentPage,
        'perPage'    => $perPage,
        'nbPage'    => $nbPage,

    ];
        var_dump($currentPage);

    $userProfilePictures=$user->getUserProfilePicture($currentPage,$perPage);
    twigRender('frontend/listProfile.html.twig','userdata',$userProfilePictures,'infos',$infos);
}*/

/*function pagination()
{
    $user=new UserManager();
    $data= $user->homeDisplay();
    $nbUsers=$data['nbUsers'];
    foreach ($data as $datum)
    {
        $nbUsers=$datum ;
    }
    $perPage=6;
    $nbPage= ceil($nbUsers/$perPage);



    if (isset($_GET['p'])&& $_GET['p']>0 && $_GET['p']<=$nbPage)
    {
        $currentPage=$_GET['p'];
    }
    else{
        $currentPage='1';
    }


    for ($i=1; $i<=$nbPage; $i++ )
    {
        if($i==$currentPage){
            echo " $i";
        }
        else{
            echo"<a href=\"index.php?p=$i\">$i</a> ";
        }

    }
}*/

function imageProfile()
{    $user = new UserManager();
    if (!file_exists('users/img/user/'.$_COOKIE['username'].'/profilPicture')) {
        newFolderProfilPicture();
        if (file_exists("users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped-center.jpg")) {
            copy("users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped-center.jpg", 'users/img/user/'.$_COOKIE['username'].'/profilPicture/img-userProfil.jpg');
        } else {
            copy("users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped.jpg", 'users/img/user/'.$_COOKIE['username'].'/profilPicture/img-userProfil.jpg');
        }
    }
    else {
        $deleteimageProfile=$user->deleteUserProfilPicture();
        $deleteimageProfile=$user->deleteUserProfilPicture();
        if (file_exists("users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped-center.jpg")) {
            copy("users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped-center.jpg", 'users/img/user/'.$_COOKIE['username'].'/profilPicture/img-userProfil.jpg');
        } else {
            copy("users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped.jpg", 'users/img/user/'.$_COOKIE['username'].'/profilPicture/img-userProfil.jpg');
        }
    }

    $imageProfile = $user->addProfilPicture();

}

function galerie1()
{

    twigRender('galerie1.html.twig','','' ,'','');
}

function infosUser()
{
    $user= new UserManager();
    $getinfos= $user->getUserInfos($_COOKIE['ID']);




    twigRender('infosUser.html.twig','session',$_SESSION,'infos',$getinfos);

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
            $_SESSION['ip'] =$_SERVER['SERVER_ADDR'];

            setcookie("ID", $_SESSION['id'], time() + 3600 * 24 * 365, '', '', false, true);
            setcookie("username", $_SESSION['username'], time() + 3600 * 24 * 365, '', '', false, true);
            setcookie("first_name", $_SESSION['first_name'], time() + 3600 * 24 * 365, '', '', false, true);
            setcookie("ip", $_SESSION['ip'], time() + 3600 * 24 * 365, '', '', false, true);



            header('Location:index.php?p=homeUser');
            //twigRender('homeUserFront.html.twig','','','','');


        } else {
            throw new Exception('Mauvais identifiant ou mot de passe !');
        }
    } else {
        throw new Exception('Cet identifiant n\'existe pas !');
    }
}

function disconnectUser()
{
    $user=new UserManager();
    @$disconnectUser =$user->disconnectUser($_COOKIE['ID']);
    session_destroy();
    setcookie("ID","", time()- 60);
    setcookie("username","", time()- 60);
    setcookie("first_name","", time()- 60);
    setcookie("ip","", time()- 60);



   home();
}

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
                        $uploadimage= $user->addUserPicture($_COOKIE['ID'],$destinationPath);



                        $temporaryPath = $file['tmp_name'];

                        if (move_uploaded_file($temporaryPath, $destinationPath)) {
                            $messages[] = "le fichier a été correctement uploadé";


                        } else {
                            $messages[] = "le fichier  n'a pas été correctement uploadé";

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
        }//twigRender('homeUserFront.html.twig', 'message', $messages);

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
                        $uploadimage= $user->addUserPicture($_COOKIE['ID'],$destinationPath);


                        $temporaryPath = $file['tmp_name'];

                        if (move_uploaded_file($temporaryPath, $destinationPath)) {
                            $messages[] = "le fichier a été correctement uploadé";


                        } else {
                            $messages[] = "le fichier n'a pas été correctement uploadé";

                        }

                    }
                } else {
                    $messages[] = 'type de fichiers non valide';
                }
            } else {

                if($file['error']==2){$messages[]= 'votre fichier est trop volumineux';}
                if($file['error']==1){$messages[]= 'votre fichier excède la taille de configuration du serveur. il doit être impérativement < 1.4Mo';}

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
    @imageProfile();

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
    imageProfile();

}

function croppedChoice($userId,$img){

       $user= new UserManager();
    $src="users/img/user/".$_COOKIE['username']."/crop/img_001-cropped-center.jpg";

    $croppedFile2Delete=$user->deleteImageCroppedCenter($userId,$img);
    if(file_exists($src))
    {

        unlink("users/img/user/".$_COOKIE['username']."/crop/img_001-cropped-center.jpg");

        header('Location: index.php?p=homeUser');
    }
    else
    {
        throw new Exception('Il n\'y a rien effacer');
    }
    imageProfile();


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
    //var_dump($imageId);die;
    $user= new UserManager();
    $imageDeleted=$user->deleteImage($userId,$imageId);
    if($imageId==='1')
    {

        $deleteProfilPicture=$user->deleteUserProfilPicture();
        $folderThumbnails="users/img/user/".$_COOKIE['username'].'/thumbnails/img_00'.$imageId.'-thumb.jpg';
        $folderProfilPicture='users/img/user/'.$_COOKIE['username'].'/profilPicture/img-userProfil.jpg';
        $folderCroppedCenterToDelete = "users/img/user/".$_COOKIE['username'].'/crop/img_00'.$imageId.'-cropped-center.jpg';
        $folderCroppedToDelete = "users/img/user/".$_COOKIE['username'].'/crop/img_00'.$imageId.'-cropped.jpg';
        $folderToDelete = "users/img/user/".$_COOKIE['username'].'/img_00'.$imageId.'.jpg';

        if(file_exists($folderThumbnails)){
            unlink($folderToDelete);
            unlink($folderProfilPicture);
            unlink($folderCroppedToDelete);
            unlink($folderCroppedCenterToDelete);
            unlink($folderThumbnails);


        }
        else {
            throw new Exception('Il N\'y a rien à effacer');
        }
    }else
    {

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
    }

    header('Location:index.php?p=galerie1');
}

function viewerGalerie($imageId)
{
    $user=new UserManager();
    $view = $user->getUserView($imageId);

    twigRender('galerieViewer.html.twig','view',$view,'','');
}

function frontGalerieViewer($imageId,$username)
{
    $user=new UserManager();
    $view = $user->getFrontUserView($imageId,$username);


    twigRender('frontend/frontGalerieViewer.html.twig','view',$view,'','');
}

function viewerGalerie2($imageId)
{
    $user=new UserManager();
    $view = $user->getUserView($imageId);

    twigRender('galerieViewer.html.twig','view',$view,'','');
}

function saveUserinfos($userId)
{


    /*
    $_SESSION['postal_code']= $_POST['postal_code'];
    $_SESSION['city']= $_POST['city'];
    $_SESSION['search']= $_POST['search'];
    $_SESSION['purpose']= $_POST['purpose'];
    $_SESSION['family_situation']= $_POST['family_situation'];
    $_SESSION['children']= $_POST['children'];
    $_SESSION['family_situation_add']= $_POST['family_situation_add'];
    $_SESSION['physic_add']= $_POST['physic_add'];
    $_SESSION['speech']= $_POST['speech'];
    $_SESSION['school_level']= $_POST['school_level'];
    $_SESSION['school_level_add']= $_POST['school_level_add'];
    $_SESSION['work']= $_POST['work'];
    $_SESSION['work_add']= $_POST['work_add'];*/


    $user=new UserManager();
    $userInfos = $user->addUserInfos($userId);var_dump($userInfos);





    header('Location: index.php?p=homeUser');
}

function deleteUserInfos($userId)
{
    $user=new UserManager();
    $deleteInfo=$user->deleteUserInfos($userId);
    twigRender('infosUser.html.twig','','','','');
}

function messages($userId)
{

    $user= new UserManager();
    $getUnreadMessages = $user->getUnreadMessages($userId);
    $getReadMessages = $user->getReadMessages($userId);
    $sentMessages=$user->sentMessages($userId);


    twigRender('messages.html.twig','messages',$getUnreadMessages,'sentMessages',$sentMessages);

}

/**
 * @param $messageId id du message à lire que l'on récupère dans la vue messages.twig
 * @param $userId id de l'utilisateur connecté que l'on récupère grâce au cookie
 * function qui lit les messages
 *
 */
function readUnreadMessages($messageId,$userId)
{
    $user= new UserManager();
   $readUnreadMessage =$user->readUnreadMessages($messageId,$userId);

twigRender('readMessage.html.twig','mailContents',$readUnreadMessage,'','');
}

function readArchivedMessages($messageId,$userId)
{
    $user= new UserManager();
    $readArchivedMessages =$user->readArchivedMessages($messageId,$userId);

    twigRender('readArchivedMessages.html.twig','archivedMessages',$readArchivedMessages,'','');
}

function sentMessages($messageId,$userId)
{
    $user= new UserManager();
    $sentMessages= $user->sentMessage($messageId,$userId);

    twigRender('sentMessages.html.twig','sentMessages',$sentMessages,'','');
}





function deleteMessage($messageId)
{
    $user= new UserManager();
    $deleteMessage =$user->deleteMessage($messageId);

    header('Location:index.php?p=messages');

}

function sendMessageToWebmaster($expeditor,$receiver)
{
    $user= New UserManager();
    $sendMessage = $user->sendMail($expeditor, $receiver);
    $data= $user->getUserProfile($receiver);

    if($sendMessage)
    {
        $Session = new Session();
        $Session->setFlash('votre message est envoyé','success');
        $Session->flash();

    }
    //header('Location:index.php?p=homeUser');
    mail('mail@site.com',"'nouveau message de'.$expeditor","$expeditor.'vous a envoyeé un mùessage'");
    twigRender('homeUser.html.twig','','','data',$data);



}









function eraseUser($userId)
{
    $folderThumbnails="users/img/user/".$_COOKIE['username'].'/thumbnails/*.jpg';
    $dirThumbnails="users/img/user/".$_COOKIE['username'].'/thumbnails';


    $folderProfilPicture='users/img/user/'.$_COOKIE['username'].'/profilPicture/img-userProfil.jpg';
    $dirProfilPicture='users/img/user/'.$_COOKIE['username'].'/profilPicture';

    $folderCroppedCenterToDelete = "users/img/user/".$_COOKIE['username'].'/crop/img_001-cropped-center.jpg';
    $dirCroppedCenterToDelete = "users/img/user/".$_COOKIE['username'].'/crop';

    $foldersCroppedToDelete = glob("users/img/user/".$_COOKIE['username'].'/crop/*.jpg');


    $folderToDelete = glob("users/img/user/".$_COOKIE['username'].'/*.jpg');
    $dirToDelete = "users/img/user/".$_COOKIE['username'];


    $folderThumbnails= glob('users/img/user/'.$_COOKIE['username'].'/thumbnails/*.jpg');
    if(file_exists($dirThumbnails))
    {

        foreach ($folderThumbnails as $folderThumbnail)
        {
            unlink($folderThumbnail);
        }

        unlink($folderProfilPicture);


        foreach ($foldersCroppedToDelete as $folderCroppedToDelete )
        {
            unlink($folderCroppedToDelete);
        }

        foreach ($folderToDelete as $fileToDelete)
        {
            unlink($fileToDelete);
        }
        rmdir($dirThumbnails);
        rmdir($dirProfilPicture);
        rmdir($dirCroppedCenterToDelete);
        rmdir($dirToDelete);
    }


    $user= new UserManager();

    $getmail=$user->getMail($userId);

    mail($getmail['email'],'Confirmation de désinscription','Au revoir,'. $_COOKIE['first_name'].', votre compte est bien détruit.' );
    disconnectUser();
    $message= [];
    $eraseUser=$user->eraseUser($userId);


    if($eraseUser)
    {
        $message[]='Votre profil est détruit...Au revoir !';
    }
    else
    {
        $message[]='votre profil fait de la résistance et ne peut-être détruit pour le moment';
    }
    twigRender('frontend/eraseUser.html.twig','message',$message,'','');

}





function archiveMessages($messageId,$userId)
{
    $user= new UserManager();
    $archiveMessages = $user->archiveMessages($messageId,$userId);

    header('Location:index.php?p=messages');
}







function writeMessage($userId)
{
    twigRender('writeMessage.html.twig','','','','');
}

