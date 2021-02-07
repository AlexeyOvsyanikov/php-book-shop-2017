(function () {

    $('#addAuthor').click( function (  ){

        let nameAuthor = $('#nameAuthor').val();
        let lastNameAuthor = $('#lastNameAuthor').val();

        if(!/^[a-zа-я]{2,50}$/i.test(nameAuthor) || !/^[a-zа-я]{2,50}$/i.test(lastNameAuthor)){

            $('#successMessage').fadeOut(1000);
            $('#errorMessage').fadeOut(500);
            $('#errorInput').fadeIn(500).delay( 5000 ).fadeOut( 500 );

        }
        else{
            $.post(
                `${window.paths.AjaxServerUrl}${window.paths.AddAuthor}`,
                {
                    'authorLastname': lastNameAuthor,
                    'authorFirstname': nameAuthor
                },
                function ( response ){

                    $('#successMessage').fadeIn(1000);
                    $('#errorMessage').fadeOut(500);
                    $('#errorInput').fadeOut(500);

                    console.log('response:' , response);

                    $('#authorTable').append(`
                    <tr data-author-id = "${response.authorID}">
                        <td>${response.authorID}</td>
                        <td>${nameAuthor}</td>
                        <td>${lastNameAuthor}</td>
                        <td>
                            <button data-author-id="${response.authorID}" class="btn btn-danger" >Удалить</button>
                        </td>
                        <td>
                            <a href="${window.paths.AjaxServerUrl}author/${response.authorID}" class="btn btn-primary" >Обновить</a>
                        </td>
                    </tr>`
                    );



                }
            );

        }



    }  );

    $('#removeAuthor').click( function (  ){

        let authorID = +$( this ).data('author-id');


        let deleteURL = `${window.paths.AjaxServerUrl}${window.paths.RemoveAuthor}`;
        deleteURL = deleteURL.replace(':authorID' , authorID);

        $.ajax({
            'url': deleteURL,
            'type': 'DELETE',
            'success': ( data )=>{

                if( +data.code === 200 ){

                        location.href = `${window.paths.AjaxServerUrl}authors`;

                }
                $.modal.close();
            }
        });

    }  );

    $('body').on('click','#removeAuthor,.btn-danger' , function (  ){

        let authorID = +$( this ).data('author-id');
        let authorName = $( this ).data('author-name');
        let authorLastName = $( this ).data('author-lastname');

        let deleteURL = `${window.paths.AjaxServerUrl}${window.paths.RemoveAuthor}`;
        deleteURL = deleteURL.replace(':authorID' , authorID);

        let self = $(this);

        $('#Modal').modal();
        $('#ModalTitle').text("Удаление");
        $('#ModalBody').html(`
            <h1>Удаление!</h1>
        `);


        $('#nameAuthorModal').text(authorName);
        $('#lastNameAuthorModal').text(authorLastName);

        $('#ConfirmButton').click(function () {

            $.ajax({
                'url': deleteURL,
                'type': 'DELETE',
                'success': ( data )=>{

                    if( +data.code === 200 ){

                        if( self.attr('id') === 'removeAuthor' ){
                            location.href = `${window.paths.AjaxServerUrl}authors`;
                        }
                        else{
                            $(`tr[data-author-id=${authorID}]`).remove();
                        }

                    }

                }
            });

        });


    }  );

    $('#updateAuthor').click(function (  ){

        let authorID = +$(this).data('author-id');
        let nameAuthor = $('#nameAuthor').val();
        let lastNameAuthor = $('#lastNameAuthor').val();

        if(!/^[a-zа-я]{4,50}$/i.test(nameAuthor) || !/^[a-zа-я]{4,50}$/i.test(lastNameAuthor)){
            $('#successMessage').fadeOut(1000);
            $('#errorMessage').fadeOut(500);
            $('#errorInput').fadeIn(500);
        }
        else{
            let url = window.paths.UpdateAuthor.replace(':authorID' , authorID);
            url = `${window.paths.AjaxServerUrl}${url}`;

            $.ajax({
                url: url,
                type: 'PUT',
                data: {
                    'authorFirstname': nameAuthor,
                    'authorLastname': lastNameAuthor,
                },
                success: ( response )=>{
                    $('#successMessage').fadeIn(1000);
                    $('#errorMessage').fadeOut(500);
                    $('#errorInput').fadeOut(500);

                }
            });
        }


    } );

})();


