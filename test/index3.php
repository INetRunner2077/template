<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
?>
 <?$APPLICATION->IncludeComponent(
	"altermax:slider.altermax",
	"slider",
	Array(
		"GROUPS" => "",
		"IBLOCK_ID" => "41",
		"IBLOCK_TYPE" => "advertising"
	)
);?>