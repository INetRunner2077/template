<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет"); ?><?$APPLICATION->IncludeComponent(
	"altermax:order.section",
	"main",
	Array(
		"0" => "=",
		"1" => "{",
		"2" => "f",
		"3" => "a",
		"4" => "l",
		"5" => "s",
		"6" => "e",
		"7" => "}"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>