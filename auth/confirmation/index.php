<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("Подтверждение регистрации	");
	
	if($USER->IsAuthorized())
	{?>
		<?$APPLICATION->IncludeComponent(
	"altermax:system.auth.confirmation",
	"flat", 
	array(
		"USER_ID" => "confirm_user_id",
		"CONFIRM_CODE" => "confirm_code",
		"LOGIN" => "login",
		"COMPONENT_TEMPLATE" => "flat"
	),
	false
);?>
	<?} 
	else 
	{ 
		LocalRedirect(SITE_DIR.'personal/');
	}
	
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>