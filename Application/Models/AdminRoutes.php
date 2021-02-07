<?php


return array(
    'get' => [
        '/admin/authors' => 'AuthorController@authorListAction',
        '/admin/author/(\d+)' => 'AuthorController@getAuthorAction',
        '/admin/users'=>'UserController@getUsers',
        '/admin/user/(\w+)'=>'UserController@getSingleUser',
        '/admin/genres' => 'GenresController@getGenresAction',
        '/admin/genre_books/(\d+)' => 'GenresController@getGenreBooksAmountAction',
        '/admin/genre/(\d+)' => 'GenresController@getGenreAction',
        '/admin/books' => 'BookController@bookListAction',
        '/admin/new-book' => 'BookController@newBookAction',
        '/admin/info-book/(\d+)' => 'BookController@infoBookAction',
        '/admin/verification'=>'UserController@verificationUser',
        '/admin/edit-books/(\d+)' => 'BookController@editBookAction',
        '/admin/comments/(\d+)' => 'CommentsController@commentListByBookAction',
        '/admin/comments-list' => 'CommentsController@commentListAction',
        '/admin/comments-mod/(\d+)' => 'CommentsController@commentModerationListAction',
        '/admin/more-comments/(\d+)' => 'CommentsController@commentMoreAction',
        '/admin/get-books' => 'BookController@getMoreBooks',
        '/admin/comments/new/(\d+)' => 'CommentsController@addCommentPageAction',
        '/admin/personal-page' => 'PersonalPageController@personalPageAction',
        '/admin/edit-personal-data' => 'PersonalPageController@EditPersonalDataAction',
        '/admin/change-password' => 'PersonalPageController@ChangePasswordAction',
        '/admin/order' => 'OrderController@getOrderAction',
        '/admin/orderdetails/(\d+)' => 'OrderController@orderDetailsListAction',
        '/admin/orderdetails-more' => 'OrderController@GetOrdersMore',


    ],
    'post' => [
        '/admin/logout' => 'ApplicationController@LogoutAction',
        '/admin/save-avatar' => 'PersonalPageController@ChangeUserAvatar',
        '/admin/new-book' => 'BookController@addBookAction',
        '/admin/author' => 'AuthorController@addAuthorAction',
        '/admin/add_genre' => 'GenresController@addGenreAction',
        '/admin/genre' => 'GenresController@updateGenreAction',
        '/admin/edit-book/(\d+)' => 'BookController@acceptEditBookAction',
        '/admin/add_comment' => 'CommentsController@addCommentAction',
        '/admin/addOrder' => 'OrderController@addOrder',
    ],
    'delete' => [
        '/admin/author/(\d+)' => 'AuthorController@deleteAuthorAction',
        '/admin/delete-book/(\d+)' => "BookController@deleteBookAction",
        '/admin/genre/(\d+)' => 'GenresController@deleteGenreAction',
        '/admin/comment/(\d+)' => 'CommentsController@deleteCommentAction',
    ],
    'put' => [
        '/admin/author/(\d+)' => 'AuthorController@updateAuthorAction',
        '/admin/comment' => 'CommentsController@updateCommentAction',
        '/admin/comment-status' => 'CommentsController@updateCommentStatusAction',
        '/admin/save-new-personal-data' => 'PersonalPageController@SaveNewPersonalData',
        '/admin/update-user-password' => 'PersonalPageController@ChangePassword',
        '/admin/update-order-status' => 'OrderController@UpdateOrderStatuses',
    ]

);

