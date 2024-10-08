<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация");
?>
    <div class="more_text_big">
        <?$APPLICATION->IncludeFile(SITE_DIR."include/auth_description.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("AUTH_INCLUDE_AREA"), ));?>
    </div>

<?if(!$USER->IsAuthorized()){
    $arParams = [
        "REGISTER_URL" => SITE_DIR."auth/registration/",
        "PROFILE_URL" => SITE_DIR."auth/forgot-password/",
        "SHOW_ERRORS" => "Y",
        "FORGOT_PASSWORD_URL" => SITE_DIR."auth/forgot-password/?forgot-password=yes",
        "CHANGE_PASSWORD_URL" => SITE_DIR."auth/change-password/?change-password=yes",
    ];

    $APPLICATION->IncludeComponent(
        "altermax:system.auth.form",
        "ord",
        $arParams
    );
}elseif( !empty( $_REQUEST["backurl"] ) ){
    LocalRedirect( 'account/' );
}else{
    LocalRedirect(SITE_DIR.'account/');
}?>
    <!-- /AuthorizationForm -->

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>