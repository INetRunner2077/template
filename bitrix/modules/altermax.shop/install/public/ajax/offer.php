<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>
<?php

use \Bitrix\Catalog\Model\Price;
use \Bitrix\Main\Loader;

Loader::IncludeModule('search');
Loader::IncludeModule('sale');

$arFilter = array (
    'SITE_ID' => 's1',
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
    'STEMMING' => false,
);

$obSearch = new CSearch();

$obSearch->Search($arFilter, $aSort, $exFILTER);

while($result = $obSearch->Fetch()) {

if($result['ITEM_ID'] == $_REQUEST['itemId']) { continue; }
$arrReturn[$result['ITEM_ID']] = $result;
$ids[] = $result['ITEM_ID'];

}

if(empty($arrReturn)) {
        die();
}

Loader::includeModule('catalog');


$arrCountProduct = \Bitrix\Catalog\ProductTable::getList(
    [
        'select' => [
            'ID',
            'QUANTITY',
        ],
        'filter' => ['ID' => $ids],
    ]
);

while ($CountProduct = $arrCountProduct->fetch()) {

        if(empty($CountProduct['QUANTITY'])) {
                unset($arrReturn[$CountProduct['ID']]);
                unset($arrReturn[$CountProduct['ID']]);
        } else {
                $arrReturn[$CountProduct['ID']]['QUANTITY'] = $CountProduct['QUANTITY'];
        }

}


if(empty($arrReturn)) {
        die();
}

$arrPrice = Price::getList(
    array(
        'filter' => array('PRODUCT_ID' => $ids),
        'select' => array('PRICE', 'PRODUCT_ID'),
    )
);

while ($price = $arrPrice->fetch()) {
        $arrReturn[$price['PRODUCT_ID']]['PRICE'] = $price['PRICE'];
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


echo json_encode($arrReturn);
die();



?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>