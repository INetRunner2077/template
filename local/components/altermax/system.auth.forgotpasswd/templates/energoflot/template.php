<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(isset($APPLICATION->arAuthResult))
    $arResult['ERROR_MESSAGE'] = $APPLICATION->arAuthResult;?>


<?
if ($arResult["BACKURL"] <> '')
{
    ?>
    <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
    <?
}
?>

<section class="main-container col2-right-layout">
    <div class="main container">
        <div class="row">
            <div class="col col-sm-12 col-xs-12">
                <?ShowMessage($arResult['ERROR_MESSAGE']);?>
                <div class="col-sm-6">
                    <h5>Восстановление пароля</h5>
                    <form method="post" name="bform" action="<?=$arResult["AUTH_URL"]?>">
                        <input type="hidden" name="AUTH_FORM" value="Y">
                        <input type="hidden" name="TYPE" value="SEND_PWD">
                        <label>E-mail</label>
                        <input type="text" class="form-control input" name="USER_LOGIN" value="<?=$arResult["USER_LOGIN"]?>" />
                        <input type="hidden" name="USER_EMAIL" />
                        <p>Укажите email, который вводили при регистрации.</p>
                        <?if ($arResult["USE_CAPTCHA"]):?>
                            <div class="form-control captcha-row clearfix">
                                <label><span><?=GetMessage("FORM_CAPTCHA_TITLE")?>&nbsp;<span class="star">*</span></span></label>
                                <div class="captcha_image">
                                    <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
                                    <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                                    <div class="captcha_reload"></div>
                                </div>
                                <div class="captcha_input">
                                    <input type="text" class="inputtext captcha" name="captcha_word" size="30" maxlength="50" value="" required />
                                </div>
                            </div>
                        <?endif?>
                        <button class="button button-green"><i class="icon-login"></i>&nbsp; <span>Восстановить пароль</span></button>
                    </form>
                </div>
                <div class="col-sm-6"></div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    document.bform.onsubmit = function(){document.bform.USER_EMAIL.value = document.bform.USER_LOGIN.value;};
    document.bform.USER_LOGIN.focus();
</script>