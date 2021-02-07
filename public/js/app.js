"use strict";

$( document ).ready( ()=>{

    $('#removeAuthor').click( function (  ){

       let authorID = $( this ).data('author-id');

       console.log('authorID: ' , authorID);

       $.ajax({
           'url': `/BookShopMVC/public/author/${authorID}`,
           'type': 'DELETE',
           'success': ( a , b)=>{
               console.log(a,b);
           }
       });

    }  );

    $('#checkIn').click(function () {

        let login = $('#login').val();
        let email = $('#email').val();

        if(login.length === 0){

            $('#loginError').text('Поле не может быть пустым!').addClass("red ");
            return;

        }
        if(email.length === 0){

            $('#emailError').text('Поле не может быть пустым!').addClass("red ");
            return;

        }

        let isTrueLogin = ValidatorConst.USER_LOGIN_VALIDATOR.test(login);
        if(!isTrueLogin){
            $('#loginError').addClass("red ");
        }

        let isTrueEmail = ValidatorConst.USER_EMAIL_VALIDATOR.test(email);
        if(!isTrueEmail){
            $('#emailError').addClass("red ");
        }

        let firstName = $('#firstNameInput').val();
        let lastName = $('#lastNameInput').val();
        let middleName = $('#middleNameInput').val();

        if(lastName.length === 0){
            $('#firstNameError').text('Поле не может быть пустым!').addClass("red ");
            return;
        }
        if(firstName.length === 0){
            $('#lastNameError').text('Поле не может быть пустым!').addClass("red ");
            return;
        }
        if(middleName.length === 0){
            $('#middleName').text('Поле не может быть пустым!').addClass("red ");
            return;
        }

        let isTrueFirstName = ValidatorConst.USER_NAMES_VALIDATOR.test(firstName);
        if( !isTrueFirstName ){
            $('#firstNameError').addClass("red ");
        }

        let isTrueLastName = ValidatorConst.USER_NAMES_VALIDATOR.test(lastName);
        if( !isTrueLastName ){
            $('#lastNameError').addClass("red ");
        }

        let isTrueMiddleName = ValidatorConst.USER_NAMES_VALIDATOR.test(middleName);
        if( !isTrueMiddleName ){
            $('#middleNameError').addClass("red ");
        }

        let phoneNumber = $('#phoneNumberInput').val();

        if(phoneNumber.length === 0){
            $('#phoneNumberError').text('Поле не может быть пустым!').addClass("red ");
            return;
        }

        let isTruePhoneNumber = ValidatorConst.USER_PHONE_VALIDATOR.test(phoneNumber);
        if(!isTruePhoneNumber){
            $('#middleNameError').addClass("red ");
        }

        let password = $('#password').val();
        let confirmPassword = $('#confirmPassword').val();

        if(password.length === 0){

            $('#passwordError').text('Поле не может быть пустым!').addClass("red ");
            return;

        }
        if(confirmPassword.length === 0){

            $('#confirmPasswordError').text('Поле не может быть пустым!').addClass("red ");
            return;

        }

        let isTruePassword = ValidatorConst.USER_PASSWORD_VALIDATOR.test(password);
        if(!isTruePassword || password !== confirmPassword) {

           let test = $('#confirmPasswordError');

            test.removeClass("none");
            test.addClass("red block");

        }

        if( isTrueLogin&&
            isTrueEmail&&
            isTruePassword&&
            isTrueFirstName&&
            isTrueLastName&&
            isTrueMiddleName&&
            isTruePhoneNumber&&
            (password === confirmPassword)
        ){

            $.ajax({

                'url': `/BookShopMVC/public/addUser`,
                'type': 'POST',
                'data': {

                    'userLogin': login,
                    'userEmail': email,
                    'firstName': firstName,
                    'lastName': lastName,
                    'middleName': middleName,
                    'phoneNumber': phoneNumber,
                    'userPassword': password

                }
            }).done( (data)=>{

                if( +data.code === 200){

                    location.href = `${window.paths.AjaxServerUserUrl}authorize`;

                }
                else{

                    $('#ModalTitle').text( 'Ошибка' );
                    $('#ModalBody').text( data.message );
                    $('#Modal').modal();

                }

            }).fail((data)=>{

                $('#ModalTitle').text( 'Ошибка' );
                $('#ModalBody').text( data.responseJSON.message );
                $('#Modal').modal();

            });

        }

    });

    $('#signOut').click(function () {

        let url = `${window.paths.AjaxServerUrl}${window.paths.Logout}`;

        $.ajax({
            'url': url,
            'type': 'POST',
            'success': () =>{

                location.href = `${window.paths.AjaxServerUserUrl}authorize`;

            }
        });

    });

    $('.add-to-cart').click( async function (){

        let bookID = +$(this).data('book-id');
        let cart = $.cookie('cart');

        if( !cart ){

            $.cookie('cart' , [
                {
                    bookID: bookID,
                    amount: 1
                }
            ] , {expires: 7 , path: '/'});

        }
        else{

            let book = cart.find( b => b.bookID === bookID )  ;

            if(!book){

                cart.push( {
                    bookID: bookID,
                    amount: 1
                });

                $.cookie('cart' , cart , {expires: 7 , path: '/'});

            }



        }

        let count = $.cookie('cart').length;

        $('#Order').text(`(${count})`);

        $(this).fadeOut(500);

    } );

    $("#searchInput").keyup(function(){

        let searchData = $("#searchInput").val();

        $.ajax({

            type: "POST",
            url: "SearchController.php",
            data: {
                "search": searchData
            },
            cache: false,
            success: function(response){
                console.log(response);
            }

        });

        return false;

    });

} );

window.paths = {

    AjaxServerUrl: '/BookShopMVC/public/admin/',
    AjaxServerUserUrl: '/BookShopMVC/public/',

    getOrders: 'ordersByUser',

    Login: 'login',
    Logout: 'logout',

    SaveNewAvatar: 'save-avatar',
    SaveNewPersonalData: 'save-new-personal-data',
    ChangePassword: 'update-user-password',

    RemoveAuthor: 'author/:authorID',
    UpdateAuthor: 'author/:authorID',
    AddAuthor: 'author',

    RemoveGenre: 'genre/:genreID',
    UpdateGenre: 'genre',
    AddGenre: 'add_genre',

    RemoveComment: 'comment/:commentID',
    UpdateComment: 'comment',
    AddComment: 'add_comment',
    MoreComments: 'more-comments/:bookId',
    UpdateStatus: 'comment-status',
    ModerationComments: 'comments-mod/',
    ModerationMoreComments: 'comments-mod-more/',

    AddBook: 'new-book',
    EditBook: 'edit-book/:bookID',
    DeleteBook: 'delete-book/:bookID',
    GetBooks: 'get-books',

    AddOrder: 'addOrder',
    UpdateStatusOrder: 'update-order-status',
    GetMoreOrders: 'orderdetails-more',


};

window.StatusConsts = {
    StatusAll: 0,
    StatusNew: 1,
    StatusApprove: 2,
    StatusReject: 3,

};

$.cookie.json = true;

