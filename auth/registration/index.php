<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("Регистрация");

	if($USER->IsAuthorized())
	{?>
	
	<?
		$APPLICATION->IncludeComponent(
			"altermax:registry",
			"main",
			Array(
				"SUCCESS_PAGE" => "/register2",
			)
		);
		$_REQUEST["REGISTER[LOGIN]"] = $_REQUEST["REGISTER[EMAIL]"];
	} elseif(!empty( $_REQUEST["backurl"] )) {LocalRedirect( $_REQUEST["backurl"] );} else { LocalRedirect(SITE_DIR.'personal/');}

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>