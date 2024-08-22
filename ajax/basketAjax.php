<?

require($_SERVER["DOCUMENT_ROOT"]
    ."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Sale;

$request = Context::getCurrent()->getRequest();
$action = $request->get("action");
if (empty($action)) {
    die();
}

if (Loader::IncludeModule("sale")) {
    $itemId = $request->get("ItemId");
    $fUser = Sale\Fuser::getId();
    $siteId = Bitrix\Main\Context::getCurrent()->getSite();

    if ($action == 'D') {
        global $APPLICATION;
        $dbBasketItems = CSaleBasket::GetList(
            array(),
            array(
                'FUSER_ID'   => $fUser,
                'LID'        => $siteId,
                'ORDER_ID'   => 'NULL',
                'PRODUCT_ID' => $itemId,
            )
        );
        if ($arBasket = $dbBasketItems->Fetch()) {
            CSaleBasket::Delete($arBasket['ID']);
        }
    }
    if ($action == 'Q') {
        $count = $request->get("count");
        $res = array();
        $product = array(
            'PRODUCT_ID' => $itemId,
            'QUANTITY'   => $count,
        );
        $arFields = array(
            "QUANTITY" => $count,
        );

        $dbBasketItems = CSaleBasket::GetList(
            array(),
            array(
                'FUSER_ID'   => $fUser,
                'LID'        => $siteId,
                'ORDER_ID'   => 'NULL',
                'PRODUCT_ID' => $itemId,
            )
        );
        if ($arBasket = $dbBasketItems->Fetch()) {
            $updateResult = CSaleBasket::Update($arBasket['ID'], $arFields);
            if (!$updateResult) {
                $addResult = array('STATUS' => 'ERROR');
                echo json_encode($addResult);
                die();
            } else {
                $addResult = array('STATUS' => 'OK');
                echo json_encode($addResult);
            }
        }
    }

    if ($action == 'B') {
        $basket = Sale\Basket::loadItemsForFUser($fUser, $siteId);
        $basketItems = $basket->getBasketItems();

        $order = Sale\Order::create($siteId, $fUser);
        $order->setPersonTypeId(1);
        $order->setBasket($basket);

        $addResult = array(
            'STATUS'      => 'BASKET',
            'FINAL_PRICE' => number_format($basket->getPrice(), 2, '.', ''),
            'TAX_PRICE' => number_format($order->getTaxValue(), 2, '.', ''),
        );
        echo json_encode($addResult);
    }

    if ($action == 'DelOrder') {
        global $USER;
        $orderId = $request->get('orderId');
        $order = Sale\Order::load($orderId);

        if ($USER->GetID() == $order->getUserId()) {
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

            if ($res_delete->isSuccess()) {
                $addResult = array('STATUS' => 'OK', 'ORDER_ID' => $orderId);
                echo json_encode($addResult);
            } else {
                $addResult = array(
                    'STATUS'  => 'ERROR',
                    'MESSAGE' => $res_delete->getErrors(),
                );
                echo json_encode($addResult);
            }
        }
    }

    if ($action == 'refresh') {
        $basket = Sale\Basket::loadItemsForFUser($fUser, $siteId);
        ob_start();
        ?>
<div class="mini-cart slim-cart">
    <div data-toggle="dropdown" data-hover="dropdown"
         class="basket dropdown-toggle">
        <a href="/checkout">
            <div class="cart-icon">
                <i class="icon-basket-loaded icons"></i>
                <span class="cart-total"><?=$basket->count()?></span></div>
            <div class="shoppingcart-inner hidden-xs"><span
                    class="cart-title text-uppercase">Корзина</span></div>
        </a>
    </div>
    <div>
        <div class="top-cart-content">
            <div class="block-subtitle hidden">Недавно добавленные товары</div>
            <ul id="cart-sidebar" class="mini-products-list">

       <?php foreach ($basket as $item): ?>
       <? $item = $item->toArray(); ?>
       <?  if($item['CURRENCY'] == 'RUB') { $item['CURRENCY'] = 'р.'; } ?>
            <li class="item odd">
                <a href="#" title="<?=$item['NAME']?>" class="product-image">
                    <i class="fa fa-info"></i>
                </a>
                <div class="product-details">
                    <p class="product-name"><a
                            href="<?=$item['DETAIL_PAGE_URL']?>"
                            target="_blank"><?=$item['NAME']?></a></p>
                    <strong><?=(int)$item['QUANTITY']?></strong> x <span class="price"><?=number_format($item['BASE_PRICE'], 2, '.', '')?></span> =
                    <span class="price"><?=number_format($item['BASE_PRICE']* (int)$item['QUANTITY'],2, '.', '')  .' '?><span
                            class="lwr-case"><?=$item['CURRENCY']?></span></span>
                </div>
            </li>
        <?php
        endforeach;?>
            </ul>
            <div class="top-subtotal"><span class="text-uppercase">Итого</span>,
                руб: <span class="price"><?=number_format($basket->getPrice(), 2, '.', '')?></span></div>
            <div class="actions">
                <button class="btn-checkout" type="button" onClick="location.href='/ord/'"><i
                        class="fa fa-check"></i> <span>Оформить</span>
                </button>
                <button class="view-cart" type="button" onClick="location.href='/basket/'"><i
                        class="fa fa-shopping-cart"></i>
                    <span>Изменить</span></button>
            </div>
        </div>
    </div>
</div>
        <? $basket = ob_get_clean();

        echo $basket;
        die();
    }
}

?>


