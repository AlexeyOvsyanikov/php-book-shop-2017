<?php
namespace Application\Services;


use Application\Controllers\BaseController;
use Application\Utils\MySQL;

use Bcrypt\Bcrypt;

class PersonalPageService  {

    public function GetUserData( $params = [] ){

        $userID = +$params['userID'];
        
        $userStm = MySQL::$db->prepare("SELECT userLogin, userEmail, firstName, lastName, middleName, phoneNumber FROM users WHERE userID = :userID");
        $userStm->bindParam('userID', $userID, \PDO::PARAM_INT);
        $userStm->execute();

        $userData = $userStm->fetch(\PDO::FETCH_OBJ);

        $userAvatarStm = MySQL::$db->prepare("SELECT userImagePath FROM useravatar WHERE userID = :userID");
        $userAvatarStm->bindParam('userID', $userID, \PDO::PARAM_INT);
        $userAvatarStm->execute();

        $userAvatar = $userAvatarStm->fetch(\PDO::FETCH_OBJ);

        $user = array(

            'userLogin' => $userData->userLogin,
            'userEmail' => $userData->userEmail,
            'userFirstName' => $userData->firstName,
            'userLastName' => $userData->lastName,
            'userMiddleName' => $userData->middleName,
            'userPhoneNumber' => $userData->phoneNumber

        );

        if($userAvatar){
            $user['userAvatar'] = $userAvatar->userImagePath;
        }

        return $user;

    }

    public function ChangeUserAvatar( $params = [] ){

        $userID = +$params['userID'];

        if( isset( $_FILES['avatarFile'] ) ){

            $userAvatarDirectory = "images/avatars/{$userID}/*";

            if(!file_exists("images/avatars/{$userID}")){

                mkdir("images/avatars/{$userID}");

            }

            $files = glob($userAvatarDirectory);

            foreach($files as $file){

                if(is_file($file)){
                    unlink($file);
                }

            }

            $fileExtension = strrchr($_FILES['avatarFile']['name'], ".");

            $time = time();

            $fileName = "Avatar_{$time}{$fileExtension}";

            $userAvatarDirectoryPath = "images/avatars/{$userID}/{$fileName}";

            $checkUserAvatarStm = MySQL::$db->prepare("SELECT userImagePath FROM useravatar WHERE userID = :userID");
            $checkUserAvatarStm->bindParam('userID', $userID, \PDO::PARAM_INT);
            $checkUserAvatarStm->execute();

            $userAvatar = $checkUserAvatarStm->fetch(\PDO::FETCH_OBJ);

            if($userAvatar){

                if( !move_uploaded_file($_FILES['avatarFile']['tmp_name'] , $userAvatarDirectoryPath) ){

                    throw new \Exception('File upload error!');

                }

                $stm = MySQL::$db->prepare("UPDATE useravatar SET  userImagePath = :newUserAvatar WHERE userID = :userID");
                $stm->bindParam('newUserAvatar', $userAvatarDirectoryPath, \PDO::PARAM_STR);
                $stm->bindParam('userID', $userID, \PDO::PARAM_INT);
                $result = $stm->execute();

                return [
                    'status' => $result,
                    'path' =>$userAvatarDirectoryPath
                ];

            }
            else{

                if( !file_exists("images/avatars") ){
                    mkdir("images/avatars");
                }

                mkdir("images/avatars/{$userID}");

                if( !move_uploaded_file($_FILES['avatarFile']['tmp_name'] , $userAvatarDirectoryPath) ){
                    throw new \Exception('File upload error!');
                }

                $stm = MySQL::$db->prepare("INSERT INTO useravatar VALUES ( DEFAULT , :userID , :userAvatarPath )");
                $stm->bindParam('userID', $userID , \PDO::PARAM_INT);
                $stm->bindParam('userAvatarPath', $userAvatarDirectoryPath , \PDO::PARAM_STR);
                $result = $stm->execute();

                if( $result === false ){

                    $exception = new \stdClass();
                    $exception->errorCode = MySQL::$db->errorCode ();
                    $exception->errorInfo = MySQL::$db->errorInfo ();

                    return $exception;

                }

            }

        }

    }

    public function UpdateUserPersonalData( $params = [] ){

        $userID = +$params['userID'];
        $userEmail = $params['userEmail'];
        $userPhoneNumber = $params['userPhone'];
        $userLastName = $params['userLastName'];
        $userFirstName = $params['userFirstName'];
        $userMiddleName = $params['userMiddleName'];

        $stm = MySQL::$db->prepare("UPDATE users SET userEmail = :userEmail, firstName = :userFirstName, lastName = :userLastName, middleName = :userMiddleName, phoneNumber = :userPhoneNumber WHERE userID = :userID");
        $stm->bindParam('userEmail', $userEmail, \PDO::PARAM_STR);
        $stm->bindParam('userFirstName', $userFirstName, \PDO::PARAM_STR);
        $stm->bindParam('userLastName', $userLastName, \PDO::PARAM_STR);
        $stm->bindParam('userMiddleName', $userMiddleName, \PDO::PARAM_STR);
        $stm->bindParam('userPhoneNumber', $userPhoneNumber, \PDO::PARAM_STR);
        $stm->bindParam('userID', $userID, \PDO::PARAM_INT);

        $result = $stm->execute();

        if($result){
            return array( 'code' => 200 );
        }
        else {
            return array( 'code' => 400 );
        }

    }

    public function UpdateUserPassword( $params = [] ){

        $userID = intval($params['userID']);
        $oldPassword = $params['oldPassword'];
        $newPassword = $params['newPassword'];
        $confirmNewPassword = $params['confirmNewPassword'];

        $passwordPattern = '/^[a-z_?!^%()\d]{6,30}$/iu';

        if( !preg_match( $passwordPattern, $oldPassword ) ){
            return array( 'code' => 600);
        }

        if( !preg_match( $passwordPattern, $newPassword ) ){
            return array( 'code' => 601);
        }

        if( !preg_match( $passwordPattern, $confirmNewPassword ) ){
            return array( 'code' => 602);
        }

        if($newPassword !== $confirmNewPassword){
            return array( 'code' => 603);
        }

        $userStm = MySQL::$db->prepare("SELECT * FROM users WHERE userID = :userID");
        $userStm->bindParam('userID', $userID, \PDO::PARAM_INT);
        $userResult = $userStm->execute();

        if($userResult){

            $bcrypt = new Bcrypt();
            $bcrypt_version = '2y';

            $encodedNewPassword = $bcrypt->encrypt($newPassword, $bcrypt_version);

            $passwordStm = MySQL::$db->prepare("UPDATE users SET userPassword = :newPassword WHERE userID = :userID");
            $passwordStm->bindParam('newPassword', $encodedNewPassword, \PDO::PARAM_STR);
            $passwordStm->bindParam('userID', $userID, \PDO::PARAM_INT);
            $passwordResult = $passwordStm->execute();

            if($passwordResult){
                return array( 'code' => 200 );
            }
            else {
                return array( 'code' => 500 );
            }

        }
        else{
            return array('code' => 605);
        }

    }

}