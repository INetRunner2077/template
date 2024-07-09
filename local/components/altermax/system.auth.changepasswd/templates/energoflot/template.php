<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(isset($APPLICATION->arAuthResult)) {
    $arResult['ERROR_MESSAGE'] = $APPLICATION->arAuthResult;

    if( $arResult['ERROR_MESSAGE']['TYPE'] === 'OK' ){
        unset($_SESSION['system.auth.changepasswd']);
    }
}?>
<?$lastLogin = (isset($_SESSION['lastLoginSave']) ? $_SESSION['lastLoginSave'] : ($arResult["LAST_LOGIN"]?:'')); ?>

<section class="main-container col2-right-layout">
    <div class="main container">
        <div class="row">

<?if($arResult['ERROR_MESSAGE']):?>
    <p><div class="alert <?=($arResult['ERROR_MESSAGE']['TYPE'] === "OK"? "alert-success" : "alert-danger")?>"><?=$arResult['ERROR_MESSAGE']['MESSAGE'].($arResult['ERROR_MESSAGE']['TYPE'] == 'OK' ? GetMessage("CHANGE_SUCCESS") : '')?></div></p>
    <?if(!$arResult['ERROR_MESSAGE'] || $arResult['ERROR_MESSAGE']['TYPE'] === 'OK'){?>
        <div class="but-r"><a href="/auth/" class="danil_button_target"><span><?=GetMessage("LOGIN")?></span></a></div>
    <?}?>
<?else:?>
    <?
    if( isset($_POST["LAST_LOGIN"]) && empty( $_POST["LAST_LOGIN"] ) ){
        $arResult["ERRORS"]["LAST_LOGIN"] = GetMessage("REQUIRED_FIELD");
    }
    if( isset($_POST["USER_PASSWORD"]) && strlen( $_POST["USER_PASSWORD"] ) < 6 ){
        $arResult["ERRORS"]["USER_PASSWORD"] = GetMessage("PASSWORD_MIN_LENGTH_2");
    }
    if( isset($_POST["USER_PASSWORD"]) && empty( $_POST["USER_PASSWORD"] ) ){
        $arResult["ERRORS"]["USER_PASSWORD"] = GetMessage("REQUIRED_FIELD");
    }
    if( isset($_POST["USER_CONFIRM_PASSWORD"]) && strlen( $_POST["USER_CONFIRM_PASSWORD"] ) < 6 ){
        $arResult["ERRORS"]["USER_CONFIRM_PASSWORD"] = GetMessage("PASSWORD_MIN_LENGTH_2");
    }
    if( isset($_POST["USER_CONFIRM_PASSWORD"]) && empty( $_POST["USER_CONFIRM_PASSWORD"] ) ){
        $arResult["ERRORS"]["USER_CONFIRM_PASSWORD"] = GetMessage("REQUIRED_FIELD");
    }
    if( $_POST["USER_PASSWORD"] != $_POST["USER_CONFIRM_PASSWORD"] ){
        $arResult["ERRORS"]["USER_CONFIRM_PASSWORD"] = GetMessage("WRONG_PASSWORD_CONFIRM");
    }
    if ($arResult['SHOW_ERRORS'] == 'Y' ){
        ShowMessage($arResult['ERROR_MESSAGE']);?>
        <p><font class="errortext"><?=GetMessage("WRONG_LOGIN_OR_PASSWORD")?></font></p>
    <?}?>
<?endif;?>
<?if(!$arResult['ERROR_MESSAGE'] || $arResult['ERROR_MESSAGE']['TYPE'] !== 'OK'){?>

            <div class="col col-sm-12 col-xs-12">
                <div class="col-sm-6">
                    <h5>Восстановление пароля</h5>
                    <form method="post" action="/auth/change-password/">

                        <?if (strlen($arResult["BACKURL"]) > 0): ?>
                            <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" /><?endif;?>
                        <input type="hidden" name="AUTH_FORM" value="Y">
                        <input type="hidden" name="TYPE" value="CHANGE_PWD">

                        <input type="text" placeholder="<?= GetMessage("AUTH_LOGIN"); ?> *" maxlength="50" value="<?=$lastLogin?>" class="form-control input <?=($_POST && empty($_POST["USER_LOGIN"]) ? "error": '')?>" required />
                        <input type="hidden" autocomplete="off" name="USER_LOGIN" value="<?=$lastLogin?>" />


                        <?if($arResult["USE_PASSWORD"]): ?>
                      <input type="password" placeholder="<?=GetMessage("AUTH_CURRENT_PASSWORD")?> *" name="USER_CURRENT_PASSWORD" id="USER_CURRENT_PASSWORD" maxlength="50" required value="<?=$arResult["USER_CURRENT_PASSWORD"]?>" class="form-control input <?=( isset($arResult["ERRORS"]) && array_key_exists( "USER_CURRENT_PASSWORD", $arResult["ERRORS"] ))? "error": ''?>" />

                        <?else:?>
                            <input type="hidden" name="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" class="bx-auth-input"  />
                        <?endif;?>

                        <label>Новый пароль</label>
                        <input type="password" autocomplete="off" placeholder="<?=GetMessage("AUTH_NEW_PASSWORD_REQ")?> *" name="USER_PASSWORD" maxlength="50" id="pass" required value="<?=$arResult["USER_PASSWORD"]?>" class="form-control input <?=( isset($arResult["ERRORS"]) && array_key_exists( "USER_PASSWORD", $arResult["ERRORS"] ))? "error": ''?>" />

                        <label>Подтверждение пароля</label>
                        <input type="password" autocomplete="off" placeholder="<?=GetMessage("AUTH_NEW_PASSWORD_CONFIRM")?> *" name="USER_CONFIRM_PASSWORD" maxlength="50" required value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" class="form-control input <?=(isset($arResult["ERRORS"]) && array_key_exists( "USER_CONFIRM_PASSWORD", $arResult["ERRORS"] ))? "error": ''?>"  />

                        <?if ($arResult["USE_CAPTCHA"]):?>
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


                        <p>Укажите код, который отправлен Вам на email.</p>
                        <button class="button button-green"><i class="icon-login"></i>&nbsp;
                            <span>Восстановить пароль</span></button>
                    </form>
                </div>
                <div class="col-sm-6"></div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $(".main form").validate({
                rules:{
                    USER_CONFIRM_PASSWORD: {equalTo: '#pass'},
                },
                messages:{USER_CONFIRM_PASSWORD: {equalTo: '<?=GetMessage("PASSWORDS_DONT_MATCH")?>'}},
                submitHandler: function( form ){
                    if( $('form[name=bform]').valid() ){
                        var eventdata = {type: 'form_submit', form: form, form_name: 'FORGOT'};
                        BX.onCustomEvent('onSubmitForm', [eventdata]);
                    }
                },
            });
        })
    </script>
    <script type="text/javascript">document.bform.USER_LOGIN.focus();</script>
    <?}?>
</section>