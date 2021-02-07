<?php

namespace Application\Services;

use Application\Utils\MySQL;

class AuthorService{

    public function GetAuthors( $limit = 10 , $offset = 0 ){

        $stm = MySQL::$db->prepare("SELECT * FROM authors LIMIT :offset, :limit");
        $stm->bindParam(':offset' , $offset , \PDO::PARAM_INT);
        $stm->bindParam(':limit' , $limit , \PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_OBJ);

    }

    public function AddAuthor( $firstName , $lastName ){

        $stm = MySQL::$db->prepare("INSERT INTO authors VALUES( DEFAULT  , :authorFirstName , :authorLastName)");
        $stm->bindParam(':authorFirstName' , $firstName , \PDO::PARAM_STR);
        $stm->bindParam(':authorLastName' , $lastName , \PDO::PARAM_STR);
        $stm->execute();

        return  MySQL::$db->lastInsertId();


    }

    public function GetAuthorByID( $id ){


        $stm = MySQL::$db->prepare("SELECT * FROM authors WHERE authorID = :id");
        $stm->bindParam(':id' , $id , \PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetch(\PDO::FETCH_OBJ);

    }

    public function DeleteAuthorByID( $id ){

        $stm = MySQL::$db->prepare("DELETE FROM authors WHERE authorID = :id");
        $stm->bindParam(':id' , $id , \PDO::PARAM_INT);
        $result = $stm->execute();

        return $result;

    }


    public function UpdateAuthorByID( $id, $firstName , $lastName ){


        $stm = MySQL::$db->prepare("UPDATE authors 
                                    SET authorFirstName= :authorFirstName, authorLastName=:authorLastName 
                                    WHERE authorID=:id");
        $stm->bindParam(':authorFirstName' , $firstName , \PDO::PARAM_STR);
        $stm->bindParam(':authorLastName' , $lastName , \PDO::PARAM_STR);
        $stm->bindParam(':id' , $id , \PDO::PARAM_INT);
        $result = $stm->execute();

        return $result;


    }

}