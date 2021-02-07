"use strict";

(function () {

    $('document').ready(function () {

        $('#changeAvatarButton').click( function() {

            console.log('Change Avatar');

            $('#hiddenAvatarBlock').show();

        });

        $('#saveNewAvatar').click( async function () {

            let avatarFile = $('#avatarFile').prop('files');

            if(avatarFile.length !== 0){

                let newAvatarFile = avatarFile[0];

                let extsn = newAvatarFile.name.substring(newAvatarFile.name.lastIndexOf('.'));

                const extensions = [
                    '.jpg',
                    '.jpeg',
                    '.png',
                    '.bmp',
                ];

                extsn = extsn.toLowerCase();

                if( extensions.indexOf( extsn ) ){

                    $('#errorInput').text('Тип файла некорректен')
                        .fadeIn(500)
                        .delay( 5000 )
                        .fadeOut( 500 );

                    return;

                }

                let avatarData = new FormData();
                avatarData.append('avatarFile', newAvatarFile);

                try{

                    let url = `${window.paths.AjaxServerUrl}${window.paths.SaveNewAvatar}`;

                    let newAvatarResponse = await $.ajax({

                        url: url,
                        method: 'POST',
                        contentType: false,
                        processData: false,
                        data: avatarData

                    });

                    if( +newAvatarResponse.code === 200 ){

                        $('#successMessage').fadeIn(200).delay(5000).fadeOut(1500);

                        $('#userAvatar').attr('src' , `${newAvatarResponse.path}`);
                        $('#hiddenAvatarBlock').hide();

                    }
                    else{

                        $('#errorMessage').fadeIn(500).delay( 5000 ).fadeOut( 500 );

                    }

                }
                catch(ex){

                    $('#errorMessage').fadeIn(500).delay( 5000 ).fadeOut( 500 );

                }

            }

            $('#hiddenAvatarBlock').hide();

        });

        $('#ConfirmChangesModalButton').click( async function () {

            let newEmail = $('#newEmailInput').val();
            let newPhoneNumber = $('#newPhoneNumberInput').val();
            let newLastName = $('#newLastNameInput').val();
            let newFirstName = $('#newFirstNameInput').val();
            let newMiddleName = $('#newMiddleNameInput').val();

            if(newEmail.length === 0){

                $('#exampleModalCenter').modal('hide');
                $('#errorMessage').text('Поле Email не должно быть пустым.').fadeIn(500).delay( 5000 ).fadeOut( 500 );

                return;

            }

            if(newPhoneNumber.length === 0){

                $('#exampleModalCenter').modal('hide');
                $('#errorMessage').text('Поле телефона не должно быть пустым.').fadeIn(500).delay( 5000 ).fadeOut( 500 );

                return;

            }

            if(newLastName.length === 0){

                $('#exampleModalCenter').modal('hide');
                $('#errorMessage').text('Поле фамилии не должно быть пустым.').fadeIn(500).delay( 5000 ).fadeOut( 500 );

                return;

            }

            if(newFirstName.length === 0){

                $('#exampleModalCenter').modal('hide');
                $('#errorMessage').text('Поле имени не должно быть пустым.').fadeIn(500).delay( 5000 ).fadeOut( 500 );

                return;

            }

            if(newMiddleName.length === 0){

                $('#exampleModalCenter').modal('hide');
                $('#errorMessage').text('Поле отчества не должно быть пустым.').fadeIn(500).delay( 5000 ).fadeOut( 500 );

                return;

            }

            if(!window.ValidatorConst.USER_EMAIL_VALIDATOR.test(newEmail)){

                $('#exampleModalCenter').modal('hide');
                $('#errorMessage').text('Поле Email содержит не корректные символы!').fadeIn(500).delay( 5000 ).fadeOut( 500 );

                return;

            }

            if(!window.ValidatorConst.USER_PHONE_VALIDATOR .test(newPhoneNumber)){

                $('#exampleModalCenter').modal('hide');
                $('#errorMessage').text('Поле телефона содержит не корректные символы!').fadeIn(500).delay( 5000 ).fadeOut( 500 );

                return;

            }

            if(!window.ValidatorConst.USER_NAMES_VALIDATOR .test(newLastName)){

                $('#exampleModalCenter').modal('hide');
                $('#errorMessage').text('Поле фамилии содержит не корректные символы!').fadeIn(500).delay( 5000 ).fadeOut( 500 );

                return;

            }

            if(!window.ValidatorConst.USER_NAMES_VALIDATOR .test(newFirstName)){

                $('#exampleModalCenter').modal('hide');
                $('#errorMessage').text('Поле имени содержит не корректные символы!').fadeIn(500).delay( 5000 ).fadeOut( 500 );

                return;

            }

            if(!window.ValidatorConst.USER_NAMES_VALIDATOR .test(newMiddleName)){

                $('#exampleModalCenter').modal('hide');
                $('#errorMessage').text('Поле отчества содержит не корректные символы!').fadeIn(500).delay( 5000 ).fadeOut( 500 );

                return;

            }

            let url = `${window.paths.AjaxServerUrl}${window.paths.SaveNewPersonalData}`;

            try{

                let response = await $.ajax({

                    'url': url,
                    'method': 'PUT',
                    'data':{
                        newEmail: newEmail,
                        newPhoneNumber: newPhoneNumber,
                        newLastName: newLastName,
                        newFirstName: newFirstName,
                        newMiddleName:newMiddleName
                    },

                });

                console.log('data: ', response);

                if(+response.code === 200){

                    $('#exampleModalCenter').modal('hide');
                    $('#successMessage').text('Данные успешно обновлены!').fadeIn(500).delay( 5000 ).fadeOut( 500 );
                    return;

                }

                $('#errorMessage')
                    .text("Пользователь с такими Email или телефоном уже есть!")
                    .fadeIn(750)
                    .delay(2500)
                    .fadeOut(750);


            }
            catch( ex ){

                console.log('Exception: ', ex);
                $('#exampleModalCenter').modal('hide');

                $('#errorMessage')
                    .text(ex.responseJSON.message)
                    .fadeIn(750)
                    .delay(2500)
                    .fadeOut(750);

            }

        });

        $('#ConfirmPasswordChangesModalButton').click(function () {

            let oldPassword = $('#oldPasswordInput').val();
            let newPassword = $('#newPasswordInput').val();
            let confirmNewPassword = $('#confirmNewPassword').val();

            if(oldPassword.length === 0){

                $('#errorMessage').text('Поле старого пароля не может быть пустым').fadeIn(500).delay( 5000 ).fadeOut( 500 );
                $('#exampleModalCenter').modal('hide');

                return;

            }

            if(newPassword.length === 0){

                $('#errorMessage').text('Поле нового пароля не может быть пустым').fadeIn(500).delay( 5000 ).fadeOut( 500 );
                $('#exampleModalCenter').modal('hide');

                return;

            }

            if(confirmNewPassword.length === 0){

                $('#errorMessage').text('Поле подтверждения нового пароля не может быть пустым').fadeIn(500).delay( 5000 ).fadeOut( 500 );
                $('#exampleModalCenter').modal('hide');

                return;

            }

            if(!/^[a-z_?!^%()\d]{6,30}$/i.test(oldPassword)){

                $('#errorMessage').text('Старый пароль содержит не корректные симовлы').fadeIn(500).delay( 5000 ).fadeOut( 500 );
                $('#exampleModalCenter').modal('hide');

                return;

            }

            if(!/^[a-z_?!^%()\d]{6,30}$/i.test(newPassword)){

                $('#errorMessage').text('Новый пароль содержит не корректные симовлы').fadeIn(500).delay( 5000 ).fadeOut( 500 );
                $('#exampleModalCenter').modal('hide');

                return;

            }

            if(!/^[a-z_?!^%()\d]{6,30}$/i.test(confirmNewPassword)){

                $('#errorMessage').text('Старый пароль содержит не корректные символы').fadeIn(500).delay( 5000 ).fadeOut( 500 );
                $('#exampleModalCenter').modal('hide');

                return;

            }

            if(newPassword !== confirmNewPassword){

                $('#errorMessage').text('Новый пароль не совпадает!').fadeIn(500).delay( 5000 ).fadeOut( 500 );
                $('#exampleModalCenter').modal('hide');

                return;

            }

            let url = `${window.paths.AjaxServerUrl}${window.paths.ChangePassword}`;

            $.ajax({

               'url': url,
               'method': 'PUT',
               'data':{
                   oldPassword: oldPassword,
                   newPassword: newPassword,
                   confirmNewPassword: confirmNewPassword
               },
               'success': (data)=>{

                   if(+data.code === 200){

                       $('#successMessage').text('Пароль успешно изменён!').fadeIn(500).delay( 5000 ).fadeOut( 500 );
                       $('#exampleModalCenter').modal('hide');

                   }

               },
               'statusCode':{

                   '500': ()=>{

                       $('#errorInput')
                           .text("Ошибка загрузки данных")
                           .fadeIn(750)
                           .delay(2500)
                           .fadeOut(750);

                       $('#exampleModalCenter').modal('hide');

                   },
                   '600':()=>{

                       $('#errorInput')
                           .text("Ошибка загрузки данных. Пришёл не корректный старый пароль")
                           .fadeIn(750)
                           .delay(2500)
                           .fadeOut(750);

                       $('#exampleModalCenter').modal('hide');

                   },
                   '601':()=>{

                       $('#errorInput')
                           .text("Ошибка загрузки данных. Пришёл не корректный новый пароль")
                           .fadeIn(750)
                           .delay(2500)
                           .fadeOut(750);

                       $('#exampleModalCenter').modal('hide');

                   },
                   '602':()=>{

                       $('#errorInput')
                           .text("Ошибка загрузки данных. Пришёл не корректный подтверждённый пароль")
                           .fadeIn(750)
                           .delay(2500)
                           .fadeOut(750);

                       $('#exampleModalCenter').modal('hide');

                   },
                   '603':()=>{

                       $('#errorInput')
                           .text("Ошибка загрузки данных. Новые пароли не совпадают!")
                           .fadeIn(750)
                           .delay(2500)
                           .fadeOut(750);

                       $('#exampleModalCenter').modal('hide');

                   },
                   '605':()=>{

                       $('#errorInput')
                           .text("Ошибка загрузки данных. Пользователь не найден!")
                           .fadeIn(750)
                           .delay(2500)
                           .fadeOut(750);

                       $('#exampleModalCenter').modal('hide');

                   },

               }
            });

        });

    });

})();



