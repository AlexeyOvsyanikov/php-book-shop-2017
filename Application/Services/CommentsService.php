<?php

namespace Application\Services;

use Application\Utils\MySQL;
class CommentsService
{

    public function GetCommentsList($limit = 10 , $offset = 0){

        $stm = MySQL::$db->prepare("
                SELECT comment.* , users.userLogin , book.bookTitle FROM comments as comment
                LEFT JOIN users 
                ON users.userID = comment.userID
                LEFT JOIN books book 
                ON book.bookID = comment.bookID
                ORDER BY `comment`.`created` 
                DESC 
                LIMIT :offset, :limit
        ");
        $stm->bindParam(':offset' , $offset , \PDO::PARAM_INT);
        $stm->bindParam(':limit' , $limit , \PDO::PARAM_INT);
        $stm->execute();

        $books = $stm->fetchAll(\PDO::FETCH_OBJ);

        foreach ( $books as  &$book){

            $book->created = date('l F Y H:i' , $book->created);

        }

        return $books;

    }

    public function GetCommentsByBookId($id, $limit = 10 , $offset = 0){

        $stm = MySQL::$db->prepare("SELECT * FROM comments WHERE bookID=:id AND statusID = 2 ORDER BY `comments`.`created` DESC LIMIT :offset, :limit");
        $stm->bindParam(':offset' , $offset , \PDO::PARAM_INT);
        $stm->bindParam(':limit' , $limit , \PDO::PARAM_INT);
        $stm->bindParam(':id' , $id , \PDO::PARAM_INT);
        $stm->execute();

        $comments = $stm->fetchAll(\PDO::FETCH_OBJ);

        foreach ( $comments as &$comment ){

            $comment->created = date('l F Y H:i' , $comment->created);

            $stm = MySQL::$db->prepare("SELECT userID,userLogin FROM users WHERE userID = :id");
            $stm->bindParam(':id' , $comment->userID , \PDO::PARAM_INT);
            $stm->execute();

            $comment->author = $stm->fetch(\PDO::FETCH_OBJ);

        }

        return $comments;

    }

    public function GetAmountCommentsByBookId($id){

        $stm = MySQL::$db->prepare("SELECT COUNT(*) as amount FROM comments WHERE bookID=:id AND statusID = 2");

        $stm->bindParam(':id' , $id , \PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetch(\PDO::FETCH_OBJ);
    }

    public function GetCommentByStatusId($id = 1, $limit = 2 , $offset = 0){

        $stm = MySQL::$db->prepare("SELECT * FROM comments WHERE statusID=:id ORDER BY `comments`.`created` ASC LIMIT :offset, :limit");
        $stm->bindParam(':offset' , $offset , \PDO::PARAM_INT);
        $stm->bindParam(':limit' , $limit , \PDO::PARAM_INT);
        $stm->bindParam(':id' , $id , \PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_OBJ);
    }

    public function GetCommentById($id){

        $stm = MySQL::$db->prepare("SELECT * FROM comments WHERE commentID=:id");
        $stm->bindParam(':id' , $id , \PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetch(\PDO::FETCH_OBJ);
    }

    public function GetStatuses(){

        $stm = MySQL::$db->prepare("SELECT * FROM statuses");

        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_OBJ);
    }

    public function GetBookTitle($id){

        $stm = MySQL::$db->prepare("SELECT * FROM books WHERE bookID=:id");
        $stm->bindParam(':id' , $id , \PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetch(\PDO::FETCH_OBJ);
    }

    public function GetUser($id){

        $stm = MySQL::$db->prepare("SELECT * FROM users WHERE userID=:id");
        $stm->bindParam(':id' , $id , \PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetch(\PDO::FETCH_OBJ);
    }

    public function AddComment( $text, $bookId, $userId, $time  ){

        $status = 1;
        $updete = 0;

        $stm = MySQL::$db->prepare("INSERT INTO comments VALUES( DEFAULT  , :userId, :bookId, :text, :status, :create, :updete)");
        $stm->bindParam(':userId' , $userId , \PDO::PARAM_INT);
        $stm->bindParam(':bookId' , $bookId , \PDO::PARAM_INT);
        $stm->bindParam(':text' , $text , \PDO::PARAM_STR);
        $stm->bindParam(':status' , $status , \PDO::PARAM_INT);
        $stm->bindParam(':create' , $time , \PDO::PARAM_INT);
        $stm->bindParam(':updete' , $updete , \PDO::PARAM_INT);
        $stm->execute();

        return  MySQL::$db->lastInsertId();


    }

    public function DeleteCommentByID( $id ){

        $stm = MySQL::$db->prepare("DELETE FROM comments WHERE commentID = :id");
        $stm->bindParam(':id' , $id , \PDO::PARAM_INT);
        $result = $stm->execute();

        return $result;

    }

    public function UpdateCommentByID($commentID, $commentText, $time , $statusID){


        $stm = MySQL::$db->prepare("UPDATE comments 
                                    SET commentText= :commentText, updated= :time , statusID = :statusID
                                    WHERE commentID=:id");

        $stm->bindParam(':commentText' , $commentText , \PDO::PARAM_STR);
        $stm->bindParam(':id' , $commentID , \PDO::PARAM_INT);
        $stm->bindParam(':statusID' , $statusID , \PDO::PARAM_INT);
        $stm->bindParam(':time' , $time , \PDO::PARAM_INT);

        $result = $stm->execute();

        return $result;


    }

    public function UpdateCommentStatusByID($commentID, $commentStatus, $time){


        $stm = MySQL::$db->prepare("UPDATE comments 
                                    SET statusID= :commentStatus, updated= :time
                                    WHERE commentID=:id");
        $stm->bindParam(':commentStatus' , $commentStatus , \PDO::PARAM_STR);
        $stm->bindParam(':id' , $commentID , \PDO::PARAM_INT);
        $stm->bindParam(':time' , $time , \PDO::PARAM_INT);
        $result = $stm->execute();

        return $result;


    }


}