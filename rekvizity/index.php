<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("rekvizity");
?>

    <div class="container">
        <div class="row">
            <div class="faq-page text-post">
                <div class="col-xs-12">
                    <div class="page-title">
                        <h2>Реквизиты</h2>
                    </div>
                    <h2>Банковские реквизиты</h2>
                    <p>
                        <strong>Получатель:</strong> <?= \Bitrix\Main\Config\Option::get(
                            "main",
                            "site_name",
                            "ООО Энергофлот"
                        ) ?>"<br><strong>Юр.
                            адрес:</strong> <?= \Bitrix\Main\Config\Option::get(
                            "main",
                            "adress_cor",
                            "350059, г. Краснодар, ул. Новороссийская, д. 172, помещ. 206"
                        ) ?></p>
                    <p><strong>ИНН:</strong> 2311312020<br><strong>КПП:</strong>
                        231201001<br><strong>Р/ cч.</strong>
                        40702810026210001481 ФИЛИАЛ "РОСТОВСКИЙ" АО "АЛЬФА-БАНК"<br><strong>БИК:</strong>
                        046015207<br><strong>К/ cч.</strong>
                        30101810500000000207<br><strong>Код ОКТМО: </strong>03701000<br><strong>ОГРН:</strong>
                        1202300061413</p></div>
            </div>
            <!-- service section -->
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                array(
                    "AREA_FILE_RECURSIVE" => "Y",
                    "AREA_FILE_SHOW"      => "file",
                    "AREA_FILE_SUFFIX"    => "inc",
                    "EDIT_TEMPLATE"       => "standard.php",
                    "PATH"                => "/bitrix/modules/altermax.shop/include/banner.php",
                )
            ); ?><!-- .jtv-service-area -->
        </div>
    </div>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>