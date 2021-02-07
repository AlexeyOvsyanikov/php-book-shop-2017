(function () {

    let limit = 10;
    let offset = 10;

    $('#addOrder').click( async function (  ){

        let cart = $.cookie('cart');
        
        console.log("cart", cart);

        if(!cart || cart.length === 0){

            $('#successMessage').fadeOut(1000);
            $('#errorMessage').text("Корзина пустая");
            $('#errorMessage').fadeIn(500).delay( 5000 ).fadeOut( 500 );

        }
        else{

            let orderData = new FormData();

            let adressOrder = $('#orderAdress').val();

            console.log('adressOrder', adressOrder);

            if(!adressOrder){
                $('#errorMessage').text("Введите адресс");
                $('#errorMessage').fadeIn(500).delay( 5000 ).fadeOut( 500 );
                return;
            }

            orderData.append('cart' , JSON.stringify(cart) );
            orderData.append('adressOrder' , adressOrder );

            try{

                let response = await $.ajax({
                    url: `${window.paths.AjaxServerUrl}${window.paths.AddOrder}`,
                    method: 'POST',
                    contentType: false,
                    processData: false,
                    data: orderData
                });

                if( +response.code === 200 ){

                    $.cookie('cart' , [] , { path: '/'});
                    $('#Order').text('(0)');
                    $(this).fadeOut(200);

                    $('#successMessage').text("Ваш заказ принят в обработку");
                    $('#successMessage').fadeIn(200).delay(5000).fadeOut(1500);

                }
                else if(+response.code === 401){
                    $('#errorMessage').text(response.message);
                    $('#errorMessage').fadeIn(500).delay( 5000 ).fadeOut( 500 );

                }
                else{
                    $('#errorMessage').text("Произошла ошибка при оформлении заказа");
                    $('#errorMessage').fadeIn(500).delay( 5000 ).fadeOut( 500 );

                }

            }
            catch( ex ){
                $('#errorMessage').text("ошибка сервера");
                $('#errorMessage').fadeIn(500).delay( 5000 ).fadeOut( 500 );

                console.log(ex);

            }
        }



    }  );

    $('#orderStatus').change( async function(){

        let status = [].map.call( $('#orderStatus option:selected') ,
                            ( opt )=>{ return $(opt).data('status-id') } );

       let orderID = $('#orderStatus').data('order-id');
       let statusID = status[0];

        try{

            let response = await $.ajax({
                url: `${window.paths.AjaxServerUrl}${window.paths.UpdateStatusOrder}`,
                method: 'PUT',
                data:{
                    orderID: orderID,
                    statusID: statusID
                },
            });

            if( +response.code === 200 ){

                $('#successMessage').text("Статус изменен");
                $('#successMessage').fadeIn(200).delay(5000).fadeOut(1500);

            }
            else{
                $('#errorMessage').text("Произошла ошибка при изменении статуса");
                $('#errorMessage').fadeIn(500).delay( 5000 ).fadeOut( 500 );

            }

        }
        catch( ex ){
            $('#errorMessage').text(ex.message);
            $('#errorMessage').fadeIn(500).delay( 5000 ).fadeOut( 500 );

            console.log(ex);

        }

    })

    $('#moreOrders').click(async function(){

        try{

            let response = await $.ajax({
                url: `${window.paths.AjaxServerUrl}${window.paths.GetMoreOrders}`,
                method: 'GET',
                data: {
                    limit: limit,
                    offset: offset
                }
            });

            if( +response.code === 200 ){

                offset += limit;

                if(offset > response.orders.length){
                    $('#moreOrders').fadeOut(500);
                }

                response.orders.forEach(function(od){
                    $('#ordersTable').append(`
                    <tr>
                <td>${od.order.orderID}</td>
                <td>${od.user.userLogin}</td>
                <td>${ od.date }</td>
                <td>${ od.statusTitle.statusTitle }</td>
                <td>${ od.count[0]}</td>
                <td>
                    <a href="/BookShopMVC/public/admin/orderdetails/${od.order.orderID }" class="btn btn-primary" >Подробнее</a>
                </td>
            </tr>`
                    );
                });

            }
            else{
                $('#errorMessage').text("Произошла ошибка при загрузке заказов");
                $('#errorMessage').fadeIn(500).delay( 5000 ).fadeOut( 500 );

            }


        }
        catch( ex ){

            $('#errorMessage').fadeIn(500).delay( 5000 ).fadeOut( 500 );

            console.log(ex);

        }
    })

})();


