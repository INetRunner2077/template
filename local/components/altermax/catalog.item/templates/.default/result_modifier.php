<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

$component = $this->getComponent();

$db_props = CIBlockElement::GetProperty($arResult['ITEM']['IBLOCK_ID'], $arResult['ITEM']['ID'], array("sort" => "asc"), Array());
while($ar_props = $db_props->Fetch()) {
    if($ar_props['CODE'] == "VENDOR" or $ar_props['CODE'] == "MANUFACTURER") {

        $arResult['ITEM']['PROP'][$ar_props['CODE']]['VALUE'] =  $ar_props['VALUE'];

    } else {
        continue;
    }

}
