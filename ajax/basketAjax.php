<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Application,
    Bitrix\Sale,
    Bitrix\Main\Context,
    Bitrix\Main\Loader;

$request = Context::getCurrent()->getRequest();
$action  = $request->get("action");
if(empty($action)) { die(); }

if(Loader::IncludeModule("sale")) {

    $itemId  = $request->get("ItemId");
    $fUser = Sale\Fuser::getId();
    $siteId = Bitrix\Main\Context::getCurrent()->getSite();

    if($action == 'D') {

        global $APPLICATION;
        $dbBasketItems = CSaleBasket::GetList(array(),array(
            'FUSER_ID' => $fUser,
            'LID' => $siteId,
            'ORDER_ID' => 'NULL',
            'PRODUCT_ID' => $itemId));
        if ($arBasket = $dbBasketItems->Fetch()) {
            CSaleBasket::Delete($arBasket['ID']);
        }
    }
    if($action == 'Q') {
        $count = $request->get("count");
        $res = array();
        $product = array(
            'PRODUCT_ID' => $itemId,
            'QUANTITY' => $count,
        );
        $arFields = array(
            "QUANTITY" => $count
        );

        $dbBasketItems = CSaleBasket::GetList(array(),array(
            'FUSER_ID' => $fUser,
            'LID' => $siteId,
            'ORDER_ID' => 'NULL',
            'PRODUCT_ID' => $itemId
        ));
        if ($arBasket = $dbBasketItems->Fetch()) {
            $updateResult = CSaleBasket::Update($arBasket['ID'], $arFields);
            if(!$updateResult){
                $addResult = array('STATUS' => 'ERROR');
                echo json_encode($addResult);
                die();
            } else {
                $addResult = array('STATUS' => 'OK');
                echo json_encode($addResult);
            }
        }

    }

if($action == 'B') {

    $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
    $basketItems = $basket->getBasketItems();
    $addResult = array('STATUS' => 'BASKET', 'FINAL_PRICE' => $basket->getPrice());
    echo json_encode($addResult);

}


}

?>


