<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
	die();
}
/** @var array $arCurrentValues */

use Bitrix\Main\Loader;

if (!Loader::includeModule('iblock'))
{
	return;
}

$iblockExists = (!empty($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID'] > 0);

$arTypesEx = CIBlockParameters::GetIBlockTypes();

$arIBlocks = [];
$iblockFilter = [
	'ACTIVE' => 'Y',
];
if (!empty($arCurrentValues['IBLOCK_TYPE']))
{
	$iblockFilter['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];
}
if (isset($_REQUEST['site']))
{
	$iblockFilter['SITE_ID'] = $_REQUEST['site'];
}
$db_iblock = CIBlock::GetList(["SORT"=>"ASC"], $iblockFilter);
while($arRes = $db_iblock->Fetch())
{
	$arIBlocks[$arRes["ID"]] = "[" . $arRes["ID"] . "] " . $arRes["NAME"];
}

$arSorts = [
	"ASC"=>GetMessage("T_IBLOCK_DESC_ASC"),
	"DESC"=>GetMessage("T_IBLOCK_DESC_DESC"),
];
$arSortFields = [
	"ID"=>GetMessage("T_IBLOCK_DESC_FID"),
	"NAME"=>GetMessage("T_IBLOCK_DESC_FNAME"),
	"ACTIVE_FROM"=>GetMessage("T_IBLOCK_DESC_FACT"),
	"SORT"=>GetMessage("T_IBLOCK_DESC_FSORT"),
	"TIMESTAMP_X"=>GetMessage("T_IBLOCK_DESC_FTSAMP"),
];

$arProperty_LNS = [];
$arProperty = [];
if ($iblockExists)
{
	$rsProp = CIBlockProperty::GetList(
		[
			"SORT" => "ASC",
			"NAME" => "ASC",
		],
		[
			"ACTIVE" => "Y",
			"IBLOCK_ID" => $arCurrentValues["IBLOCK_ID"],
		]
	);
	while ($arr = $rsProp->Fetch())
	{
		$arProperty[$arr["CODE"]] = "[" . $arr["CODE"] . "] " . $arr["NAME"];
		if (in_array($arr["PROPERTY_TYPE"], ["L", "N", "S"]))
		{
			$arProperty_LNS[$arr["CODE"]] = "[" . $arr["CODE"] . "] " . $arr["NAME"];
		}
	}
}

$arComponentParameters = [
	"PARAMETERS" => [
		"IBLOCK_TYPE" => [
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_LIST_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
			"DEFAULT" => "news",
			"REFRESH" => "Y",
		],
		"IBLOCK_ID" => [
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_LIST_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => '={$_REQUEST["ID"]}',
			"ADDITIONAL_VALUES" => "Y",
			"REFRESH" => "Y",
		],
	],
];
