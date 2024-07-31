<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>
<?php

use \Bitrix\Catalog\Model\Price;
use \Bitrix\Main\Loader;

Loader::IncludeModule('search');
Loader::IncludeModule('sale');

$arFilter = array (
    'SITE_ID' => SITE_ID,
    'QUERY' => $_REQUEST['name'],
    'CHECK_DATES' => 'Y',
    'MODULE_ID' => 'iblock',
    "PARAM2" => $_REQUEST['iblock'] //инфоблок id
);

$aSort = array (
    'RANK' => 'DESC',
    'DATE_CHANGE' => 'DESC',
);

$exFILTER = array (
    0 =>
        array (
            '=MODULE_ID' => 'iblock',
        ),
    'STEMMING' => true,
);

$obSearch = new CSearch();

$obSearch->Search($arFilter, $aSort, $exFILTER);

while($result = $obSearch->Fetch()) {

if($result['ITEM_ID'] == $_REQUEST['itemId']) {
    continue;
}
$arrReturn[$result['ITEM_ID']] = $result;
$ids[] = $result['ITEM_ID'];
}

if(empty($ids)) {
    $arFilter['QUERY'] = $_REQUEST['categoryname'];
    $obSearch->Search($arFilter, $aSort, $exFILTER);
    while($result = $obSearch->Fetch()) {

        if($result['ITEM_ID'] == $_REQUEST['itemId']) {
            continue;
        }
        $arrReturn[$result['ITEM_ID']] = $result;
        $ids[] = $result['ITEM_ID'];
    }
}


/*
Loader::includeModule('catalog');
$arrCountProduct = \Bitrix\Catalog\ProductTable::getList(
    [

        'filter' => ['ID' => $ids],
    ]
);

while ($CountProduct = $arrCountProduct->fetch()) {



}
*/


if(empty($ids)) {
        die();
}

$arrPrice = Price::getList(
    array(
        'filter' => array('PRODUCT_ID' => $ids),
        'select' => array('PRICE', 'PRODUCT_ID'),
    )
);
$ids = array();
while ($price = $arrPrice->fetch()) {
        $ids[] = $price['PRODUCT_ID'];
}

if(empty($arrReturn)) {
        die();
}


$dbBasketItems = CSaleBasket::GetList(
    array(
        "NAME" => "ASC",
        "ID" => "ASC"
    ),
    array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID" => SITE_ID,
        "PRODUCT_ID" => $ids,
        "ORDER_ID" => "NULL",
        "DELAY" => "N"
    ),
    false,
    false,
    array("PRODUCT_ID")
);
while ($arItemsBasket = $dbBasketItems->Fetch()) {
    $arrReturn[$arItemsBasket['PRODUCT_ID']]['BASKET'] = true;
}



GLOBAL $arrFilter, $APPLICATION;
if (!is_array($arrFilter))
    $arrFilter = array();
$arrFilter['ID'] = $ids;


$APPLICATION->ShowAjaxHead();

$APPLICATION->IncludeComponent(
    "new:catalog.top",
    ".default",
    array(
        "ACTION_VARIABLE" => "action",
        "ADD_PICT_PROP" => "-",
        "ADD_PROPERTIES_TO_BASKET" => "Y",
        "ADD_TO_BASKET_ACTION" => "BUY",
        "BASKET_URL" => "/basket/",
       // "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "COMPARE_NAME" => "CATALOG_COMPARE_LIST",
        "COMPATIBLE_MODE" => "N",
        "CONVERT_CURRENCY" => "N",
        "CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
        "DETAIL_URL" => "",
        "DISPLAY_COMPARE" => "N",
        "ELEMENT_COUNT" => "16",
        "ELEMENT_SORT_FIELD" => "sort",
        "ELEMENT_SORT_FIELD2" => "id",
        "ELEMENT_SORT_ORDER" => "asc",
        "ELEMENT_SORT_ORDER2" => "desc",
        "ENLARGE_PRODUCT" => "STRICT",
        "FILTER_NAME" => "arrFilter",
        "HIDE_NOT_AVAILABLE" => "N",
        "HIDE_NOT_AVAILABLE_OFFERS" => "N",
        "IBLOCK_ID" => $_REQUEST['iblock'],
        "IBLOCK_TYPE" => $_REQUEST['iblocktype'],
        "LABEL_PROP" => array(
        ),
        "LINE_ELEMENT_COUNT" => "3",
        "MESS_BTN_ADD_TO_BASKET" => "В корзину",
        "MESS_BTN_BUY" => "Купить",
        "MESS_BTN_COMPARE" => "Сравнить",
        "MESS_BTN_DETAIL" => "Подробнее",
        "MESS_NOT_AVAILABLE" => "Нет в наличии",
        "MESS_NOT_AVAILABLE_SERVICE" => "Недоступно",
        "OFFERS_FIELD_CODE" => array(
            0 => "",
            1 => "",
        ),
        "OFFERS_LIMIT" => "5",
        "OFFERS_SORT_FIELD" => "sort",
        "OFFERS_SORT_FIELD2" => "id",
        "OFFERS_SORT_ORDER" => "asc",
        "OFFERS_SORT_ORDER2" => "desc",
        "PARTIAL_PRODUCT_PROPERTIES" => "Y",
        "PRICE_CODE" => array(
            0 => "BASE",
        ),
        "PRICE_VAT_INCLUDE" => "Y",
        "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
        "PRODUCT_DISPLAY_MODE" => "Y",
        "PRODUCT_ID_VARIABLE" => "id",
        "PRODUCT_PROPS_VARIABLE" => "prop",
        "PRODUCT_QUANTITY_VARIABLE" => "quantity",
        "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'4','BIG_DATA':false}]",
        "PRODUCT_SUBSCRIPTION" => "Y",
        "PROPERTY_CODE_MOBILE" => array(
            0 => "ARTNUMBER",
            1 => "MANUFACTURER",
            2 => "MATERIAL",
        ),
        "ROTATE_TIMER" => "30",
        "SECTION_URL" => "",
        "SEF_MODE" => "Y",
        "SHOW_CLOSE_POPUP" => "Y",
        "SHOW_DISCOUNT_PERCENT" => "N",
        "SHOW_MAX_QUANTITY" => "N",
        "SHOW_OLD_PRICE" => "N",
        "SHOW_PAGINATION" => "Y",
        "SHOW_PRICE_COUNT" => "1",
        "SHOW_SLIDER" => "Y",
        "SLIDER_INTERVAL" => "3000",
        "SLIDER_PROGRESS" => "N",
        "TEMPLATE_THEME" => "blue",
        "USE_ENHANCED_ECOMMERCE" => "N",
        "USE_PRICE_COUNT" => "N",
        "USE_PRODUCT_QUANTITY" => "Y",
        "VIEW_MODE" => "SECTION",
        "COMPONENT_TEMPLATE" => ".default",
        "SEF_RULE" => "",
        "LABEL_PROP_MOBILE" => "",
        "LABEL_PROP_POSITION" => "top-left",
        "OFFER_ADD_PICT_PROP" => "-",
        "REPLACE" => $_REQUEST['currenturl'],
    ),
    false
);?>


<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>