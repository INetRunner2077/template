<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<section class="main-container col2-right-layout">
    <div class="main container">
        <div class="row">
            <div class="col col-sm-12 col-xs-12 register-container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="price-col register-bx">
                            <h3 class="text-uppercase color-blue">Регистрация нового пользователя</h3>
                            <br>
                            <? if(!empty($arResult['ERRORS'])): ?>
                                <div class="error">
                                    <p><strong class="error-span">Ошибка!</strong> <?=$arResult['ERRORS'][0]?></p>
                                </div>
                            <? endif; ?>
                            <? if(!empty($arResult['ALLERT'])): ?>
                                <div class="success">
                                    <p><?=$arResult['ALLERT'][0]?></p>
                                </div>
                            <? endif; ?>
                            <form method="post" autocomplete="off" id='registraion-page-form' action="<?=$_SERVER['REQUEST_URI']?>">
                                <div class="row">
                                    <div class="col-md-3 text-left">
                                        <label class="required">E-mail</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control input" value="<?=$arResult["VALUES"]['EMAIL']?>" name="email" required="">
                                        <p class="color-orange">Введите действующий email, к которому у Вас есть доступ. Этот же адрес будет использоватся в качестве логина.</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 text-left">
                                        <label class="required">Пароль</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control input" name="pass" value="<?=$arResult["VALUES"]['PASS']?>" minlength="8" required="">
                                        <p class="color-orange">Придумайте надежный пароль (не менее 8 символов).</p>
                                    </div>
                                </div>

                               <div class="form-control bg register-captcha captcha-row clearfix">
                                    <label class="label_captha">
                                        <span>  Введите капчу:&nbsp;</span>
                                    </label>
                                    <div class="iblock label_block_captha">
                                        <div class="iblock_captha_container">
                                            <div class="captcha_image">
                                                <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?> border="0" />
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


                                <p class="text-right">
                                    <button class="button button-green h4-size" name="save" value="Y" type="submit"><span>Регистрация</span></button>
                                </p>
                            </form>
                        </div><!-- .price-col -->
                    </div>
                </div>
            </div>
        </div>
        <!-- service section -->
        <?$APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_RECURSIVE" => "Y",
                "AREA_FILE_SHOW" => "file",
                "AREA_FILE_SUFFIX" => "inc",
                "EDIT_TEMPLATE" => "standard.php",
                "PATH" => "/bitrix/modules/altermax.shop/include/banner.php"
            )
        );?>
        <!-- .jtv-service-area -->
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
            error.addClass("errorCust");
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