<?php
declare(strict_types=1);

require_once 'model/UserManager.php';
require_once 'twig.php';
require_once 'functionUpload.php';
require_once 'model/User.php';

use model\UserManager;
use model\User;

function addNewUser(){
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
}

function signUp()
{
    twigRender('signUp.html.twig','','');
}

function home()
{
    $user= new UserManager();

    twigRender('home.html.twig','userData', $userData = $user->userData());
}

function connectUser()
{
    twigRender('connexion.html.twig','','');
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

            switch ($_SESSION['gender']) {
                case 'female':

                    twigRender('ladies.html.twig','userDatum',$_SESSION);
                    break;
                case 'male':
                    //viewBackendTemplate('menPage.php');
                    twigRender('men.html.twig','userDatum',$_SESSION);
                    break;
                case 'other':
                    twigRender('others.html.twig','userDatum',$_SESSION);
                    break;

            }

            //header('Location:index.php?action=verifyUSerDataFilled');


        } else {
            throw new Exception('Mauvais identifiant ou mot de passe !');
        }
    } else {
        throw new Exception('Cet identifiant n\'existe pas !');
    }
}


function disconnectUser()
{
    $user= new UserManager();
    $logOut = $user->disconnect();

   home();
}

function newPage()
{
    //session_start();
    twigRender('page2.html.twig','userDatum',$_SESSION);
}


function UploadPictures()
{
    $messages = [];

    if(!file_exists('users/img/user/'.$_POST['username']))
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
                        $destinationPath = 'users/img/user/' . $_POST['username'] . '/' . sleep(1) . time() . '.' . $extension;


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

        }//twigRender('page2.html.twig', 'message', $messages);

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
                        $destinationPath = 'users/img/user/' . $_POST['username'] . '/' . sleep(1) . time() . '.' . $extension;


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

        }
    }
    resizeImage();
twigRender('success.html.twig','message', $messages);


}

