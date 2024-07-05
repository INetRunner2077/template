<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle(""); ?><?$APPLICATION->IncludeComponent(
    "altermax:uf.change",
    "main",
    array(
        "USER_PROPERTY" => array(
            0 => "UF_INN",
            1 => "UF_KPP",
            2 => "UF_NAME_ORGANIZATION",
            3 => "UF_LEGAL_ADRESS",
            4 => "UF_MAILING_ADDRESS",
            5 => "UF_CHECKING_ACCOUNT",
            6 => "UF_BIK",
            7 => "UF_BANK",
            8 => "UF_SURVEY",
            9 => "UF_TYPE_OF_BUYER",
            10 => "UF_POST",
            11 => "UF_OGRNIP",
            12 => "UF_OWNERSHIP",
        ),
        "COMPONENT_TEMPLATE" => "main"
    ),
    false
);?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>