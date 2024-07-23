<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
$APPLICATION->SetTitle("Оформление заказа");

if($_REQUEST['AUTH_FORM'] == 'Y') {
    ?>
    <script>
        $('.notification').text('Введенный E-MAIl уже зарегистрирован в системе. Войдите в аккаунт либо используйте ')

        $('.notification').append($('<span class="refresh_email">другой Email</span>'));
        $('.autorize').removeClass('anim-hidden');
        $('#register-order').removeClass('anim-show');

        $('.autorize').addClass('anim-shows');
        $('#register-order').addClass('anim-hidden');
    </script>
    <?php
}

?>

<div class="notification">

</div>
<form action="" method="post" name="register-order" id="register-order" class="register-order">
    <div class="order_form">


        <div class="order-section">
            <div class="order-section-header">
                <span class="order-section-header-sign"><span>1</span></span>
                <span class="order-section-header-text">Ваши данные</span>
            </div>



            <div class="properties-table properties-1 active">
                <div class="form-row">

                    <div class="item">

                        <label for="property_NAME">Имя</label>
                        <label class="error" data-value="PROPERTIES[NAME]"></label>
                        <input class="form-control" type="text" required="required" id="property_NAME" name="PROPERTIES[NAME]" value="">
                    </div>

                    <div class="item">

                        <label for="property_FAMILY">Фамилия</label>
                        <label class="error" data-value="PROPERTIES[FAMILY]"></label>
                        <input class="form-control" type="text" id="property_FAMILY" name="PROPERTIES[FAMILY]" value="">

                    </div>
                    <div class="item">

                        <label for="property_LAST_NAME">Отчество</label>
                        <label class="error" data-value="PROPERTIES[LAST_NAME]"></label>
                        <input class="form-control" type="text" id="property_LAST_NAME" name="PROPERTIES[LAST_NAME]" value="">


                    </div>
                    <div class="item">
                        <label for="property_EMAIL">E-Mail</label>
                        <label class="error" data-value="PROPERTIES[EMAIL]"></label>
                        <input class="form-control" type="email" required="required" id="property_EMAIL" name="PROPERTIES[EMAIL]" value="">
                    </div>

                    <input type="submit" class="button_register" value="Далее">

                </div>
            </div>
        </div>
    </div>
</form>




<div class="autorize">

    <?
    $arParams = [
        "REGISTER_URL" => SITE_DIR."auth/registration/",
        "PROFILE_URL" => SITE_DIR."auth/forgot-password/",
        "SHOW_ERRORS" => "Y",
        "FORGOT_PASSWORD_URL" => SITE_DIR."auth/forgot-password/?forgot-password=yes",
        "CHANGE_PASSWORD_URL" => SITE_DIR."auth/change-password/?change-password=yes",
    ];

    $APPLICATION->IncludeComponent(
        "danil:system.auth.form",
        "ord",
        $arParams
    );
    ?>

</div>


<script>

$('#register-order').validate({
        rules: {
            "PROPERTIES[NAME]": {required: true},
            "PROPERTIES[EMAIL]": {email: true},
        },
        highlight: function( element ){
            $(element).removeClass('error');
        },
        errorPlacement: function( error, element ){
            error.insertBefore(element);
        },
        messages:{
            'PROPERTIES[NAME]': {
                required: 'Введите Имя'
            },
            'PROPERTIES[EMAIL]': {
               email: 'Введите корректный email' 
            },
        },

    });

    $('.os-order').on('click', '.refresh_email', function (event) {
        $('.autorize').removeClass('anim-shows');
        $('#register-order').removeClass('anim-hidden');

        $('.autorize').addClass('anim-hidden');
        $('#register-order').addClass('anim-shows');

        $('.notification').text('');
        $('.notification .refresh_email').remove();

    });

    $( "#register-order" ).submit(function( event ){
        
        event.preventDefault();

        if($('#register-order').valid()) {

        var formData = $('#register-order').serializeArray();

        var query = {
            c: 'opensource:order.new',
            action: 'register',
            mode: 'ajax'
        };

        /* Загрузка */
        let whiteWindow = $('#popup-window-overlay-loading_screen');
        let heart = $('#loading_screen');
        if (whiteWindow.css('display') === 'block') {
            whiteWindow.css("display", "none");
            heart.css("display", "none");
        } else {
            whiteWindow.css("display", "block");
            heart.css("display", "block");
        }
        /* Загрузка */


        var request = $.ajax({
            url: '/bitrix/services/main/ajax.php?' + $.param(query, true),
            type: 'POST',
            data: formData
        });


        request.done(function (result) {

            if(result.status == 'error') {


                for (var key in result.errors) {
                    if(result.errors[key].message == 'EMAIL_EXIST') {

                        $('.notification').text('Введенный E-MAIl уже зарегистрирован в системе. Войдите в аккаунт либо используйте ')

                         $('.notification').append($('<span class="refresh_email">другой Email</span>'));

                        var email = $('.properties-table .item input[name="PROPERTIES[EMAIL]"]').val();
                        $('.module-authorization .r input[name="USER_LOGIN"]').val(email);


                        $('.autorize').removeClass('anim-hidden');
                        $('#register-order').removeClass('anim-show');

                        $('.autorize').addClass('anim-shows');
                        $('#register-order').addClass('anim-hidden');
                    }

                    if(result.errors[key].message == 'EMAIL_INCORRECT') {

                        $('.notification').text('Некорректный E_MAIL');
                    }

                }

                /* Загрузка */
                let whiteWindow = $('#popup-window-overlay-loading_screen');
                let heart = $('#loading_screen');
                if (whiteWindow.css('display') === 'block') {
                    whiteWindow.css("display", "none");
                    heart.css("display", "none");
                } else {
                    whiteWindow.css("display", "block");
                    heart.css("display", "block");
                }
                /* Загрузка */

            }

            if(result.data == 'OK')  {

                location.reload();

            }

        });
    }

    });
</script>


<style>

.form-row .item .error {
    position: absolute!important;
    top: 3px !important;

}

    .order_form {
        flex-basis: 100% !important;
    }
    .button_register {
        width: 200px;
        height:50px;
        font-family: 'Montserrat', sans-serif!important;
        font-size:14px!important;
        background: #0D366B!important;
        color:white!important;
        padding: 8px 21px 8px 21px!important;
        cursor: pointer!important;
        font-weight: 600!important;
        border:none!important;
        border-radius: 0%!important;
    }
    .button_register:hover {
        background: green!important;
        color: white !important;
    }
    
    .notification {
        font-weight: 600;
        font-size: medium;
        color: #990000 !important;
        margin-bottom: 15px;
        cursor: pointer;
    }

   .notification .refresh_email {
       cursor:pointer;
       color:blue;
       text-decoration:underline;
   }
   .module-authorization .buttons button {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 14px !important;
    background: #0D366B !important;
    color: white !important;
    padding: 8px 21px 8px 21px !important;
    cursor: pointer !important;
    font-weight: 600 !important;
    border: none !important;
   }
   .module-authorization .buttons button:hover{
    background: green!important;
    color: white !important;
   }

</style>
