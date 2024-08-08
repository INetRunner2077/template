<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
?><?$APPLICATION->IncludeComponent(
	"bitrix:sale.order.ajax",
	"",
Array()
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>