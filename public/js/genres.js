$(document).ready( function (  ){


    $('#UpdateGenreButton').click( function (  ){

        let name = $('#UpdateGenreInput').val();

        if (/^[a-zа-я\s]{4,50}$/i.test(name) === false) {
            $('#errorInput').fadeIn(500).delay( 5000 ).fadeOut( 500 );

        }
        else{
            let genreID = $( this ).data('genre-id');



            $.ajax({
                'url': `${window.paths.AjaxServerUrl}${window.paths.UpdateGenre}`,
                'type': 'POST',
                'data': {
                    'id': genreID,
                    'name': name,
                },
                'success': (data) => {

                    let genreId = +data.genreID;
                    let status = +data.status;

                    if (status === 200 && genreId !== 0) {

                        $('#errorMessage').fadeOut(1000);
                        $('#successMessage').fadeIn(500).delay( 5000 ).fadeOut( 500 );;

                    }
                    else {
                        $('#successMessage').fadeOut(1000);
                        $('#errorMessage').fadeIn(500).delay( 5000 ).fadeOut( 500 );;
                    }
                }

            });
        }

    }  );

    $('body').on('click', '#AddGenreButton', function () {

        let name = $('#AddGenreInput').val();

        if (/^[a-zа-я\s]{4,50}$/i.test(name) === false) {
            $('#errorInput').fadeIn(500).delay( 5000 ).fadeOut( 500 );

        }
        else{

            $.ajax({
                'url': `${window.paths.AjaxServerUrl}${window.paths.AddGenre}`,
                'type': 'POST',
                'data': {
                    'name': name,
                },
                'success': (data) =>{

                    let genreId = +data.genreID;
                    let status = +data .status;
                    let amount = +data .amount;

                    if( status === 200 &&genreId!==0){

                        $('#errorMessage').fadeOut(1000);
                        $('#successMessage').fadeIn(1000).delay( 5000 ).fadeOut( 500 );;

                        $('#GenresTable').append(`
                             <tr data-genre-id = "${genreId}">
                                <td>${genreId}</td>
                                <td>${name}</td>
                                <td>${amount}</td>
                                <td>
                                    <button data-genre-id="${genreId}" data-genre-name="${name}" class="btn btn-danger" >Удалить</button>
                                </td>
                                <td>
                                    <a href="${window.paths.AjaxServerUrl}${window.paths.UpdateGenre}/${genreId}" class="btn btn-primary" >Обновить</a>
                                </td>
                            </tr>`
                        );
                    }
                    else{
                        $('#successMessage').fadeOut(1000);
                        $('#errorMessage').fadeIn(1000).delay( 5000 ).fadeOut( 500 );;
                    }



                }
            });
        }


    }  );


    $('body').on('click','.btn-danger' , function (  ){

        let name = $(this).data('genre-name');
        let genreID = $(this).data('genre-id');

        let deleteURL = `${window.paths.AjaxServerUrl}${window.paths.RemoveGenre}`;
        deleteURL = deleteURL.replace(':genreID', genreID);

        $('#Modal').modal();
        $('#ModalTitle').text("Удаление");
        $('#ModalBody').html(`
            <h3>Удаление!</h3>
            <div>Вы действительно хотите удалить жанр  <span style="font-weight: bold" id="nameGenre"></span>?</div>
        `);

        $('#nameGenre').text(name);

        $('#ConfirmButton').click(function () {
            $.ajax({
                'url': deleteURL,
                'type': 'DELETE',
                'success': (data) => {

                    if (+data.code === 200) {

                        $(`tr[data-genre-id=${genreID}]`).remove();

                    }

                }
            });

        });


    }  );






});
