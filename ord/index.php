<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("ord");
?>
    <div class="main-container">
    <div class="container has-sidebar">
    <div class="row">
<?$APPLICATION->IncludeComponent(
	"bitrix:sale.order.ajax",
	"",
Array()
);?>
    </div>
    </div>
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>