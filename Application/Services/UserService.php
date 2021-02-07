<?php

namespace Application\Services;

use Application\Utils\MySQL;
use Bcrypt\Bcrypt;

class UserService
{
    public function addUser($login, $password, $email, $userFirstName, $userLastName, $userMiddleName, $userPhoneNumber, $hesh){

        $isUser = MySQL::$db->prepare("SELECT * FROM users WHERE userLogin = :userLogin OR userEmail=:userEmail");
        $isUser->bindParam(':userLogin', $login,\PDO::PARAM_STR);
        $isUser->bindParam(':userEmail', $email,\PDO::PARAM_STR);
        $isUser->execute();

        $result = $isUser->fetchAll(\PDO::FETCH_OBJ);

        if(!$result){

            $bcrypt = new Bcrypt();
            $bcrypt_version = '2y';
            $heshPassword = $bcrypt->encrypt($password,$bcrypt_version);

            $stm = MySQL::$db->prepare("INSERT INTO users VALUES( DEFAULT, :login, :email , :firstName , :lastName , :middleName , :phoneNumber , NULL , :password , false , :hash )");
            $stm->bindParam(':login' , $login , \PDO::PARAM_STR);
            $stm->bindParam(':email' , $email , \PDO::PARAM_STR);
            $stm->bindParam(':firstName', $userFirstName, \PDO::PARAM_STR);
            $stm->bindParam(':lastName', $userLastName, \PDO::PARAM_STR);
            $stm->bindParam(':middleName', $userMiddleName, \PDO::PARAM_STR);
            $stm->bindParam(':phoneNumber', $userPhoneNumber, \PDO::PARAM_STR);
            $stm->bindParam(':hash' , $hesh , \PDO::PARAM_STR);
            $stm->bindParam(':password' , $heshPassword , \PDO::PARAM_STR);
            $stm->execute();

            return  MySQL::$db->lastInsertId();

        }

        return null;

    }

    public function getUsers($limit = 10, $offset = 0){

        $stm = MySQL::$db->prepare("SELECT * FROM users LIMIT :offset,:limit ");
        $stm->bindParam(':offset' , $offset , \PDO::PARAM_INT);
        $stm->bindParam(':limit' , $limit , \PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_OBJ);

    }

    public function getSingleUser($identifier){

        $stm = MySQL::$db->prepare("SELECT * FROM users WHERE userLogin = :userLogin OR userID=:userID ");

        $stm->bindParam(':userLogin', $identifier,\PDO::PARAM_STR);
        $stm->bindParam(':userID',$identifier ,\PDO::PARAM_STR);

        $stm->execute();

        return $stm->fetch(\PDO::FETCH_OBJ);

    }

    public function verificationUser($token){

        $stm = MySQL::$db->prepare("UPDATE users SET verification = true, token = NULL WHERE token =:token");
        $stm->bindParam('token', $token,\PDO::PARAM_STR);
        $stm->execute();

        return  $stm->fetch(\PDO::FETCH_OBJ);

    }


    public function getCurrentUser(){

        $user = null;

        if( isset($_SESSION[ 'session_user' ])){
            $user = unserialize($_SESSION[ 'session_user' ]);
            $user['session'] = 'yes';
        }
        else if(isset($_COOKIE[ 'cookie_user' ])){
            $user = unserialize($_COOKIE[ 'cookie_user' ]);
        }
        else if( isset($_SESSION['admin'])) {
            $user = unserialize($_SESSION[ 'admin' ]);
        }
        else if( isset($_COOKIE['admin']) ){
            $user = unserialize($_COOKIE[ 'admin' ]);
        }

        return $user;

    }

}