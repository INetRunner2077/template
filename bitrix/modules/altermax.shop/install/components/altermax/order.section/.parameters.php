<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = array(
	"PARAMETERS" => array(
        "PATH_TO_PAYMENT" => Array(
            "NAME" => 'Страница подключения платежной системы',
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "/personal/order/payment/",
            "PARENT" => "ADDITIONAL_SETTINGS",
        ),
	),
);
?>