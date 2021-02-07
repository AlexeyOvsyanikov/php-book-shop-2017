<?php

namespace Application\Controllers;

use Application\Services\UserService;
use Application\Controllers\patternConst;
use Application\Controllers\messageConst;

use Bcrypt\Bcrypt;

class UserController extends BaseController{

    public function registration(){
        try{
            $template = $this->twig->load( 'User/registration.twig');
            echo $template->render( );
        }
        catch (\Exception $ex) {

            echo "<pre>";
            print_r($ex);
            echo "<pre>";

            include '../Application/Views/Errors/InternalError.php';

        }

    }

    public function addUser( ){

        $pattern = new patternConst();

        $userLogin = $this->request->GetPostValue('userLogin');
        if(!preg_match($pattern->LoginPattern,$userLogin)){
            $this->json(400,array(
                'code'=> 400,
                'message' => 'неверный логин'
            ));
            return;
        }

        $userEmail = $this->request->GetPostValue('userEmail');
        if(!preg_match($pattern->EmailPattern,$userEmail)){
            $this->json(400,array(
                'code'=> 400,
                'message' => 'неверный Email'
            ));
            return;
        }

        $userFirstName = $this->request->GetPostValue('firstName');
        if(!preg_match($pattern->NamesPattern, $userFirstName)){
            $this->json(400,array(
                'code'=> 400,
                'message' => 'Не корректное Имя'
            ));
            return;
        }

        $userLastName = $this->request->GetPostValue('lastName');
        if(!preg_match($pattern->NamesPattern, $userLastName)){
            $this->json(400,array(
                'code'=> 400,
                'message' => 'Не корректная Фамилия'
            ));
            return;
        }

        $userMiddleName = $this->request->GetPostValue('middleName');
        if(!preg_match($pattern->NamesPattern, $userMiddleName)){
            $this->json(400,array(
                'code'=> 400,
                'message' => 'Не корректное Отчество'
            ));
            return;
        }

        $userPhoneNumber = $this->request->GetPostValue('phoneNumber');
        if(!preg_match($pattern->PhoneNumberPattern, $userPhoneNumber)){
            $this->json(400,array(
                'code'=> 400,
                'message' => 'Не корректный номер телефона'
            ));
            return;
        }

        $usrPassword = $this->request->GetPostValue('userPassword');
        if(!preg_match($pattern->PasswordPattern,$usrPassword)){
            $this->json(400,array(
                'code'=> 400,
                'message'=> 'неверный пароль'
            ));
            return;
        }

        $bcrypt = new Bcrypt();
        $bcrypt_version = '2y';
        $heshToken = $bcrypt->encrypt($userEmail,$bcrypt_version);

        $userService = new UserService();

        $result = $userService->addUser( $userLogin, $usrPassword, $userEmail, $userFirstName, $userLastName, $userMiddleName, $userPhoneNumber, $heshToken );

        if($result !== null){

            $message = new messageConst();

            $message->tuneTemplate($userLogin,$heshToken);
            $mailres = mail($userEmail , $message->verificationSubject,$message->verificationTemplate,$message->header);

            $this->json(200, array(
                'code' => 200
            ));

        }
        else{

            $this->json(403,array(
                'code'=> 403,
                'message' => 'Пользователь с такими данными уже есть!'
            ));

        }


     }

    public function getUsers (){

        $userService = new UserService();

        $users = $userService->getUsers();

        $template = $this->twig->load('User/users.twig');

        echo $template->render( array(
            'users' => $users
        ) );
    }

    public function getSingleUser($identifier){
        $userService = new UserService();
        $user = $userService->getSingleUser($identifier);

        $template = $this->twig->load('User/singleUser.twig');

        echo $template->render( array(
            'user' => $user
        ) );

    }

    public function verificationUser(){

        $userService = new UserService();

        $token = $this->request->GetGetValue('token');

        $userVer = $userService->verificationUser($token);

        if($userVer !==0){
            $template = $this->twig->load('User/users.twig');

            echo $template->render( );
        }
        else{
            $template = $this->twig->load('ErrorPages/Error.twig');

            echo $template->render( );
        }


    }

}