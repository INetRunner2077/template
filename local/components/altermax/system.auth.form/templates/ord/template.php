<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<? if($arResult['ERROR'] &&$arResult['ERROR_MESSAGE']['TYPE'] === 'ERROR' && $arResult['ERROR_MESSAGE']['ERROR_TYPE'] === 'CHANGE_PASSWORD' &&$arParams['CHANGE_PASSWORD_URL']):?>
	<?$_SESSION['arAuthResult'] = $APPLICATION->arAuthResult;
	$_SESSION['lastLoginSave'] = $arResult["USER_LOGIN"];
	?>
	<script>
	location.href = '<?=$arParams['CHANGE_PASSWORD_URL'].(strlen($arResult['BACKURL']) ? (strpos($arParams['CHANGE_PASSWORD_URL'], '?') ? '&' : '?').'backurl='.$arResult['BACKURL'] : '')?>';
	</script>
<?else:?>
<section class="main-container col2-right-layout">
    <div class="main container">
    <?if(!$USER->isAuthorized()):?>
        <div class="row">
            <div class="col col-sm-12 col-xs-12">
                <?if($arResult['SHOW_ERRORS'] == 'Y'):?>
                    <?ShowMessage($arResult['ERROR_MESSAGE']);?>
                <?endif;?>
                <div class="col-sm-6">
                    <h5>Вход</h5>
                    <form  name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=SITE_DIR?>auth/<?=!empty( $_REQUEST["backurl"] ) ? '?backurl='.$_REQUEST["backurl"] : ''?>">
                        <input type="hidden" name="AUTH_FORM" value="Y" />
                        <input type="hidden" name="TYPE" value="AUTH" />
                        <label>E-mail</label>
                        <input type="text" class="form-control input" name="USER_LOGIN" value="<?=$arResult["USER_LOGIN"]?>">
                        <label>Пароль</label>
                        <input type="password" class="form-control input" name="USER_PASSWORD">

                        <?if ($arResult["CAPTCHA_CODE"]):?>
                        <div class="bg register-captcha captcha-row clearfix">
                            <div class="iblock label_block_captha">
                                <?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:<br />
                                <div class="iblock_captha_container">
                                <div class="captcha_image">
                                    <input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
                                    <img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
                                    <div class="captcha_reload"> Релоад </div>
                                </div>
                                <div class="captcha_input">
                                    <input type="text" class="inputtext captcha" name="captcha_word" maxlength="50" value="" />
                                </div>
                                </div>
                            </div>
                        </div>
                        <?endif?>

                        <p>
                            <a href="/account?passwordforgot" class="form-link">Забыли пароль?</a>
                        </p>
                        <button class="button button-green"><i class="icon-login"></i>&nbsp; <span>Вход</span></button>
                    </form>
                </div>
                <div class="col-sm-6">
                    <h5>Зарегистрироваться</h5>
                    <br>
                    <p>Регистрация в интернет-магазине дает возможность быстрее оформлять заказы, а так же просматривать статус своих заказов.</p>
                    <br>
                    <p>Регистрация занимает 1-2 минуты.</p>
                    <br><br>
                    <form method="post" action="/auth/registration">
                        <input type="hidden" name="type" value="new">
                        <button class="button button-orange"><i class="icon-login"></i>&nbsp; <span>Зарегистрироваться</span></button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>
<?endif;?>


<?php endif; ?>