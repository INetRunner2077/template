<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Позвонить мне");
?>

<div class="container">
    <div class="row">
        <div class="text-post">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-sm-6">
                        <?$APPLICATION->IncludeComponent(
                            "altermax:call.me",
                            "main",
                            Array(
                                "EMAIL_REQ" => "N",
                                "SUCCESS_PAGE" => ""
                            )
                        );?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
