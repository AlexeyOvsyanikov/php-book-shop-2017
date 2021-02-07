<?php

namespace Application\Services;


class CartService {

    public function getCart(  ){

        if( isset($_COOKIE['cart']) ){
            return json_decode($_COOKIE['cart']);
        }

        return [];
    }

    public function prepareBookArray(  $books  ){

        $cart = $this->getCart();

        if($cart){

            foreach ($cart as $cartItem) {

                foreach ($books as &$book) {

                    if( (int)$cartItem->bookID ===  (int)$book->bookID){
                        $book->isInCart = true;
                        break;
                    }

                }


            }
        }


    }

}