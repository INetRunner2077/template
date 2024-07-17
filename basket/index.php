<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина"); ?>

<?$APPLICATION->IncludeComponent(
	"altermax:catalog.basket",
	"main",
Array(),
false
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>



<script>



    $.ajax({
        type: "POST",
        url: "/ajax/offer.php",
        data: finder,
        dataType: "json",
        success: function (data) {
            console.log('sussess')
    });




</script>