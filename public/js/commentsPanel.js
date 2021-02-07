$(document).ready( function (  ){

    let limit = 2;
    let offset = 2;
    let commentID = -1;
    let userId = -1;

    $('body').on('click', '#ApproveButton', function () {
        if ($('#commentsComboBox option:selected').data('status-id')=== 2){
            return;
        }
        let commentID = $(this).data('comment-id');
                $.ajax({
                    'url': `${window.paths.AjaxServerUrl}${window.paths.UpdateStatus}`,
                    'type': 'PUT',
                    'data': {
                        'commentStatus': 2,
                        'commentID': commentID,


                    },
                    'success': (data) =>{


                        let status = +data .code;


                        if( status === 200){

                            $(`div[data-comment-id=${commentID}]`).remove();
                            $('#errorMessage').fadeOut(1000);
                            $('#successMessage').text("Комментарий успешно обновлен!").fadeIn(1000).delay( 5000 ).fadeOut( 500 );
                        }
                        else{
                            $('#successMessage').fadeOut(1000);
                            $('#errorMessage').fadeIn(1000).delay( 5000 ).fadeOut( 500 );
                        }

                        offset--;
                        commentID = null;
                    }
                });


    }  );
    $('body').on('click', '#RejectButton', function () {
        if ($('#commentsComboBox option:selected').data('status-id')=== 3){
            return;
        }
                let commentID = $(this).data('comment-id');
                $.ajax({
                    'url': `${window.paths.AjaxServerUrl}${window.paths.UpdateStatus}`,
                    'type': 'PUT',
                    'data': {
                        'commentStatus': 3,
                        'commentID': commentID,


                    },
                    'success': (data) =>{


                        let status = +data .code;


                        if( status === 200){

                            $(`div[data-comment-id=${commentID}]`).remove();
                            $('#errorMessage').fadeOut(1000);
                            $('#successMessage').text("Комментарий успешно обновлен!").fadeIn(1000).delay( 5000 ).fadeOut( 500 );
                        }
                        else{
                            $('#successMessage').fadeOut(1000);
                            $('#errorMessage').fadeIn(1000).delay( 5000 ).fadeOut( 500 );
                        }

                        offset--;
                        commentID = null;
                    }
                });


    });

    $('body').on('change', '#commentsComboBox', function () {
        let status = $('#commentsComboBox option:selected').data('status-id');
        location.href = `${window.paths.AjaxServerUrl}${window.paths.ModerationComments}${status}`;
    });

    $('#MoreButton').click(function () {

        let status = $('#commentsComboBox option:selected').data('status-id');

        let URL = `${window.paths.AjaxServerUrl}${window.paths.ModerationMoreComments}${status}`;


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

                        $('#CommentsModeratedList').append(`
                                                   <div data-comment-id="${ comment.comment.commentID }" class="card w-100">
                                                        <div class="card-body">
                                        
                                                            <h3 class="card-title">Книга: ${ comment.book.bookTitle }.</h3>
                                        
                                                            <div data-comment-for-update-id="${ comment.comment.commentID }" class="card-text">${ comment.comment.commentText }</div>
                                        
                                                            <h5 class="card-title">${ comment.user.userLogin } Дата: ${ comment.date }</h5>
                                        
                                                            <div id="ApproveButton" class="btn btn-primary"  data-comment-id="${ comment.comment.commentID }" >Одобрить</div>
                                        
                                                            <div id="RejectButton" class="btn btn-danger"   data-comment-id="${ comment.comment.commentID }" >Отклонить</div></a>
                                        
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