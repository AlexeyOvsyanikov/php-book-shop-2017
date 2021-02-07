<?php

namespace Application\Controllers;

use Application\Services\BookService;

use Application\Services\CartService;
use Application\Services\UserService;

class HomeController extends BaseController{

    public function indexAction(  ){

        $userService = new UserService();
        $bookService = new BookService();
        $cartService = new CartService();

        $cart = $cartService->getCart();
        $user = $userService->getCurrentUser();

        $template = $this->twig->load('public/home.twig');
        $books = $bookService->GetFullBooks();

        $cartService->prepareBookArray($books);

        echo $template->render( [
            'user' => $user,
            'books' => $books,
        ] );

    }

    public function Action404(  ){

        try {

            $template = $this->twig->load('ErrorPages/404-not-found.twig');
            echo $template->render( );

        }
        catch (\Exception $ex) {

        }

    }

}