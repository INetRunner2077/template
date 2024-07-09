<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle(""); ?><?$APPLICATION->IncludeComponent(
    "altermax:uf.change",
    "main",
    array(
        "USER_PROPERTY" => array(
            0 => "UF_POST_ALTERMAX",
            1 => "UF_LEGAL_ADRESS_ALTERMAX",
            2 => "UF_INN_ALTERMAX",
            3 => "UF_NAME_ORGANIZATION_ALTERMAX",
            4 => "UF_OGRNIP_ALTERMAX",
            5 => "UF_OWNERSHIP_ALTERMAX",
            6 => "UF_TYPE_OF_BUYER_ALTERMAX",
        ),
        "COMPONENT_TEMPLATE" => "main"
    ),
    false
);?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>