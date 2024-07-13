<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина"); ?><?$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket",
	"",
Array(),
false
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>