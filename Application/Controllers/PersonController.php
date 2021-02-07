<?php

namespace Application\Controllers;

use Application\Services\PersonalPageService;

class PersonController extends BaseController{

    public function getPersonAction(){

        $template = $this->twig->load('public/Person/person.twig');

        try {

            $personalPageService = new PersonalPageService();


            if($this->currentUser !== null){
                //получаем данные о пользователе
                $user = $personalPageService->GetUserData(
                    [
                        'userID' => $this->currentUser['userID']
                    ]
                );
            }
            else{
                $user = null;
            }

            echo $template->render(
                array(
                    'userStorage' => $this->currentUser ? $this->currentUser['userID'] : null,
                    'user' => $user
                )
            );

        }
        catch (\Exception $ex){

            echo "<pre>";
            print_r($ex);
            echo "<pre>";

            include '../Application/Views/Errors/InternalError.php';

        }

    }

    public function EditPersonDataAction(){

        $template = $this->twig->load('public/Person/edit-person-data.twig');

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

    public function ChangePasswordAction(){

        $template = $this->twig->load('public/Person/change-person-password.twig');

        try{

            if( isset($_COOKIE["cookie_user"]) ){

                $user = unserialize($_COOKIE["cookie_user"]);

            }
            else{

                $user = unserialize($_SESSION["session_user"]);

            }

            echo $template->render( array( 'user' => $user ) );

        }
        catch(\Exception $ex){

            echo "<pre>";
            print_r($ex);
            echo "<pre>";

            include '../Application/Views/Errors/InternalError.php';

        }

    }

}