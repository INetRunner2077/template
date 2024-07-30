<?

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Sale;
use Bitrix\Sale\Cashbox\CheckManager;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
Loader::includeModule("sale");
class OrderSection extends CBitrixComponent
{

    public function GetProductsOrder($orderId) {

        $arrProducts = array();
        $dbBasketItems = CSaleBasket::GetList(
            array(
                "ID" => "ASC"
            ),
            array(
                "LID" => SITE_ID,
                "ORDER_ID" => $orderId,
                "CAN_BUY" => "Y"
            ),
            false,
            false,
            array()
        );
        while ($arItems = $dbBasketItems->Fetch())
        {
            $this->arResult['ORDERS'][$arItems['ORDER_ID']]['PRODUCTS'][$arItems['PRODUCT_ID']] = $arItems;
        }
    }

    public function GetOrder($orderId = array()) {

        global $USER;
        $this->OrderIds = array();

        if(empty($orderId)) {

            if($this->allOrder == 'all') {
                $orderArray =  [
                    'filter' => [
                        "USER_ID" => $USER->GetID(),
                    ],
                    'order'  => ['ID' => 'DESC'],
                ];
            } else {
                $myDate = new \Bitrix\Main\Type\Date();
                $myDate->add('-1M');
                $orderArray =  [
                    'filter' => [
                        "USER_ID" => $USER->GetID(),
                        ">=DATE_INSERT" => $myDate,
                    ],
                    'order' => ["DATE_INSERT" => "ASC"]
                ];
            }
        } else {
            $orderArray =  [
                'filter' => [
                    "USER_ID" => $USER->GetID(),
                    "ID" => $orderId,
                ],
                'order'  => ['ID' => 'DESC'],
            ];
            $this->arResult['show'] = 'Y';
            $this->arResult['ID'] = $orderId;
        }

        $dbRes = \Bitrix\Sale\Order::getList($orderArray);
        while ($order = $dbRes->fetch()) {
            $this->arResult['ORDERS'][$order['ID']] = $order;
            $this->arResult['ORDERS'][$order['ID']]['STATUS']
                = CSaleStatus::GetByID($order['STATUS_ID']);
            $this->OrderIds[] = $order['ID'];
        }
    }

    public function GetShipment($ordId) {

        $this->registry = Sale\Registry::getInstance(
            Sale\Registry::REGISTRY_TYPE_ORDER
        );
        $trackingManager = Sale\Delivery\Tracking\Manager::getInstance();

        $deliveryStatusClassName = $this->registry->getDeliveryStatusClassName(
        );
        $deliveryStatuses = $deliveryStatusClassName::getAllStatusesNames(
            LANGUAGE_ID
        );

        $shipmentClassName = $this->registry->getShipmentClassName();
        /** @var Main\DB\Result $listShipments */
        $listShipments = $shipmentClassName::getList(
            array(
                'select' => array(
                    'STATUS_ID',
                    'DELIVERY_NAME',
                    'SYSTEM',
                    'DELIVERY_ID',
                    'ACCOUNT_NUMBER',
                    'PRICE_DELIVERY',
                    'DATE_DEDUCTED',
                    'CURRENCY',
                    'DEDUCTED',
                    'TRACKING_NUMBER',
                    'ORDER_ID',
                ),
                'filter' => array('ORDER_ID' => $ordId),
            )
        );

        while ($shipment = $listShipments->fetch()) {
            if ($shipment['SYSTEM'] == 'Y') {
                continue;
            }

            $shipment['DELIVERY_NAME'] = htmlspecialcharsbx(
                $shipment['DELIVERY_NAME']
            );
            $shipment["FORMATED_DELIVERY_PRICE"] = SaleFormatCurrency(
                floatval($shipment["PRICE_DELIVERY"]),
                $shipment["CURRENCY"]
            );
            $shipment["DELIVERY_STATUS_NAME"]
                = $deliveryStatuses[$shipment["STATUS_ID"]];
            $shipment['TRACKING_URL'] = '';
            if ($shipment["DELIVERY_ID"] > 0
                && (string)$shipment["TRACKING_NUMBER"] !== ''
            ) {
                $shipment["TRACKING_URL"] = $trackingManager->getTrackingUrl(
                    $shipment["DELIVERY_ID"],
                    $shipment["TRACKING_NUMBER"]
                );
            }
            $listOrderShipment[$shipment['ORDER_ID']] = $shipment;
        }
        $this->arResult['SHIPMENT'] = $listOrderShipment;


        $paymentClassName = $this->registry->getPaymentClassName();
        /** @var Main\DB\Result $listPayments */
        $listPayments = $paymentClassName::getList(
            array(
                'select' => array(
                    'ID',
                    'PAY_SYSTEM_NAME',
                    'PAY_SYSTEM_ID',
                    'ACCOUNT_NUMBER',
                    'ORDER_ID',
                    'PAID',
                    'SUM',
                    'CURRENCY',
                    'DATE_BILL',
                ),
                'filter' => array('ORDER_ID' => $ordId),
            )
        );

        $paymentIdList = array();
        $paymentList = array();

        while ($payment = $listPayments->fetch()) {
            $paySystemFields
                = $this->dbResult['PAYSYS'][$payment['PAY_SYSTEM_ID']];
            $payment['PAY_SYSTEM_NAME'] = htmlspecialcharsbx(
                $payment['PAY_SYSTEM_NAME']
            );
            $payment["FORMATED_SUM"] = SaleFormatCurrency(
                $payment["SUM"],
                $payment["CURRENCY"]
            );
            $payment['IS_CASH'] = $paySystemFields['IS_CASH'];
            $payment['NEW_WINDOW'] = $paySystemFields['NEW_WINDOW'];
            $payment['ACTION_FILE'] = $paySystemFields['ACTION_FILE'];
            $payment["PSA_ACTION_FILE"] = htmlspecialcharsbx(
                    $this->arParams["PATH_TO_PAYMENT"]
                ).'?ORDER_ID='.$payment["ORDER_ID"].'&PAYMENT_ID='.$payment['ACCOUNT_NUMBER'];
            $paymentList[$payment['ID']] = $payment;
            $paymentIdList[] = $payment['ID'];
        }
        $this->arResult["PAYMENT"] = $paymentList;
        $this->arResult["RETURN_URL"] = (new Sale\PaySystem\Context())->getUrl();


    }

    public function executeComponent()
    {
        global $USER;
        if(empty($this->arParams['PATH_TO_PAYMENT'])) { $this->arParams['PATH_TO_PAYMENT'] = "/personal/order/payment/"; }
        if($USER->IsAuthorized()) {
        $request = Context::getCurrent()->getRequest();
        $this->orderId  = $request->get("orderId");
        $this->allOrder  = $request->get("all");

        if(!empty($this->orderId)) {
            self::getOrder($this->orderId);
            self::GetProductsOrder($this->OrderIds);
            self::GetShipment($this->OrderIds);
        }
        else {
            self::getOrder();
            self::GetProductsOrder($this->OrderIds);
        }

            $this->includeComponentTemplate();
        }
    }

}



