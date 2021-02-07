"use strict";

$(document).ready(()=>{

    let limit = 10;
    let offset = 10;

    let limitOrders = 10;
    let offsetOrders =10;

    let limitOrdersDetail = 10;
    let offsetOrdersDetail =10;

    let countOrdersDetail =0;
    let countOrders = 0;

    let count =  +$('tr').length;

    if(count < limitOrdersDetail){

        $('#btn-yet-detail').css({
            'display': 'none'
        });

    }

    $('#deal-yet').click(async function(){

        try{
            let response = await $.ajax({
                url: `${window.paths.AjaxServerUserUrl}/ordersByUser/${limitOrders}/${offsetOrders}`,
                method: 'GET'
            });
            if(response.orders === null  ){
                $('#btn-yet').css({
                    'display': 'none'
                });
                return;
            };
            $.each(response.orders, function (index , order) {


                $('.table').append(`
                     <tr>

                    <td>${ order.orderID }</td>
                    <td>${ order.orderDatetime }</td>
                    <td>${ order.adressOrder }</td>
                    <td>${order.statusTitle}</td>
                    <td>
                        <a href="orders.twig/${order.orderID}/10/0" class="btn btn-primary"> подробнее </a>
                    </td>

                </tr>

                `);

            });

        }
        catch (e) {
            console.log('error');
        }

        offsetOrders+=limitOrders;


    });

    $('#deal-yet-detail').click(async function(){


        let id = $('[data-orderId]').data('orderid');

        console.log('работает', id);
        try{
            let response = await $.ajax({
                url: `${window.paths.AjaxServerUserUrl}/ordersUserDetailsOffset/${id}/${limitOrdersDetail}/${offsetOrdersDetail}`,
                method: 'GET'
            });
            console.log('length',response.orderDetail.length);
            if(response.orderDetail.length===0  ){
                $('#btn-yet-detail').css({
                    'display': 'none'
                });
                return;
            };
            $.each(response.orderDetail, function (index , order) {


                let stringAuthors='';

                $.each(order.authors, function (index, author) {

                    stringAuthors += author.authorFirstName

                    if(order.authors.length<index-1){
                        stringAuthors+=','
                    }
                } )

                $('.table').append(`
                     <tr>

                        <th scope="row">
                            <img src="${ order.book.bookImagePath }" style="float: left; width: 50px"  alt="Cinque Terre">
                        </th>
                        <td>${ order.book.bookTitle }</td>
                        <td>${ stringAuthors  }</td>
                        <td>${ order.bookPrice } руб.</td>
                        <td>${ order.bookAmount }</td>
                     
                     </tr>

                `);

            });

        }
        catch (e) {
            console.log('error',e);
        }
        offsetOrdersDetail+=limitOrdersDetail;


    });

});