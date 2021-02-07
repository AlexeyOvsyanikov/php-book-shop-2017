<?php

namespace Application\Controllers;

use Application\Services\PersonalPageService;

class PersonalPageController extends BaseController {

    public function personalPageAction(){

        $template = $this->twig->load('PersonalPage/personal-page.twig');

        try{

            if( isset($_COOKIE["cookie_user"]) ){

                $userStorage = unserialize($_COOKIE["cookie_user"]);

            }
            else{

                $userStorage = unserialize($_SESSION["session_user"]);

            }

            $personalPageService = new PersonalPageService();

            $user = $personalPageService->GetUserData( [ 'userID' => $userStorage['userID'] ] );

            echo $template->render( array( 'userStorage' => $userStorage, 'user' => $user ) );

        }
        catch (\Exception $ex){

            echo "<pre>";
            print_r($ex);
            echo "<pre>";

            include '../Application/Views/Errors/InternalError.php';

        }

    }

    public function ChangeUserAvatar(){

        $personalPageService = new PersonalPageService();

        try{

            if( isset($_COOKIE["cookie_user"])){
                $CookieUser = unserialize($_COOKIE["cookie_user"]);
            }
            else if ( isset($_SESSION['session_user']) ){
              $CookieUser = unserialize($_SESSION['session_user']);
            } if
            else {
                $CookieUser = null;
            }

            if(!$CookieUser){

                $this->json( 200 , array(
                    'code' => 401
                ) );

            }

            $userID = $CookieUser['userID'];

            $result = $personalPageService->ChangeUserAvatar(
                [
                    'userID' => $userID
                ]
            );

            if($result['status']){

                $this->json( 200 , array(
                    'code' => 200,
                    'path' => $result['path']
                ) );

            }
            else{
                $this->json( 500 , array(
                    'code' => 500,
                    'path' => null
                ) );
            }

        }
        catch(\Exception $ex){

            $this->json( 500 , array(
                'code' => 500,
                'avatarException' => $ex
            ) );

        }

    }

    public function EditPersonalDataAction(){

        $template = $this->twig->load('PersonalPage/edit-personal-data.twig');

        try{

            if( isset($_COOKIE["cookie_user"]) ){

                $userStorage = unserialize($_COOKIE["cookie_user"]);

            }
            else{

                $userStorage = unserialize($_SESSION["session_user"]);

            }

            $personalPageService = new PersonalPageService();

            $user = $personalPageService->GetUserData( [ 'userID' => $userStorage['userID'] ] );
            $user->userID = $userStorage['userID'];

            echo $template->render( array( 'userStorage' => $userStorage, 'user' => $user ) );

        }
        catch (\Exception $ex){

            echo "<pre>";
            print_r($ex);
            echo "<pre>";

            include '../Application/Views/Errors/InternalError.php';

        }

    }

    public function SaveNewPersonalData(){

        if( isset($_COOKIE["cookie_user"]) ){

            $userStorage = unserialize($_COOKIE["cookie_user"]);

        }
        else{

            $userStorage = unserialize($_SESSION["session_user"]);

        }

        $userID = $userStorage['userID'];
        $userEmail = $this->request->GetPutValue('newEmail');
        $userPhone = $this->request->GetPutValue('newPhoneNumber');
        $userLastName = $this->request->GetPutValue('newLastName');
        $userFirstName = $this->request->GetPutValue('newFirstName');
        $userMiddleName = $this->request->GetPutValue('newMiddleName');

        $personalPageService = new PersonalPageService();

        try{

           $result = $personalPageService->UpdateUserPersonalData(
               [   'userID' => $userID ,
                   'userEmail' => $userEmail,
                   'userPhone' => $userPhone,
                   'userLastName' => $userLastName,
                   'userFirstName' => $userFirstName,
                   'userMiddleName' => $userMiddleName
               ]);

            $this->json( $result['code'],
                array(
                'code' => $result['code'],
                'message' =>
                    $result['code'] === 200 ? 'Данные успешно обновлены!'
                                            : 'Пользователь с такими данными уже есть!'
            ) );

        }
        catch(\Exception $ex){

            $this->json( 500 , array(
                'code' => 500,
                'avatarException' => $ex
            ) );

        }

    }

    public function ChangePasswordAction(){

        $template = $this->twig->load('PersonalPage/change-password.twig');

        try{

            if( isset($_COOKIE["cookie_user"]) ){

                $CookieUser = unserialize($_COOKIE["cookie_user"]);
                echo $template->render( array( 'user' => $CookieUser ) );

            }
            else{

                echo $template->render( array( 'user' => $_SESSION["session_user"] ) );

            }

        }
        catch(\Exception $ex){

            echo "<pre>";
            print_r($ex);
            echo "<pre>";

            include '../Application/Views/Errors/InternalError.php';

        }

    }

    public function ChangePassword(){

        if( isset($_COOKIE["cookie_user"]) ){

            $user = unserialize($_COOKIE["cookie_user"]);

        }
        else if( isset($_SESSION["session_user"]) ) {

            $user = unserialize($_SESSION["session_user"]);

        } if
        else{
            $user = null;
        }

        if( !$user ){

            $this->json( 401 ,
                array(
                    'code' => 401
                ) );

        }

        $userID = $user['userID'];

        $oldPassword = $this->request->GetPutValue('oldPassword');
        $newPassword = $this->request->GetPutValue('newPassword');
        $confirmNewPassword = $this->request->GetPutValue('confirmNewPassword');

        $personalPageService = new PersonalPageService();

        try{

            $result = $personalPageService->UpdateUserPassword( [

                    'userID' => $userID,
                    'oldPassword' => $oldPassword,
                    'newPassword' => $newPassword,
                    'confirmNewPassword' => $confirmNewPassword

                ]);

            $this->json( $result['code'],
                array(
                    'code' => $result['code']
                ) );

        }
        catch(\Exception $ex){

            $this->json( 500 , array(
                'code' => 500,
                'avatarException' => $ex
            ) );

        }

    }

}