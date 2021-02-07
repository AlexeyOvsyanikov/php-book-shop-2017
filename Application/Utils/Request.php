<?php

namespace Application\Utils;


class Request{

    public function GetGetValue( $key ){

        if( isset($_GET[$key]) ){
            return $_GET[$key];
        }

        return null;
    }

    public function GetPostValue( $key ){

        if( isset($_POST[$key]) ){
            return $_POST[$key];
        }

        return null;
    }

    public function GetPutValue( $key ){

        $params = [];

        parse_str(
            file_get_contents("php://input") ,
            $params
        );

        if( isset($params[$key]) ){
            return $params[$key];
        }
        else {
            return null;
        }

    }

}