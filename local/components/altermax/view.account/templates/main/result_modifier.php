<?php
$obEnum = new \CUserFieldEnum;
$rsEnum = $obEnum->GetList(array(), array("USER_FIELD_NAME" => 'UF_TYPE_OF_BUYER_ALTERMAX', 'ID' => $arResult['arUser']['UF_TYPE_OF_BUYER_ALTERMAX']));
$enum = array();
if($arEnum = $rsEnum->Fetch())
{
    $arResult['arUser']['UF_TYPE_OF_BUYER_ALTERMAX'] = $arEnum['XML_ID'];
}
$rsEnum = $obEnum->GetList(array(), array("USER_FIELD_NAME" => 'UF_OWNERSHIP_ALTERMAX', 'ID' => $arResult['arUser']['UF_OWNERSHIP_ALTERMAX']));
$enum = array();
if($arEnum = $rsEnum->Fetch())
{
    $arResult['arUser']['UF_OWNERSHIP_ALTERMAX'] = $arEnum['VALUE'];
}