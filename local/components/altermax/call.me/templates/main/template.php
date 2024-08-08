<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="form_div">
<form method="post" id="call" enctype="multipart/form-data">
    <input type="hidden" class="form-control input" name="template"  value="CALL_ME">
     <div class="input_container">
    <label class="require">Как к вам обращаться*</label>
    <input type="text" class="form-control input" name="name" value="<?=$arResult['USER']['NAME']?>" required="">
     </div>
    <div class="input_container">
    <label>Номер телефона*</label>
    <input type="text" class="form-control input phone-inp-mask" name="phone"  required="" value="">
    </div>
    <div class="input_container">
    <label>Email*</label>
    <input type="email" class="form-control input" name="email" value="<?=$arResult['USER']['EMAIL']?>" required="">
    </div>
    <div class="input_container">
    <label>Укажите комментарий при необходимости</label>
    <textarea class="form-control" name="comments"></textarea>
    </div>
    <button class="button" type="submit"><i class="fa fa-send"></i>&nbsp; <span>Отправить</span></button>
</form>
</div>
<script>


    $( "#call" ).submit(function(event) {

        event.preventDefault();
        if($('#call').valid()) {
            var $form = $(this);
            $.ajax({
                type: "POST",
                url: "/ajax/formSend.php",
                data: $form.serialize(),
                dataType: "json",
                success: function (data) {
                    if(data.STATUS == "OK") {
                        $('.alert').remove();
                        $('#call').remove();
                       $('.form_div').prepend('<div class="alert success"><strong class="alert-success">Форма успешна отправлена</strong> </div>');
                    } else {
                        $('.alert').remove();
                        $('.form_div').prepend('<div class="alert alert-danger"><strong>Ошибка!</strong>Форма не отправлена</div>');
                    }
                }
            })


        }
    });


    $('#call').validate({
        rules: {
            "email": {email: true},
            "name": {required: true},
            "phone": {required: true},
        },
        highlight: function (element) {
            $(element).removeClass('error');
        },
        errorPlacement: function( error, element ){
            error.insertBefore(element);
            error.addClass("errorCust");
        },
        messages: {
            'phone': {
                required: 'Введите телефон'
            },
            'email': {
                email: 'Введите корректный email',
                required: 'Заполните поле email',
            },
            'name': {
                required: 'Введите Имя'
            },
        },

    });


</script>