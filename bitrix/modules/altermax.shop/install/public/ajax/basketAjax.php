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

if($action == 'DelOrder') {

    global $USER;
    $orderId  = $request->get('orderId');
        $order = Sale\Order::load($orderId);

        if($USER->GetID() == $order->getUserId()) {
            //отменяем оплаты если есть
            $paymentCollection = $order->getPaymentCollection();
            if ($paymentCollection->isPaid()) {
                foreach ($paymentCollection as $payment) {
                    $payment->setReturn("Y");
                }
            }

            //отменяем отгрузки если есть
            $shipmentCollection = $order->getShipmentCollection();
            if ($shipmentCollection->isShipped()) {
                $shipment = $shipmentCollection->getItemById(
                    $shipmentCollection[0]->getField("ID")
                );
                $res = $shipment->setField("DEDUCTED", "N");
            }

            $order->save();

            $res_delete = Sale\Order::delete($orderId);

            echo $res_delete;
            if($res_delete->isSuccess()) {
                $addResult = array('STATUS' => 'OK', 'ORDER_ID' => $orderId);
                echo json_encode($addResult);
            } else {
                $addResult = array('STATUS' => 'ERROR', 'MESSAGE' =>  $res_delete->getErrors());
                echo json_encode($addResult);
            }

        }

}


}

?>


