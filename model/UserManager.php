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
        $pdoStat=$pdo->prepare('SELECT id, first_name, username  FROM projet5_user WHERE username=:username');
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



}