<?

use Bitrix\Main\Loader,
    Bitrix\Main\Application,
    Bitrix\Sale,
    Bitrix\Main\Context;

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

    public function executeComponent()
    {
        global $USER;
        if($USER->IsAuthorized()) {
        $request = Context::getCurrent()->getRequest();
        $this->orderId  = $request->get("orderId");
        $this->allOrder  = $request->get("all");

        if(!empty($this->orderId)) {
            self::getOrder($this->orderId);
            self::GetProductsOrder($this->OrderIds);
        }
        else {
            self::getOrder();
            self::GetProductsOrder($this->OrderIds);
        }

            $this->includeComponentTemplate();
        }
    }

}



