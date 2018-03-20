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
        $PdoStat = $pdo->prepare('INSERT INTO projet5_user VALUES (NULL,:gender, :first_name, :last_name, :username,:birthday,:email,:password, NOW(),NULL,NULL,:roles)');
        $PdoStat->bindValue(':gender', $_POST['gender'], PDO::PARAM_STR);
        $PdoStat->bindValue(':first_name', $_POST['first_name'], PDO::PARAM_STR);
        $PdoStat->bindValue(':last_name', $_POST['last_name'], PDO::PARAM_STR);
        $PdoStat->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
        $PdoStat->bindValue(':birthday', $_POST['birthday'], PDO::PARAM_STR);
        $PdoStat->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $PdoStat->bindValue(':password', $hashPwd, PDO::PARAM_STR);
        $PdoStat->bindValue(':roles', $JSON, PDO::PARAM_STR);
        $newUser= $PdoStat->execute();
        $lastId= $pdo->lastInsertId();
        $PdoStat->closeCursor();
        $PdoStat= $pdo->prepare("UPDATE projet5_user SET username = REPLACE(username,'_',' ')
        WHERE username=:username");
        $PdoStat->bindValue(':username',$_POST['username'],PDO::PARAM_STR);
        $newUser=$PdoStat->execute();
        $PdoStat->closeCursor();
        $PdoStat=$pdo->prepare('
        SELECT first_name,email,username
        FROM projet5_user
        WHERE id=:id');
        $PdoStat->bindValue(':id',$lastId,PDO::PARAM_INT);
        $newUser = $PdoStat->execute();
        $newUser = $PdoStat->fetch();

        return $newUser;
        }

        public function getUserMainData(){

        $pdo=$this->dbConnect();
        $PdoStat=$pdo->prepare('SELECT id,gender, first_name, username  FROM projet5_user WHERE username=:username');
        $PdoStat->bindValue(':username',$_POST['username'],PDO::PARAM_STR);
        $userMainData = $PdoStat->execute();
        $userMainData = $PdoStat->fetch();//var_dump($userMainData);die;
        return $userMainData;
        }

        /*public function userData(){

        $pdo=$this->dbConnect();
        $userData = $pdo->query('SELECT * FROM projet5_user');
        return $userData;
        }*/

        /*
         * controlUsername controle dans la bdd si l'username existe déjà
         */
        public function controlUsername()
        {
            $username= $_POST['username'];
            $pdo= $this->dbConnect();
            $PdoStat = $pdo->query("SELECT id FROM projet5_user WHERE username = '$username'  ");
            $veryUsername= $PdoStat->execute();
            $veryUsername= $PdoStat->fetch();
            $getusernameId= $veryUsername['id'];
            return $getusernameId;
        }

        /*
         * getEmail controle dans la bdd si l'email existe déjà
         */
        public function getEmail()
        {
            $email= $_POST['email'];
            $pdo= $this->dbConnect();
            $PdoStat = $pdo->query("SELECT id FROM projet5_user WHERE email = '$email'  ");
            $veryEmail= $PdoStat->execute();
            $veryEmail= $PdoStat->fetch();
            $getEmailId= $veryEmail['id'];
            return $getEmailId;
        }

        public function getConnexion()
    {

        $pwd=$_POST['password'];


        $pdo=$this->dbConnect();
        $PdoStat=$pdo->prepare('SELECT id, password,gender  FROM projet5_user WHERE username=:username');
        $PdoStat->bindValue(':username',$_POST['username'],PDO::PARAM_STR);
        $hashedPass = $PdoStat->execute();
        $hashedPass = $PdoStat->fetch();
        $verifyPassword = $hashedPass['password'];
        return $verifyPassword;
    }

        public function getUserName($id)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
            SELECT id, username
            FROM projet5_user
            WHERE id=:id');
            $PdoStat->bindValue(':id',$id,PDO::PARAM_INT);
            $UserName=$PdoStat->execute();
            $UserName=$PdoStat->fetch();

            return $UserName;
        }

        public function isConnected($id)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
            UPDATE projet5_user
            SET connected =:connected
            WHERE id=:id');
            $PdoStat->bindValue(':id',$id,PDO::PARAM_INT);
            $PdoStat->bindValue(':connected',1,PDO::PARAM_INT);
            $isconnected= $PdoStat->execute();

            return $isconnected;

        }

        public function getConnectedUsers()
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
                SELECT username, connected
                FROM projet5_user
                WHERE connected IS NULL limit 0,6');
            $connected= $PdoStat->execute();
            $connected= $PdoStat->fetchAll();

            return $connected;

        }



        /*
         * function isConnectedSelf($id)
         * rajoute 1 dans le champ connectedSezlf de la BDD
         */
        public function isConnectedSelf($id)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
            UPDATE projet5_user
            SET connected = :connected, connected_self =:connectedSelf
            WHERE id=:id');
            $PdoStat->bindValue(':id',$id,PDO::PARAM_INT);
            $PdoStat->bindValue(':connected',1,PDO::PARAM_INT);
            $PdoStat->bindValue(':connectedSelf',1,PDO::PARAM_INT);
            $isconnectedSelf= $PdoStat->execute();

            return $isconnectedSelf;

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

        public function addUserPicture($userId,$img)
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

        /*
         * pas d'usage connu pour getUserFiles
         */
       /*public function getUserFiles($userId)
{



    $pdo=$this->dbConnect();
    $PdoStat=$pdo->prepare('SELECT * FROM projet5_images WHERE  user_id=:userId AND dirname=:dirname');
    $PdoStat->bindValue(':userId',$userId,PDO::PARAM_STR);
    $PdoStat->bindValue(':dirname','users/img/user/'.$_COOKIE['username'],PDO::PARAM_STR);
    $userImages=$PdoStat->execute();
    $userImages = $PdoStat->fetchAll();
    return $userImages;
}*/

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
            $PdoStat= $pdo->prepare('DELETE FROM projet5_images WHERE user_id=:userId AND filename=:filename ');
            $PdoStat->bindValue(':userId', $userId,PDO::PARAM_INT);
            $PdoStat->bindValue(':filename', 'img_00'.$img,PDO::PARAM_STR);
            $deletedImage=$PdoStat->execute();
            $PdoStat->closeCursor();
            $PdoStat= $pdo->prepare('DELETE FROM projet5_images WHERE user_id=:userId AND filename=:filename ');
            $PdoStat->bindValue(':userId', $userId,PDO::PARAM_INT);
            $PdoStat->bindValue(':filename', 'img_00'.$img.'-cropped',PDO::PARAM_STR);
            $deletedImage=$PdoStat->execute();
            $PdoStat->closeCursor();
            $PdoStat= $pdo->prepare('DELETE FROM projet5_images WHERE user_id=:userId AND filename=:filename ');
            $PdoStat->bindValue(':userId', $userId,PDO::PARAM_INT);
            $PdoStat->bindValue(':filename', 'img_00'.$img.'-cropped-center',PDO::PARAM_STR);
            $deletedImage=$PdoStat->execute();
            $PdoStat->closeCursor();
            $PdoStat= $pdo->prepare('DELETE FROM projet5_thumbnails WHERE user_id=:userId AND thumbnail=:thumbnail ');
            $PdoStat->bindValue(':userId', $userId,PDO::PARAM_INT);
            $PdoStat->bindValue(':thumbnail', 'users/img/user/'.$_COOKIE['username'].'/thumbnails/img_00'.$img.'-thumb.jpg',PDO::PARAM_STR);
            $deletedImage=$PdoStat->execute();


        }

        public function deleteImageCroppedCenter($userId,$img)
        {
            $pdo=$this->dbConnect();
            $PdoStat= $pdo->prepare('DELETE FROM projet5_images WHERE id=:id AND user_id=:userId ');
            $PdoStat->bindValue(':id', $img,PDO::PARAM_STR);
            $PdoStat->bindValue(':userId', $userId,PDO::PARAM_INT);

            $deletedImage=$PdoStat->execute();
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

        public function homeDisplay()
        {

            $pdo = $this->dbConnect();
            $PdoStat = $pdo->query('
            SELECT COUNT(filename) AS nbUsers
            FROM projet5_images WHERE filename="img-userProfil"');
            $data = $PdoStat->execute();
            $data = $PdoStat->fetch();
            return $data;
        }

        public function getUserProfilePicture($currentPage,$perPage)
        {

            $pdo=$this->dbConnect();
            $PdoStat=$pdo->query('
            SELECT projet5_images.user_id,filename, projet5_user.id,username,registry_date,connected
            FROM projet5_images
            INNER JOIN projet5_user
            ON projet5_images.user_id = projet5_user.id
            WHERE projet5_images.filename="img-userProfil"

            AND projet5_user.connected_self IS NULL
            ORDER BY registry_date DESC LIMIT '.(($currentPage-1)*$perPage). ','.$perPage);

            $username= $PdoStat->execute();
            $username= $PdoStat->fetchAll();

            return $username;
    }

        public function getUserProfilePictures()
        {

            $pdo=$this->dbConnect();
            $PdoStat=$pdo->query('
            SELECT projet5_images.user_id,filename, projet5_user.id,username,registry_date,connected
            FROM projet5_images
            INNER JOIN projet5_user
            ON projet5_images.user_id = projet5_user.id
            WHERE projet5_images.filename="img-userProfil"

            AND projet5_user.connected_self IS NULL
            ORDER BY registry_date DESC LIMIT 0,6');

            $username= $PdoStat->execute();
            $username= $PdoStat->fetchAll();

            return $username;
    }

        public function getUserProfile($userId)
         {
             $pdo=$this->dbConnect();
             $PdoStat=$pdo->prepare('
             SELECT projet5_images.dirname,filename,extension, projet5_user.id,username
             FROM projet5_images
             INNER JOIN projet5_user
              ON projet5_images.user_id = projet5_user.id
             WHERE user_id = :userId AND filename=:filename');
             $PdoStat->bindValue(':userId',$userId,PDO::PARAM_INT);
             $PdoStat->bindValue(':filename',"img-userProfil",PDO::PARAM_STR);


             $PdoStat->execute();
             $data=$PdoStat->fetch();

             return $data;

         }

        public function disconnectUser($id){
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
            UPDATE projet5_user
            SET connected=:connected,connected_self =:connectedSelf
            WHERE id=:id');
            $PdoStat->bindValue(':id',$id,PDO::PARAM_INT);
            $PdoStat->bindValue(':connected',null,PDO::PARAM_INT);
            $PdoStat->bindValue(':connectedSelf',null,PDO::PARAM_INT);
            $disconnectUser= $PdoStat->execute();


        }

        public function frontUsergalerie($userId,$username)
         {
             $pdo=$this->dbConnect();
             $PdoStat=$pdo->prepare('
             SELECT projet5_thumbnails.image_id,thumbnail, projet5_user.id,username
             FROM projet5_thumbnails
             INNER JOIN projet5_user
             ON projet5_thumbnails.user_id = projet5_user.id
             WHERE user_id = :userId AND username=:username');
             $PdoStat->bindValue(':userId',$userId,PDO::PARAM_INT);
             $PdoStat->bindValue(':username',$username,PDO::PARAM_STR);

             $userGalerie = $PdoStat->execute();
             $userGalerie = $PdoStat->fetchAll();

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

        public function addUserInfos($userId)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('REPLACE INTO projet5_infosuser VALUES (NULL,:userId,:search,:postal_code,:city,:purpose,:family,:children,:familyAdd,:physic,:speech,:school,:schoolAdd, :business,:buinessAdd)');
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_INT);
            $PdoStat->bindValue(':search',$_POST['search'],PDO::PARAM_STR);
            $PdoStat->bindValue(':postal_code',$_POST['postal_code'],PDO::PARAM_STR);
            $PdoStat->bindValue(':city',$_POST['city'],PDO::PARAM_STR);
            $PdoStat->bindValue(':purpose',$_POST['purpose'],PDO::PARAM_STR);
            $PdoStat->bindValue(':family',$_POST['family_situation'],PDO::PARAM_STR);
            $PdoStat->bindValue(':children',$_POST['children'],PDO::PARAM_STR);
            $PdoStat->bindValue(':familyAdd',$_POST['family_situation_add'],PDO::PARAM_STR);
            $PdoStat->bindValue(':physic',$_POST['physic_add'],PDO::PARAM_STR);
            $PdoStat->bindValue(':speech',$_POST['speech'],PDO::PARAM_STR);
            $PdoStat->bindValue(':school',$_POST['school_level'],PDO::PARAM_STR);
            $PdoStat->bindValue(':schoolAdd',$_POST['school_level_add'],PDO::PARAM_STR);
            $PdoStat->bindValue(':business',$_POST['work'],PDO::PARAM_STR);
            $PdoStat->bindValue(':buinessAdd',$_POST['work_add'],PDO::PARAM_STR);
            $addInfos = $PdoStat->execute();var_dump($addInfos);

            if($addInfos)
            {
                unset($_SESSION['postal_code']);
                unset($_SESSION['city']);
                unset($_SESSION['search']);
                unset($_SESSION['purpose']);
                unset($_SESSION['family_situation']);
                unset($_SESSION['children']);
                unset($_SESSION['family_situation_add']);
                unset($_SESSION['physic_add']);
                unset($_SESSION['speech']);
                unset($_SESSION['school_level']);
                unset($_SESSION['school_level_add']);
                unset($_SESSION['work']);
                unset($_SESSION['work_add']);
}

        }

        public function getUserInfos($userId)
        {

            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
            SELECT *
            FROM projet5_infosuser
            WHERE user_id=:userId');
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_INT);
            $userInfos= $PdoStat->execute();
            $userInfos=$PdoStat->fetchAll();

            return $userInfos;
        }





        public function whoIsOnLine($id,$ip)
        {

            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('INSERT INTO projet5_whosonline VALUES(:id, :time,:ip)
            ON DUPLICATE KEY UPDATE
            online_time = :time , online_id = :id');
            $PdoStat->bindValue(':id',$id,PDO::PARAM_INT);
            $PdoStat->bindValue(':time',time(), PDO::PARAM_INT);
            $PdoStat->bindValue(':ip', $ip, PDO::PARAM_INT);
            $PdoStat->execute();
            $PdoStat->CloseCursor();
            $time_max = time() - (60 * 5);
            $PdoStat=$pdo->prepare('DELETE FROM projet5_whosonline WHERE online_time < :timemax');
            $PdoStat->bindValue(':timemax',$time_max, PDO::PARAM_INT);
            $PdoStat->execute();
            $PdoStat->CloseCursor();

        }

        public function countWhoIsOnLine()
        {
            $count_online = 0;
            //count users
            $pdo=$this->dbConnect();
            $count_visitors=$pdo->query('SELECT COUNT(*) AS nbr_visiteurs FROM projet5_whosonline WHERE online_id = 0')->fetchColumn();
            $count_visitors->CloseCursor();

            //count Users online
            $texte_a_afficher = "<br />Liste des personnes en ligne : ";
            $time_max = time() - (60 * 5);
            $PdoStat=$pdo->prepare('SELECT id, username 
            FROM projet5_whosonline 
            LEFT JOIN projet5_user ON online_id = id
            WHERE online_time > :timemax AND online_id <> 0');
            $PdoStat->bindValue(':timemax',$time_max, PDO::PARAM_INT);
            $PdoStat->execute();
            $count_membres=0;
            while ($data = $PdoStat->fetch())
            {
                $count_membres ++;
                $texte_a_afficher .= '<a href="./voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">
	'.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a> ,';
            }

            $texte_a_afficher = substr($texte_a_afficher, 0, -1);
            $count_online = $count_visitors + $count_membres;
            echo '<p>Il y a '.$count_online.' connectés ('.$count_membres.' membres et '.$count_visitors.' invités)';
            echo $texte_a_afficher.'</p>';
            $PdoStat->CloseCursor();
        }

        public function getTowns()
        {
            $pdo=$this->dbConnect2();
            $PdoStat = $pdo->query('
            SELECT ville_nom_simple
            FROM villes_france_free');
            $towns=$PdoStat->execute();
            $towns=$PdoStat->fetchAll();

            return $towns;



        }












}