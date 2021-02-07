<?php


namespace Application\Controllers;


use Application\Services\BookService;
use Application\Services\CartService;
use Application\Services\OrderDetailsService;
use Application\Services\OrderService;
use Application\Services\UserService;

class OrderController extends BaseController {

    public function getOrderAction(){

        $orderService = new OrderService();
        $orders = $orderService->GetOrders();

        $ordersWithUser = [];

        for($i=0;$i < count($orders); $i++ ){

            $ordersWithUser[$i] = $this->GetFullOrder($orders[$i]);


        }

        $template = $this->twig->load('Order/order-list.twig');

        echo $template->render( array(
            'orders' => $ordersWithUser
        ) );

    }

    public function GetFullOrder($order){

        $orderService = new OrderService();

        $userService = new UserService();

        $dateStr = $order->orderDatetime;

        $countBook = $orderService->GetCountBookInOrderByID($order->orderID);

        return [
            'order' => $order,
            'user' => $userService->getSingleUser($order->userID),
            'date' => $dateStr,
            'count' => $countBook,
            'statusTitle' => $orderService->GetTitleStatusOrderByID($order->orderStatus),

        ];
    }

    public function addOrder(){

        $cart = json_decode($this->request->GetPostValue('cart'));
        $adressOrder = $this->request->GetPostValue('adressOrder');

        if(!$adressOrder){

            $this->json( 400 , array(
                'adress_err' => $adressOrder
            ) );

            return;
        }

        $userService = new UserService();

        $user = $userService->getCurrentUser();

        if(!$user){
            $this->json( 401 , array(
                'code' => 401,
                'message' => "Вы не авторизированны"
            ) );
        }
        $const = new patternConst();

        $orderStatus = $const->statusOrderNew;

        $orderService  = new OrderService();

        try{
            $orderID = $orderService->AddOrder($user['userID'], $orderStatus, $adressOrder);

            $bookService = new BookService();

            $bookByID= null;

            foreach($cart as $book){

                $bookByID = $bookService->GetBookById($book->bookID);

                $orderService->AddOrdersDetails($orderID, $book->bookID, $bookByID->bookPrice, $book->amount );

            }

            $this->json( 200 , array(
                'code' => 200,
                '$orderID' => $orderID,
            ) );

        }
        catch (\Exception $ex){
            $this->json( 500 , array(
                'code' => 500,
                'message' => $ex
            ) );
        }

    }

    public function orderDetailsListAction( $orderID ){

        $orderService = new OrderService();
        $orderDetails = $orderService->GetOrdersDetailsByOrderId($orderID);

        $orderController = new OrderController();

        $order = $orderService->GetOrderByID($orderID);

        $orderFull = $orderController->GetFullOrder($order);

        $bookService = new BookService();

        $ODFull = [];

        $totalSum = 0;

        for($i=0;$i < count($orderDetails); $i++ ){

            $totalSum += $orderDetails[$i]->bookPrice * $orderDetails[$i]->bookAmount;

            $ODFull[$i] = [
                'od'=>$orderDetails[$i],
                'book'=>$bookService->GetBookById($orderDetails[$i]->bookID),
            ];

        }

        $template = $this->twig->load('Order/orderDetails.twig');

        $statuses = $orderService->GetOrderStatuses();

        foreach ( $statuses as $status ){

            if($status->statusID === $order->orderStatus){
                $status->isSelected = 1;
            }
            else{
                $status->isSelected = 0;
            }
        }

        echo $template->render( array(
            'orderdetails' => $ODFull,
            'order'=>$orderFull,
            'statuses'=>$statuses,
            'total'=>$totalSum
        ) );

    }

    public function UpdateOrderStatuses(){

        $orderID = $this->request->GetPutValue('orderID');
        $statusID = $this->request->GetPutValue('statusID');


        $orderService = new OrderService();

        try{

            $orderService->UpdateStatusOrder($orderID, $statusID);

            $this->json( 200 , array(
                'code' => 200,
                '$orderID' => $orderID,
            ) );

        }
        catch (\Exception $ex){
            $this->json( 500 , array(
                'code' => 500,
                'message' => $ex,
                 '$orderID' => $orderID,
            ) );
        }

    }

    public function GetOrdersMore(){

        $limit = $this->request->GetGetValue('limit');
        $offset = $this->request->GetGetValue('offset');

        $orderService = new OrderService();
        $orders = $orderService->GetOrders($limit, $offset);

        $ordersWithUser = [];

        for($i=0;$i < count($orders); $i++ ){

            $ordersWithUser[$i] = $this->GetFullOrder($orders[$i]);

        }

        $this->json( 200 , array(
            'code' => 200,
            'orders' => $ordersWithUser,
        ) );
    }

    public function PlaceOrderAction(){

       $cartService = new CartService();
       $bookService = new BookService();

       $cart = $cartService->getCart();

       $cartFull = [];

       $total = 0;

        foreach ( $cart as $item ){

            $book = $bookService->GetBookById($item->bookID);

            $total+= $book->bookPrice * $item->amount;

            $cartFull[] = [
                'cart'=>$item,
                'book'=>$book
            ];
        }

        $userService = new UserService();

        if($this->currentUser !== null){
            $user = $userService->getSingleUser($this->currentUser['userID']);
        }
        else{
            $user = null;
        }

        $template = $this->twig->load('public/OrderAndCart/orderPlace.twig');

        echo $template->render( array(
            'user' => $user,
            'cart'=>$cartFull,
            'total'=>$total
        ) );

    }
}