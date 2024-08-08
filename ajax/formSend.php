<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
};
$template = htmlspecialchars($_REQUEST['template']);
if($template == "CALL_ME") {

    $email = htmlspecialcharsEx($_REQUEST['email']);
    $name = htmlspecialcharsEx($_REQUEST['name']);
    $phone = htmlspecialcharsEx($_REQUEST['phone']);
    $comments = htmlspecialcharsEx($_REQUEST['comments']);

    $arFeedForm = array(
        "EMAIL" => $email,
        "PHONE" => $phone,
        "NAME" => $name,
        "COMMENT" => $comments,
    );

    if($send = CEvent::Send($template, SITE_ID, $arFeedForm,"N")) {
        echo json_encode(array('STATUS' => 'OK'));
    } else {
        echo json_encode(array('STATUS' => 'NO'));
    }

}
if($template == "REQUEST_PRODUCT") {

    $email = htmlspecialcharsEx($_REQUEST['email']);
    $name = htmlspecialcharsEx($_REQUEST['your-name']);
    $phone = htmlspecialcharsEx($_REQUEST['phone']);
    $priceProduct = htmlspecialcharsEx($_REQUEST['price']);
    $nameProduct = htmlspecialcharsEx($_REQUEST['nameProduct']);
    $Quantity = htmlspecialcharsEx($_REQUEST['quantity']);

    $arFeedForm = array(
        "NAME_PRODUCT" => $nameProduct,
        "PHONE" => $phone,
        "NAME" => $name,
        "PRICE_PRODUCT" => $priceProduct,
        "QUANTITY" => $Quantity,
    );

    if($send = CEvent::Send($template, SITE_ID, $arFeedForm,"N")) {
        echo json_encode(array('STATUS' => 'OK'));
    } else {
        echo json_encode(array('STATUS' => 'NO'));
    }

}