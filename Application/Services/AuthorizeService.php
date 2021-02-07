<?php

namespace Application\Services;

use Application\Utils\MySQL;
use Bcrypt\Bcrypt;

class AuthorizeService{

    public function LogIn( $login, $password, $rememberMe){

        $bcrypt = new Bcrypt();
        $rememberMe = filter_var($rememberMe , FILTER_VALIDATE_BOOLEAN);

        $stm = MySQL::$db->prepare( "SELECT userID, isAdmin, userLogin, userEmail, userPassword, verification FROM users WHERE userLogin = :login OR userEmail = :login" );
        $stm->bindParam(':login', $login, \PDO::PARAM_STR);
        $stm->execute();

        $user = $stm->fetch(\PDO::FETCH_OBJ);

        if(!$user){
            return array(
                'code' => 401,
                'user' => $user
            );
        }

        $user->isAdmin = filter_var( $user->isAdmin , FILTER_VALIDATE_INT);

        $userForSessionAndCookies = array(
            'userID' => $user->userID
        );

        $verifyPassword = $bcrypt->verify($password, $user->userPassword);

        if($verifyPassword){

            $isEmailVerified = $user->verification;

            if(!$isEmailVerified){

                $result = array(
                    'code' => 405,
                    'emailVerify' => $isEmailVerified
                );

                return $result;

            }

            if(!$rememberMe){

                if($user->isAdmin !== 1){

                    $_SESSION['session_user'] = serialize($userForSessionAndCookies);

                    unset($_COOKIE['cookie_user']);
                    setcookie("cookie_user", "", 1);

                }
                else{

                    $_SESSION['admin'] = serialize($userForSessionAndCookies);

                    unset($_COOKIE['admin']);
                    setcookie("admin", "", 1);

                }


            }
            else{

                $userSerializeResultForCookie = serialize($userForSessionAndCookies);

                if($user->isAdmin !== 1){

                    setcookie(
                        'cookie_user' ,
                        $userSerializeResultForCookie ,
                        time()+60*60*24*60
                    );

                }
                else{

                    setcookie(
                        'admin' ,
                        $userSerializeResultForCookie ,
                        time()+60*30
                    );

                }

            }

           return array(
               'code' => 200
           );

        }
        else{

            return array(
                'code' => 401,
                'password' => $password
            );

        }

    }

}