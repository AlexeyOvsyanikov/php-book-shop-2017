<?php
namespace Application\Services;


use Application\Utils\MySQL;

class GenresService
{
    public function GetGenres($limit = 10 , $offset = 0){

        $stm = MySQL::$db->prepare("SELECT * FROM genres LIMIT :offset, :limit");
        $stm->bindParam(':offset' , $offset , \PDO::PARAM_INT);
        $stm->bindParam(':limit' , $limit , \PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_OBJ);
    }

    public function GetGenreBooksAmount($id){

        $stm = MySQL::$db->prepare("SELECT COUNT(*) as amount FROM booksgenres WHERE genreID=:id");
        $stm->bindParam(':id' , $id , \PDO::PARAM_INT);
        $stm->execute();

        $resultObject = $stm->fetch(\PDO::FETCH_OBJ);

        return  $resultObject->amount;

    }

    public function GetGenreByID( $id ){


        $stm = MySQL::$db->prepare("SELECT * FROM genres WHERE genreID = :id");
        $stm->bindParam(':id' , $id , \PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetch(\PDO::FETCH_OBJ);

    }

    public function UpdateGenreByID( $id, $title ){

        $stm = MySQL::$db->prepare("UPDATE genres SET genreTitle=:title  WHERE genreID=:id");
        $stm->bindParam(':title' , $title , \PDO::PARAM_STR);
        $stm->bindParam(':id' , $id , \PDO::PARAM_INT);
        $result= $stm->execute();



        return  $result;

    }

    public function DeleteGenreByID( $id ){
        $stm = MySQL::$db->prepare("DELETE FROM genres WHERE genreID = :id");
        $stm->bindParam(':id' , $id , \PDO::PARAM_INT);
        $result = $stm->execute();

        return $result;


    }

    public function AddGenre( $name ){

        $stm = MySQL::$db->prepare("INSERT INTO genres VALUES( DEFAULT  , :genreTitle )");
        $stm->bindParam(':genreTitle' , $name , \PDO::PARAM_STR);
        $stm->execute();

        return  MySQL::$db->lastInsertId();


    }

}