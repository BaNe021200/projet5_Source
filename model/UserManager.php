<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 14/02/2018
 * Time: 19:39
 */

namespace model;
require_once 'model/Manager.php';
use model\Manager;
use PDO;

class UserManager extends Manager
{
        public function addUser(){

        $hashPwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $JSON= json_encode(["ROLE_USER"]);
        $pdo=$this->dbConnect();//var_dump($pdo);die;
        $pdoStat = $pdo->prepare('INSERT INTO projet5_user VALUES (NULL,:gender, :first_name, :last_name, :username,:birthday,:email,:password, NOW(),:roles)');
        $pdoStat->bindValue(':gender', $_POST['gender'], PDO::PARAM_STR);
        $pdoStat->bindValue(':first_name', $_POST['first_name'], PDO::PARAM_STR);
        $pdoStat->bindValue(':last_name', $_POST['last_name'], PDO::PARAM_STR);
        $pdoStat->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
        $pdoStat->bindValue(':birthday', $_POST['birthday'], PDO::PARAM_STR);
        $pdoStat->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $pdoStat->bindValue(':password', $hashPwd, PDO::PARAM_STR);
        $pdoStat->bindValue(':roles', $JSON, PDO::PARAM_STR);

        $newUser= $pdoStat->execute();
        return $newUser;
        }

        public function getUserMainData(){

        $pdo=$this->dbConnect();
        $pdoStat=$pdo->prepare('SELECT id,gender, first_name, username  FROM projet5_user WHERE username=:username');
        $pdoStat->bindValue(':username',$_POST['username'],PDO::PARAM_STR);
        $userMainData = $pdoStat->execute();
        $userMainData = $pdoStat->fetch();//var_dump($userMainData);die;
        return $userMainData;
        }

        public function userData(){

        $pdo=$this->dbConnect();
        $userData = $pdo->query('SELECT * FROM projet5_user');
        return $userData;
        }

        public function controlUsername()
        {
            $username= $_POST['username'];
            $pdo= $this->dbConnect();
            $pdoStat = $pdo->query("SELECT id FROM projet5_user WHERE username = '$username'  ");
            $veryUsername= $pdoStat->execute();
            $veryUsername= $pdoStat->fetch();
            $getusernameId= $veryUsername['id'];
            return $getusernameId;
        }

        public function getEmail()
        {
            $email= $_POST['email'];
            $pdo= $this->dbConnect();
            $pdoStat = $pdo->query("SELECT id FROM projet5_user WHERE email = '$email'  ");
            $veryEmail= $pdoStat->execute();
            $veryEmail= $pdoStat->fetch();
            $getEmailId= $veryEmail['id'];
            return $getEmailId;
        }

        public function getConnexion()
    {

        $pwd=$_POST['password'];


        $pdo=$this->dbConnect();
        $pdoStat=$pdo->prepare('SELECT id, password,gender  FROM projet5_user WHERE username=:username');
        $pdoStat->bindValue(':username',$_POST['username'],PDO::PARAM_STR);
        $hashedPass = $pdoStat->execute();
        $hashedPass = $pdoStat->fetch();
        $verifyPassword = $hashedPass['password'];
        return $verifyPassword;
    }

        public function getPhoto2Thumb($userId,$photoId)
        {
            $pdo=$this->dbConnect();

            $PdoStat=$pdo->prepare('SELECT * FROM projet5_images WHERE id=:id  AND user_id=:userId ');

            $PdoStat->bindValue(':id',$photoId,PDO::PARAM_STR);
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_STR);

            $userImages=$PdoStat->execute();

            $userImages = $PdoStat->fetchAll();
            return $userImages;
        }

        public function addUserFiles($userId,$img)
        {
            /*$images= glob('users/img/user/'.$_SESSION['username'].'/*.jpg');
            foreach ($images as $avatar){$avatar;};
            $pdo=$this->dbConnect();
            $PdoStat= $pdo->prepare('INSERT INTO projet5_images VALUES(NULL,:userId,:img_name,NULL) ');
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_STR);
            $PdoStat->bindValue(':img_name',$avatar,PDO::PARAM_STR);

            $addImage = $PdoStat->execute();
            return $addImage;*/
            /*$images=glob('users/img/user/'.$_COOKIE['username'].'/*.jpg');

            foreach ($images as $image){
                $path_parts= pathinfo($image);
                $path_parts['dirname'];
                $path_parts['filename'];
                $path_parts['extension'];
            }*/
            $path_parts= pathinfo($img);
            $path_parts['dirname'];
            $path_parts['filename'];
            $path_parts['extension'];
            $pdo=$this->dbConnect();
            $PdoStat= $pdo->prepare('INSERT INTO projet5_images VALUES(NULL,:userId,:dirname, :filename ,:extension) ');
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_STR);
            $PdoStat->bindValue(':dirname',$path_parts['dirname'],PDO::PARAM_STR);
            $PdoStat->bindValue(':filename',$path_parts['filename'],PDO::PARAM_STR);
            $PdoStat->bindValue(':extension',$path_parts['extension'],PDO::PARAM_STR);
            $addImage = $PdoStat->execute();
            $lastId=$pdo->lastInsertId();
            return $lastId;
        }

        /*public function addCropFiles($userId)
        {
            $crop=glob('users/img/user/'.$_COOKIE['username'].'/crop/*.jpg');
            foreach ($crop as $imageCropped){
                $path_parts= pathinfo($imageCropped);
                $path_parts['dirname'];
                $path_parts['filename'];
                $path_parts['extension'];
            }
            $pdo=$this->dbConnect();
            $PdoStat= $pdo->prepare('INSERT INTO projet5_images VALUES(NULL,:userId,:dirname, :filename ,:extension) ');
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_STR);
            $PdoStat->bindValue(':dirname',$path_parts['dirname'],PDO::PARAM_STR);
            $PdoStat->bindValue(':filename',$path_parts['filename'],PDO::PARAM_STR);
            $PdoStat->bindValue(':extension',$path_parts['extension'],PDO::PARAM_STR);
            $addCropedImage = $PdoStat->execute();
            return $addCropedImage;
         }*/
        public function addCropFiles($userId,$img)
        {


            $pdo=$this->dbConnect();
            $PdoStat= $pdo->prepare('INSERT INTO projet5_images VALUES(NULL,:userId,:dirname, :filename ,:extension) ');
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_STR);
            $PdoStat->bindValue(':dirname','users/img/user/'.$_COOKIE['username'].'/crop',PDO::PARAM_STR);
            $PdoStat->bindValue(':filename','img_00'.$img.'-cropped',PDO::PARAM_STR);
            $PdoStat->bindValue(':extension','jpg',PDO::PARAM_STR);
            $addCropedImage = $PdoStat->execute();
            return $addCropedImage;
         }

        public function addCropCenterFiles($userId,$img)
        {


            $pdo=$this->dbConnect();
            $PdoStat= $pdo->prepare('INSERT INTO projet5_images VALUES(NULL,:userId,:dirname, :filename ,:extension) ');
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_STR);
            $PdoStat->bindValue(':dirname','users/img/user/'.$_COOKIE['username'].'/crop',PDO::PARAM_STR);
            $PdoStat->bindValue(':filename','img_00'.$img.'-cropped-center',PDO::PARAM_STR);
            $PdoStat->bindValue(':extension','jpg',PDO::PARAM_STR);
            $addCropedImage = $PdoStat->execute();
            $lastId=$pdo->lastInsertId();
            $PdoStat->closeCursor();
            $PdoStat=$pdo->prepare('SELECT * FROM projet5_images WHERE id=:id AND user_id=:userId');
            $PdoStat->bindValue(':id',$lastId,PDO::PARAM_INT);
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_STR);
            $cropCenterFile=$PdoStat->execute();
            $cropCenterFile=$PdoStat->fetch();

            return $cropCenterFile;

        }

        public function addProfilPicture()
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('INSERT INTO projet5_images VALUES(NULL, :userid, :dirname,:filename ,:extension)');
            $PdoStat->bindValue(':userid',$_COOKIE['ID'],PDO::PARAM_STR);
            $PdoStat->bindValue(':dirname','users/img/user/'.$_COOKIE['username'].'/profilPicture',PDO::PARAM_STR);
            $PdoStat->bindValue(':filename','img-userProfil',PDO::PARAM_STR);
            $PdoStat->bindValue(':extension','jpg',PDO::PARAM_STR);
            $addProfilPicture = $PdoStat->execute();


        }

        public function addThumbnails($userId,$photoId,$dirname,$fileName)
        {
            $pdo=$this->dbConnect();
            $PdosStat=$pdo->prepare('INSERT INTO projet5_thumbnails VALUES (NULL, :userId, :imageId,:thumbnail )');
            $PdosStat->bindValue(':userId',$userId,PDO::PARAM_STR);
            $PdosStat->bindValue(':imageId',$photoId,PDO::PARAM_STR);
            $PdosStat->bindValue(':thumbnail',$dirname.'/thumbnails/'.$fileName.'-thumb.jpg',PDO::PARAM_STR);
            $savedThumnail=$PdosStat->execute();

        }

        public function getThumbnails($userId)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('SELECT * FROM projet5_thumbnails WHERE user_id=:userId ');
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_INT);
            $thumbnails=$PdoStat->execute();
            $thumbnails=$PdoStat->fetchAll();
            return $thumbnails;

        }

        public function getUserFiles($userId)
        {
            /*
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('SELECT id, dirname,filename,extension FROM projet5_images WHERE  user_id=:userId ');
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_STR);
            $userImages=$PdoStat->execute();
            $userImages = $PdoStat->fetchAll();
            return $userImages;
            */


            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('SELECT * FROM projet5_images WHERE  user_id=:userId AND dirname=:dirname');
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_STR);
            $PdoStat->bindValue(':dirname','users/img/user/'.$_COOKIE['username'],PDO::PARAM_STR);
            $userImages=$PdoStat->execute();
            $userImages = $PdoStat->fetchAll();
            return $userImages;
        }

        public function getUserView($photoId)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('SELECT * FROM projet5_images WHERE id=:id');
            $PdoStat->bindValue(':id',$photoId,PDO::PARAM_INT);
            $userView=$PdoStat->execute();
            $userView=$PdoStat->fetch();
            return $userView;
        }

        public function getFrontUserView($photoId,$username)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
            SELECT projet5_images.*, projet5_user.username 
            FROM projet5_images
            INNER JOIN projet5_user
            ON projet5_images.user_id = projet5_user.id
            WHERE projet5_images.id=:id AND username = :username
            ');

            $PdoStat->bindValue(':id',$photoId,PDO::PARAM_INT);
            $PdoStat->bindValue(':username',$username,PDO::PARAM_STR);

            $userView=$PdoStat->execute();
            $userView=$PdoStat->fetch();
            return $userView;
        }




        public function getAllFiles()
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->query('SELECT img_name FROM projet5_images');
            $files = $PdoStat->execute();
            $files = $PdoStat->fetchAll();

            return $files;
        }

        public function deleteImage($userId,$img){

            $pdo=$this->dbConnect();
            $pdoStat= $pdo->prepare('DELETE FROM projet5_images WHERE user_id=:userId AND filename=:filename ');
            $pdoStat->bindValue(':userId', $userId,PDO::PARAM_INT);
            $pdoStat->bindValue(':filename', 'img_00'.$img,PDO::PARAM_STR);
            $deletedImage=$pdoStat->execute();
            $pdoStat->closeCursor();
            $pdoStat= $pdo->prepare('DELETE FROM projet5_images WHERE user_id=:userId AND filename=:filename ');
            $pdoStat->bindValue(':userId', $userId,PDO::PARAM_INT);
            $pdoStat->bindValue(':filename', 'img_00'.$img.'-cropped',PDO::PARAM_STR);
            $deletedImage=$pdoStat->execute();
            $pdoStat->closeCursor();
            $pdoStat= $pdo->prepare('DELETE FROM projet5_images WHERE user_id=:userId AND filename=:filename ');
            $pdoStat->bindValue(':userId', $userId,PDO::PARAM_INT);
            $pdoStat->bindValue(':filename', 'img_00'.$img.'-cropped-center',PDO::PARAM_STR);
            $deletedImage=$pdoStat->execute();
            $pdoStat->closeCursor();
            $pdoStat= $pdo->prepare('DELETE FROM projet5_thumbnails WHERE user_id=:userId AND thumbnail=:thumbnail ');
            $pdoStat->bindValue(':userId', $userId,PDO::PARAM_INT);
            $pdoStat->bindValue(':thumbnail', 'users/img/user/'.$_COOKIE['username'].'/thumbnails/img_00'.$img.'-thumb.jpg',PDO::PARAM_STR);
            $deletedImage=$pdoStat->execute();


        }

        public function deleteImageCroppedCenter($userId,$img)
        {
            $pdo=$this->dbConnect();
            $pdoStat= $pdo->prepare('DELETE FROM projet5_images WHERE id=:id AND user_id=:userId ');
            $pdoStat->bindValue(':id', $img,PDO::PARAM_STR);
            $pdoStat->bindValue(':userId', $userId,PDO::PARAM_INT);

            $deletedImage=$pdoStat->execute();
        }



       /*public function getUserProfilePicture2()
        {

            $pdo=$this->dbConnect();
            $PdoStat=$pdo->query('
            SELECT COUNT(id) AS nbUsers
            FROM projet5_user');
            $data=$PdoStat->execute();
            $data=$PdoStat->fetch();
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
                   echo " $i/";
               }
               else{
                   echo"<a href=\"index.php?p=$i\">$i</a> ";
               }

            }


            $PdoStat->closeCursor();
            $PdoStat=$pdo->query('
            SELECT projet5_images.user_id,filename, projet5_user.id,username,registry_date
            FROM projet5_images
            INNER JOIN projet5_user
            ON projet5_images.user_id = projet5_user.id
            WHERE projet5_images.filename="img-userProfil"
            ORDER BY registry_date DESC LIMIT '.(($currentPage-1)*$perPage). ','.$perPage);

            $username= $PdoStat->execute();
            $username= $PdoStat->fetchAll();

            return $username;
        }*/

         public function getUserProfilePicture()
    {

        $pdo=$this->dbConnect();
        $PdoStat=$pdo->query('
            SELECT COUNT(id) AS nbUsers
            FROM projet5_user');
        $data=$PdoStat->execute();
        $data=$PdoStat->fetch();
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
                //echo " $i/";
            }
            else{
              // echo"<a href=\"index.php?p=$i\">$i</a> ";
            }

        }


        $PdoStat->closeCursor();
        $PdoStat=$pdo->query('
            SELECT projet5_images.user_id,filename, projet5_user.id,username,registry_date
            FROM projet5_images
            INNER JOIN projet5_user
            ON projet5_images.user_id = projet5_user.id   
            WHERE projet5_images.filename="img-userProfil"
            ORDER BY registry_date DESC LIMIT '.(($currentPage-1)*$perPage). ','.$perPage);

        $username= $PdoStat->execute();
        $username= $PdoStat->fetchAll();

        return $username;
    }

        public function getUserProfile($userId)
         {
             $pdo=$this->dbConnect();
             $pdoStat=$pdo->prepare('
             SELECT projet5_images.dirname,filename,extension, projet5_user.id,username
             FROM projet5_images
             INNER JOIN projet5_user
              ON projet5_images.user_id = projet5_user.id
             WHERE user_id = :userId AND filename=:filename');
             $pdoStat->bindValue(':userId',$userId,PDO::PARAM_INT);
             $pdoStat->bindValue(':filename',"img-userProfil",PDO::PARAM_STR);


             $pdoStat->execute();
             $data=$pdoStat->fetch();

             return $data;

         }

         public function frontUsergalerie($userId,$username)
         {
             $pdo=$this->dbConnect();
             $pdoStat=$pdo->prepare('
             SELECT projet5_thumbnails.image_id,thumbnail, projet5_user.id,username
             FROM projet5_thumbnails
             INNER JOIN projet5_user
             ON projet5_thumbnails.user_id = projet5_user.id
             WHERE user_id = :userId AND username=:username');
             $pdoStat->bindValue(':userId',$userId,PDO::PARAM_INT);
             $pdoStat->bindValue(':username',$username,PDO::PARAM_STR);

             $userGalerie = $pdoStat->execute();
             $userGalerie = $pdoStat->fetchAll();

             return $userGalerie;




         }












        public  function deleteUserProfilPicture()
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
            DELETE FROM projet5_images WHERE user_id =:userId AND filename= :filename');
            $PdoStat->bindValue(':userId', $_COOKIE['ID'],PDO::PARAM_INT);
            $PdoStat->bindValue(':filename', 'img-userProfil',PDO::PARAM_STR);
            $deleteUserProfilPicture = $PdoStat->execute();


        }












}