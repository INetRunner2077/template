<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Восстановление пароля");?>
<?
	if(!$USER->IsAuthorized()){$APPLICATION->IncludeComponent( "altermax:system.auth.forgotpasswd", "energoflot", false );}
	elseif( !empty( $_REQUEST["backurl"] ) ){ LocalRedirect( $_REQUEST["backurl"] );}
	else{ LocalRedirect(SITE_DIR.'personal/');}
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>