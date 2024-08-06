<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("Сменить пароль");
	
	if($USER->IsAuthorized())
	{
		$arParams = [
			"AUTH_URL" => $arParams["SEF_FOLDER"],
			"URL" => $arParams["SEF_FOLDER"].$arParams["SEF_URL_TEMPLATES"]["change"],
			//"~AUTH_RESULT" => $APPLICATION->arAuthResult,
		];

		if(isset($_SESSION['arAuthResult'])){
			$arParams['AUTH_RESULT'] = $APPLICATION->arAuthResult = $_SESSION['arAuthResult'];
			unset($_SESSION['arAuthResult']);
		}

		$APPLICATION->IncludeComponent( 
			"altermax:system.auth.changepasswd",
			"energoflot",
			$arParams,
			false 
		);
	} 
	else 
	{ 
		LocalRedirect(SITE_DIR.'personal/');
	}
	
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>