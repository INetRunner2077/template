<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контактная информация");
?>

    <div class="container">
        <div class="row">
            <div class="faq-page text-post">
                <div class="col-xs-12">
                    <div class="page-title">
                        <h2>Контакты</h2>
                    </div>
                    <p><strong><?=\Bitrix\Main\Config\Option::get("main", "site_name", "ООО Энергофлот")?></strong><br><strong>тел.&nbsp;
                            &nbsp; &nbsp;<?= \Bitrix\Main\Config\Option::get(
                                "altermax.shop",
                                "phone_work",
                                "+7(861) 205-03-77"
                            ) ?>
                        </strong><br>
                        <strong>e-mail&nbsp;
                            <a href="mailto:mail@energoflot.ru"><?= \Bitrix\Main\Config\Option::get(
                                    "altermax.shop",
                                    "email_work",
                                    "mail@energoflot.ru"
                                ) ?></a><br>
                        </strong>
                        <strong>Адрес
                            для
                            корреспонденции: <?= \Bitrix\Main\Config\Option::get(
                                "altermax.shop",
                                "adress",
                                "350059, г. Краснодар, ул. Новороссийская, д. 172, помещ. 206"
                            ) ?></strong>
                    </p>
                </div>
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
            ); ?>
            <!-- service section -->
        </div>
    </div>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>