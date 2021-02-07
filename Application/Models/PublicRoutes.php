<?php

return array(

    'get' => [
        '/comments-new/(\d+)' => 'CommentsController@publicAddCommentPageAction',
        '/comments/(\d+)' => 'CommentsController@commentListPublicByBookAction',
        '/' => 'HomeController@indexAction',
        '/home' => 'HomeController@indexAction',
        '/cart' => 'CartController@cartAction',
        '/authorize' => 'AuthorizeController@authorizeAction',
        '/registration'=>'UserController@registration',
        '/person' => 'PersonController@getPersonAction',
        '/book/(\d+)' => 'BookController@getPublicBookAction',
        '/orders' => 'OrdersController@UserDealInfoByIdAction',
        '/ordersByUser/(\d+)/(\d+)'=> 'OrdersController@UserDealInfoById',
        '/order/(\d+)' => 'OrdersController@userOrderDetailAction',
        '/ordersUserDetailsOffset/(\d+)/(\d+)/(\d+)' => 'OrdersController@userOrderDetail',
        '/edit-person-data' => 'PersonController@EditPersonDataAction',
        '/change-person-password' => 'PersonController@ChangePasswordAction',
        '/placeOrder' => 'OrderController@PlaceOrderAction',
    ],
    'post' => [
        '/addUser'=>'UserController@addUser',
        '/login' => 'AuthorizeController@LoginAction',
        '/add_comment' => 'CommentsController@addCommentAction',
        '/search' => 'SearchController@LoadSearchPage'
    ],
    'put' => [

    ],
    'delete' => [

    ]

);