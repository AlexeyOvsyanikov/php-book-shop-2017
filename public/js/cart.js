$(document).ready( function (  ) {

    $('.count').change( function (  ){

        let bookId = $(this).data('book-id');
        let price = $(this).data('book-price');
        let amount = $(this).val();
        let totalBookPrice = amount*price;


       $(`tr[data-book-id=${bookId}]`).find('#valueTotalPrice').text(totalBookPrice + 'руб.');

        let booksInCart = $.cookie('cart');

        let book = booksInCart.find( b => b.bookID === bookId )  ;

        if(book){

            book.amount = +amount;

        };

        $.cookie('cart' , booksInCart , {expires: 7 , path: '/'});

        let totalSum = 0;

        $("#cartTable tr").each(function(){

            $("td[id^='totalPrice']",this).each(function(){
                let str = $(this).find('#valueTotalPrice').text();
                let currentSum = parseFloat(str);
                if(!isNaN(currentSum)){
                    totalSum += currentSum;
                }

            });
        });

        $('#totalSum').text('Итого:' + ' ' + totalSum + ' ' + 'руб.');


    });


    $('.btn-danger').click( function (  ){

        let bookId = $(this).data('book-id');

        let title = $(this).data('title');

        $('#Modal').modal();
        $('#ModalTitle').text("Удаление товара");
        $('#ModalBody').html(`
            <h3>Delete!</h3>
            <div>Вы действительно хотите удалить данную книгу из корзины  <span style="font-weight: bold" data-book-id="${bookId}" id="nameAccess"></span>?</div>
        `);

        $('#nameAccess').text(title);


    });

    $('#OkButton').click(function () {

        let bookId = $('#nameAccess').data('book-id');
        let booksInCart = $.cookie('cart');

        let book = booksInCart.find( b => b.bookID === bookId )  ;
        let index = booksInCart.indexOf(book);
        console.log(index);
        booksInCart.splice(+index, 1);

        console.log($.cookie('cart'));

        $.cookie('cart' , booksInCart , {expires: 7 , path: '/'});

        console.log($.cookie('cart'));
        let strTotalBookPrice = $(`tr[data-book-id=${bookId}]`).find('#valueTotalPrice').text();
        let totalBookPrice =parseFloat(strTotalBookPrice);

        $(`tr[data-book-id=${bookId}]`).remove();

        let totalArray = $('#totalSum').text().split(' ');
        let total = totalArray[1];


        let totalSum = total - totalBookPrice;

        $('#totalSum').text('Итого:' + ' ' + totalSum + ' ' + 'руб.');
        let count = $.cookie('cart').length;

        $('#Order')[0].innerText = '(' + count + ')';

        if(count === 0){

            $('#BlockTotal').css('display', 'none');
            $('#cartTable').css('display', 'none');
            $('#CartTableSection').append(` <div id="cartEmpty" class="col-6 alert alert-info">Корзина пуста!</div>`);

        }
    });

});