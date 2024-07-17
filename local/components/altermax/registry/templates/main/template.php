<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<section class="main-container col2-right-layout">
    <div class="main container">
<div class="register-ressi">
<? if(!empty($arResult['ERRORS'])): ?>
    <div class="error">
        <p><strong class="error-span">Ошибка!</strong> <?=$arResult['ERRORS'][0]?></p>
    </div>
    <? endif; ?>
    <h2>  Регистрация нового пользователя  </h2>
    <form method="post" autocomplete="off" id='registraion-page-form' action="<?=$_SERVER['REQUEST_URI']?>">

   
            <div class="registraion-page" class='register_form'>
                <input type="hidden" name="register_submit_button" value="reg">

                <input size="30" id="input_LOGIN" type="hidden" value="1"
                       name="REGISTER[LOGIN]">
                <div class="form-control bg">
                    <div class="wrap_md">
                        <div class="iblock label_block">
                            <input type="email" autocomplete="off" placeholder="E-mail *" class="form-control input" value="<?=$arResult["VALUES"]['EMAIL']?>" name="email"required="">
                            <p class="color-orange">Введите действующий email, к которому у Вас есть доступ. Этот же адрес будет использоватся в качестве логина.</p>
                        </div>
                    </div>
                </div>
                <div class="form-control bg">
                    <div class="wrap_md">
                        <div class="iblock label_block">
                                <input type="password" autocomplete="off" placeholder="Пароль *" class="form-control input" name="pass" value="<?=$arResult["VALUES"]['PASS']?>" minlength="8" required="">
                                <p class="color-orange">Придумайте надежный пароль (не менее 8 символов).</p>
                        </div>
                    </div>
                </div>

                <div class="form-control bg register-captcha captcha-row clearfix">
                    <label class="label_captha">
                        <span>  Введите капчу:&nbsp;</span>
                    </label>
                    <div class="iblock label_block_captha">
                        <div class="iblock_captha_container">
                        <div class="captcha_image">
                            <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" border="0" />
                            <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                            <div class="captcha_reload">Релоад</div>
                        </div>
                        <div class="captcha_input">
                            <input type="text" class="inputtext captcha" name="captcha_word" size="30" maxlength="50" value="" required />
                        </div>
                        </div>
                    </div>
                    <div class="iblock text_block"></div>
                </div>
                <div class="licence_block filter">
                    <input type="checkbox" id="licenses_popup" checked="" name="licenses_popup" required="" value="Y" aria-required="true">
                    <label for="licenses_popup">
                        Я согласен на <a href="/include/licenses_detail.php" target="_blank">обработку персональных
                            данных</a> </label>
                </div>

                <p class="text-right">
                    <input type="hidden" name="save" value="Y">
                    <button class="danil_button_target button button-green h4-size" type="submit"><span>Регистрация</span></button>
                </p>

        </form>

</div>
    </div>
</section>

<script>


$('#registraion-page-form').validate({
        rules: {
            "email": {email : true},
            "pass": {required: true, minlength: 8},
        },
        highlight: function( element ){
            $(element).removeClass('error');
        },
        errorPlacement: function( error, element ){
            error.insertBefore(element);
        },
        messages:{
            'email': {
                email : 'Введите корректный email'
            },
            'pass': {
                required: 'Придумайте пароль',
                minlength: 'Минимум 8 символов!'
            },
        },

    });

</script>