$(document).ready( function (  ){


    let limit = 2;
    let offset = 2;
    let commentID = -1;
    let statusID = -1;
    let userId = -1;
    let deleteURL = null;

    $('body').on('click', '#AddCommentButton', async function () {

        let text = $('#AddCommentInput').val();

        if (text.length <= 4) {
            $('#errorInput').css("display", "block");
            return;

        }

        $('#errorInput').css("display", "none");

        let bookId = $(this).data('book-id');

        let url = `${window.paths.AjaxServerUserUrl}${window.paths.AddComment}`;

        try{

            let response = await $.ajax({
                'url': url,
                'type': 'POST',
                'data': {
                    'text': text,
                    'bookId': bookId
                }
            });

            $('#errorMessage').fadeOut(1000);
            $('#successMessage').fadeIn(1000).delay( 5000 ).fadeOut( 500 );

        }
        catch( ex ){

            $('#successMessage').fadeOut(1000);
            $('#errorMessage').fadeIn(1000).delay( 5000 ).fadeOut( 500 );

        }

    }  );

    $('body').on('click', '#ConfirmButton', function () {

        if(!deleteURL ||
            commentID === -1){
            return;
        }

        $.ajax({
            'url': deleteURL,
            'type': 'DELETE',
            'success': ( data )=>{

                if( +data.code === 200 ){

                    offset--;


                        $(`div[data-comment-id=${commentID}]`).remove();

                }
                deleteURL = null;
                commentID = -1;
            return;
            }
        });
    });

    $('body').on('click' , '#UpdateButton' ,function () {

        let newText = $('#UpdateCommentInput').val();
        if (newText.length <= 4) {
            $('#errorInput').css("display", "block");

        }
        else{

            $('#errorInput').css("display", "none");

            let option = $(`#ModalUpdate option:selected`);
            statusID = +$(option).data('status-id');


            $.ajax({
                'url': `${window.paths.AjaxServerUrl}${window.paths.UpdateComment}`,
                'type': 'PUT',
                'data': {
                    'text': newText,
                    'commentID': commentID,
                    'statusID': statusID

                },
                'success': (data) =>{


                    let status = +data .code;


                    if( status === 200){

                        $(`div[data-comment-for-update-id=${commentID}]`).text(data.text);
                        $('#errorMessage').fadeOut(1000);
                        $('#successMessage').text("Комментарий успешно обновлен!").fadeIn(1000).delay( 5000 ).fadeOut( 500 );
                    }
                    else{
                        $('#successMessage').fadeOut(1000);
                        $('#errorMessage').fadeIn(1000).delay( 5000 ).fadeOut( 500 );
                    }

                    commentID = -1;
                }
            });
        }


    } );

    $('body').on('click', '#EditButton', function () {

        commentID = $(this).data('comment-id');
        let commentText = $(`div[data-comment-for-update-id=${commentID}]`).text();

        statusID = +$(this).data('status-id');
        $('#UpdateCommentInput').val( commentText );

        let option = $(`#ModalUpdate option[data-status-id="${statusID}"]`);

        if(option){

            option.selected = true;

        }
        
        $('#ModalUpdate').modal();

    }  );

    $('body').on('click','#DeleteButton' , function (  ){

        commentID = +$( this ).data('comment-id');
        let currentUserId = +$('#CommentsList').data('current-user-id');
        userId = +$( this ).data('user-id');

        if(currentUserId === userId){
            deleteURL = `${window.paths.AjaxServerUrl}${window.paths.RemoveComment}`;
            deleteURL = deleteURL.replace(':commentID' , commentID);
            let self = $(this);

            $('#Modal').modal();
            $('#ModalTitle').html('<h5> Удаление комментария</h5>');
            $('#ModalBody').html('<h5> Вы действительно хотите удалить комментарий :?</h5>');

        }
        else{
            $('#Modal').modal();
            $('#ModalTitle').html('<h5> Удаление комментария</h5>');
            $('#ModalBody').html('<h5> Вы можете удалять только свои сообщения!</h5>');
            $('#ConfirmButton').hide();
        }


    }  );

    $('#MoreButton').click(function () {

        let bookId = $(this).data('book-id');

        let URL = `${window.paths.AjaxServerUrl}${window.paths.MoreComments}`;
        URL = URL.replace(':bookId' , bookId);


        $.ajax({
            'url': URL,
            'type': 'GET',
            'data': {
                limit: limit,
                offset: offset,

            },
            'success': (data) =>{

                let status = +data .status;
                let comments = data .comments;

                if( status === 200){

                    comments.forEach(comment => {

                        $('#CommentsList').append(`
                                                   <div data-comment-id="${ comment.comment.commentID }" class="card w-100">
                                                        <div class="card-body">
                                                            <h5 class="card-title">${ comment.user.userLogin } Дата: ${ comment.date }</h5>
                                                            <div data-comment-for-update-id="${ comment.comment.commentID }" class="card-text">${ comment.comment.commentText }</div>
                                                            <a href="#" >
                                                                <i id="EditButton" data-user-id="${ comment.comment.userID }" data-comment-id="${ comment.comment.commentID }" class="far fa-edit fa-2x"></i>
                                                            </a>
                                                            <a href="#" ><i id="DeleteButton" data-user-id="${ comment.comment.userID }" data-comment-id="${ comment.comment.commentID }" class="btn-danger far fa-trash-alt fa-2x"></i>
                                                            </a>

                                                        </div>
                                                   </div>`
                        );

                    });

                    if(comments.length < limit ){
                        $('#More').css("display", "none");
                    }

                    offset += limit;
                }

            }
        });
    })

});


