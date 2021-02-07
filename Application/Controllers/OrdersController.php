<?php

namespace Application\Controllers;
use Application\Services\OrderService;
use Application\Services\BookService;
use Application\Services\UserService;

class OrdersController extends BaseController{

    public function UserDealInfoByIdAction(){

        $OrdersService = new OrderService();
        $userService = new UserService();
        $userId = $userService->getCurrentUser();

        $orders = $OrdersService->UserDealInfoById($userId['userID'], $limit=10, $offset=0);
        $template = $this->twig->load('public/OrderAndCart/ordersInfo.twig');


        if($offset!==null){
            echo $template->render( array(
                'orders' => $orders
            ) );
        }
        else{
            echo $template->render( array(
                'orders' => []
            ) );
        }

    }

    public function UserDealInfoById($limit, $offset){

        $limit = intval($limit);
        $offset = intval($offset);

        if($this->currentUser !== null){

            $OrdersService = new OrderService();

            $orders = $OrdersService->UserDealInfoById(
                $this->currentUser['userID'],
                $limit,
                $offset
            );

            $this->json( 200 , array(
                'code' => 200,
                'orders' => $orders,
            ) );

        }
        else{

            $this->json( 401 , array(
                'code' => 401,
            ) );

        }

    }

    public function userOrderDetailAction($orderId){


        $template = $this->twig->load('public/OrderAndCart/orders.twig');

        $BookService = new BookService();
        $OrdersService = new OrderService();

        $orderDetail = $OrdersService->getDealDetail($orderId,$limit=10,$offset=0);
        $order = $OrdersService->GetOrderByID($orderId);

        for($i=0; $i<count($orderDetail);$i++){

            $book = $BookService->GetBookById($orderDetail[$i]->bookID);

            $orderDetail[$i]->book = $book;
        }//for

        $total = $OrdersService->getTotalSumByDealId($orderId);
        echo $template->render( array(

            'orderDetail' => $orderDetail,
            'orderID' => $orderId,
            'total' => $total->total,
            'order' => $order

        ) );

    }

    public function userOrderDetail($orderId, $limit, $offset){//my

        $limit = intval($limit);
        $offset = intval($offset);

        $BookService = new BookService();
        $OrdersService = new OrderService();
        $orderDetail = $OrdersService->getDealDetail($orderId,$limit,$offset);


        for($i=0; $i<count($orderDetail);$i++){

            $book = $BookService->GetBookById($orderDetail[$i]->bookID);

            $orderDetail[$i]->book = $book;
        }//for

        $this->json( 200 , array(
            'code' => 200,
            'orderDetail' => $orderDetail,
        ) );

    }

}