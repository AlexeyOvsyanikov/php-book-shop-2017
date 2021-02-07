<?php
namespace Application\Utils;


class Storage{

    private $storage = [];

    public function __get($name){

        if( isset( $this->storage[$name] ) ){
            return $this->storage[$name];
        }

        return null;

    }

    public function __set($name, $value){

        $this->storage[ $name ] = $value;

    }

    function getRawStorage(){
        return $this->storage;
    }

}