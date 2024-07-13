<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>
<?php


\Bitrix\Main\Loader::IncludeModule('search');

$arFilter = array (
    'SITE_ID' => 's1',
    'QUERY' => $_REQUEST['name'],
    'CHECK_DATES' => 'Y',
    'MODULE_ID' => 'iblock',
);

$aSort = array (
    'CUSTOM_RANK' => 'DESC',
    'RANK' => 'DESC',
    'DATE_CHANGE' => 'DESC',
);

$exFILTER = array (
    0 =>
        array (
            '=MODULE_ID' => 'iblock',
        ),
    'STEMMING' => false,
    'USE_TF_FILTER' => true,
);

$obSearch = new CSearch();

$obSearch->Search($arFilter, $aSort, $exFILTER);

while($result = $obSearch->Fetch()) {

$arrReturn[] = $result;

}
echo json_encode($arrReturn);
die();



?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>