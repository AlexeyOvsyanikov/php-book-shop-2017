"use strict";

$(document).ready( function (  ){

    let $offset = 10;
    let $limit = 10;

    $('#AddBookButton').click( async function ( ){

        const extentions = [
            '.JPG',
            '.jpg',
            '.jpeg',
            '.png',
            '.bmp',
        ];

        let bookTitle = $('#bookTitle').val();
        let bookISBN = $('#bookISBN').val();
        let bookPages = $('#bookPages').val();
        let bookPrice = $('#bookPrice').val();
        let bookAmount = $('#bookAmount').val();
        let bookDescription = $('#bookDescription').val()

        if(/^[а-яa-z0-9\s]{3,50}$/i.test(bookTitle) === false ){

            $('#errorInput').fadeIn(500).delay( 5000 ).fadeOut( 500 ).text('Название некорректно!');
            return;

        }

        if(/^\d{9}[\d|X]$/.test(bookISBN) === false ){

            $('#errorInput').fadeIn(500).delay( 5000 ).fadeOut( 500 ).text('ISBN некорректен!');
                return;

        }

        if(/\d{1,10}$/.test(bookPages) === false ){

            $('#errorInput').fadeIn(500).delay( 5000 ).fadeOut( 500 ).text('Кол-во страниц некорректно!');
                return;

        }

        if(/^\d+(,\d{1,2})?$/.test(bookPrice) === false ){

            $('#errorInput').fadeIn(500).delay( 5000 ).fadeOut( 500 ).text('Цена некорректна!');
                return;

        }

        if(/^\d{1,5}/.test(bookAmount) === false ){

            $('#errorInput').fadeIn(500).delay( 5000 ).fadeOut( 500 ).text('Кол-во книг некорректно!');
                return;

        }
        if(/^.{10,500}/.test(bookDescription) === false ){

            $('#errorInput').fadeIn(500).delay( 5000 ).fadeOut( 500 ).text('Описание некорректно!');
                return null;

        }

        let bookData = new FormData();
        bookData.append('bookTitle' , bookTitle);
        bookData.append('bookISBN' , bookISBN);
        bookData.append('bookPages' , bookPages);
        bookData.append('bookPrice' , bookPrice);
        bookData.append('bookAmount' , bookAmount);
        bookData.append('bookDescription' , bookDescription);

        let files =  $('#bookFile').prop('files');

        if(files.length !== 0){

            let file = files[0];

            let ext = file.name.substring(file.name.lastIndexOf('.'));

            bookData.append('bookImage' , file);

        }

        let authors = [].map.call( $('#authorsList option:selected') , ( opt )=>{ return $(opt).data('author-id') } );
        let genres = [].map.call( $('#genresList option:selected') , ( opt )=>{ return $(opt).data('genre-id') } );

        if(  authors.length === 0 ){

            $('#errorInput').css("display", "block").text('Авторы не выбраны!');
            return;

        }

        if(  genres.length === 0 ){

            $('#errorInput').css("display", "block").text('Жанры не выбраны!');
            return;

        }

        bookData.append('authors' , JSON.stringify(authors) );
        bookData.append('genres' , JSON.stringify(genres) );

        try{

            let response = await $.ajax({
                url: `${window.paths.AjaxServerUrl}${window.paths.AddBook}`,
                method: 'POST',
                contentType: false,
                processData: false,
                data: bookData
            });

            if( +response.code === 200 ){

                $('#successMessage').fadeIn(200).delay(5000).fadeOut(1500);

            }
            else{

                $('#errorMessage').fadeIn(500).delay( 5000 ).fadeOut( 500 );

            }

        }
        catch( ex ){

            $('#errorMessage').fadeIn(500).delay( 5000 ).fadeOut( 500 );

            console.log(ex);

        }

    });

    $('body').on('click','#editBook', function () {


        let bookID = $('#bookID').data('bookId');
        let bookTitle = $('#bookTitle').val();
        let bookISBN = $('#bookISBN').val();
        let bookPages = $('#bookPages').val();
        let bookPrice = $('#bookPrice').val();
        let bookAmount = $('#bookAmount').val();

        console.log(bookID);

        let updateURL = `${window.paths.AjaxServerUrl}${window.paths.EditBook}`;
        updateURL = updateURL.replace(':bookID' , bookID);

        let bookData = new FormData();
        bookData.append('bookTitle' , bookTitle);
        bookData.append('bookISBN' , bookISBN);
        bookData.append('bookPages' , bookPages);
        bookData.append('bookPrice' , bookPrice);
        bookData.append('bookAmount' , bookAmount);

        try{

            console.log(updateURL);
            $.ajax({
                url: updateURL,
                method: 'POST',
                contentType: false,
                processData: false,
                data: bookData,
                success: (response)=>{

                }
            });

        }
        catch( ex ){
            console.log('Exception: ' , ex);
        }

    });

    $('body').on('click','#removeBook,.btn-danger' , function (){

        let bookID = +$( this ).data('book-id');
        let bookTitle = $( this ).data('book-title');

        let deleteURL = `${window.paths.AjaxServerUrl}${window.paths.DeleteBook}`;
        deleteURL = deleteURL.replace(':bookID' , bookID);

        let self = $(this);

        $('#Modal').modal();
        $('#ModalTitle').html('<h3>Удаление книги</h3>');
        $('#ModalBody').html('<h5> Вы действительно хотите удалить книгу "' + bookTitle + '"?</h5>');

       $('#ConfirmButton').unbind('click');

        $("#ConfirmButton").click(function  () {

            $.ajax({

                'url': deleteURL,
                'type': 'DELETE',
                'success': ( data )=>{

                    console.log(data);
                    if( +data.code === 200 ){

                        if( self.attr('id') === 'removeBook' ){
                            location.href = `${window.paths.AjaxServerUrl}books`;
                        } 
                        else{
                            $(`tr[data-book-id=${bookID}]`).remove();
                        }

                    }

                }

            });

        });

    });

    $('#show_more').click(async function (){

        try{

            let response = await $.ajax({
                url: `${window.paths.AjaxServerUrl}${window.paths.GetBooks}`,
                method: 'GET',
                data:{
                    offset:$offset,
                    limit:$limit
                }
            });

            $.each(response.books, function (index , book) {

                console.log('book: ' , book );


                $('#booksList').append(`
                    <tr data-book-id="${ book.bookID }">
                        <td>${ book.bookID }</td>
                        <td>${ book.bookTitle }</td>
                        <td>${ book.bookISBN }</td>
                        <td>${ book.bookPages }</td>
                        <td>${ book.bookPrice }</td>
                        <td>${ book.bookAmount }</td>
                        <td>
                            <button class="btn btn-danger" data-book-id="${ book.bookID }" data-book-title="${ book.bookTitle }">Удалить</button>
                        </td>
                        <td>
                            <a class="btn btn-outline-primary" href="/BookShopMVC/public/edit-books/${ book.bookID }" data-book-id="${ book.bookID }">Редактировать</a>
                        </td>
                        <td>
                            <a class="btn btn-primary" href="/BookShopMVC/public/info-book/${ book.bookID }">Посмотреть</a>
                        </td>
                    </tr>`

                );

            });

            $offset += $limit;

            console.log(response);

        }
        catch( ex ){

            console.log(ex);

        }

    });

});
