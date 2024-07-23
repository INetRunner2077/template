<?php
/**
 * MAKING $arResult FROM SCRATCHES
 *
 * @var OpenSourceOrderComponent $component
 */

use Bitrix\Sale\BasketItem;
use Bitrix\Sale\BasketPropertyItem;
use Bitrix\Sale\Delivery;
use Bitrix\Sale\Order;
use Bitrix\Sale\PropertyValue;
use OpenSource\Order\LocationHelper;
use OpenSource\Order\OrderHelper;

$component = &$this->__component;
$order = $component->order;

if (!$order instanceof Order) {
    return;
}

/**
 * ORDER FIELDS
 */
$props = $arResult['PROP'];
/*if($order->getPropertyCollection()->getDeliveryLocationZip() != null) {
    $props['ZIP'] = $order->getPropertyCollection()->getDeliveryLocationZip()
        ->getValue();
}*/
$arResult = $order->getFieldValues();
$arResult['PROP'] = $props;


$arResult['PERSON_TYPES'] = $component->getPersonTypes();
foreach ($arResult['PERSON_TYPES'] as $key => &$arPersonType) {
    if ($arParams['PERSON_TYPE_ID'] === $arPersonType['ID']) {
        $arPersonType['CHECKED'] = true;
    } else {
        $arPersonType['CHECKED'] = false;
    }
}
unset($arPersonType);


/**
 * ORDER PROPERTIES
 */
$arResult['PROPERTIES'] = [];
foreach ($component->getPersonTypes() as $arPersonType) {
    $arResult['PROPERTIES'][$arPersonType['ID']] = [];

    if ($arParams['PERSON_TYPE_ID'] === $arPersonType['ID']) {
        $propertyCollection = $order->getPropertyCollection();
    } else {
        $order->setPersonTypeId($arPersonType['ID']);
        $propertyCollection = $order->loadPropertyCollection();
        $order->setPersonTypeId($arParams['PERSON_TYPE_ID']);
    }

    foreach ($propertyCollection as $prop) {
        /**
         * @var PropertyValue $prop
         */
        if ($prop->isUtil()) {
            continue;
        }

        $arProp['FORM_NAME'] = 'PROPERTIES[' . $prop->getField('CODE') . ']';
        $arProp['FORM_LABEL'] = 'property_' . $prop->getField('CODE');


        $value = $props[$prop->getField('CODE')] ?? null;

        if (empty($value)) {
            $value = $prop->getProperty()['DEFAULT_VALUE'];
        }

        if (!empty($value)) {
            $prop->setValue($value);
        }

        $arProp['isRequired'] = $prop->isRequired();
        $arProp['TYPE'] = $prop->getType();
        $arProp['NAME'] = $prop->getName();
        $arProp['VALUE'] = $prop->getValue();
        $arProp['ERRORS'] = $component->errorCollection->getAllErrorsByCode('PROPERTIES[' . $prop->getField('CODE') . ']');

        switch ($prop->getType()) {
            case 'LOCATION':
                if (!empty($arProp['VALUE'])) {

                    if(empty($arParams['location'])) {
                        $arProp['LOCATION_DATA'] = LocationHelper::getDisplayByCode($arProp['VALUE']);
                        $prop->setValue($arProp['VALUE']);
                    } else {
                        $arProp['LOCATION_DATA'] = LocationHelper::getDisplayByCode($arParams['location']);
                        $prop->setValue($arParams['location']);
                        $arProp['VALUE'] = $arParams['location'];
                    }

                }
                break;

            case 'ENUM':
                $arProp['OPTIONS'] = $prop->getPropertyObject()
                    ->getOptions();
                break;
        }

        $arResult['PROPERTIES'][$arPersonType['ID']][$prop->getField('CODE')] = $arProp;
    }
}


/**
 * DELIVERY
 */
$arResult['DELIVERY_ERRORS'] = [];
foreach ($component->errorCollection->getAllErrorsByCode('delivery') as $error) {
    $arResult['DELIVERY_ERRORS'][] = $error;
}

$arResult['DELIVERY'] = [];
$shipment = OrderHelper::getFirstNonSystemShipment($order);
if ($shipment !== null) {
    $availableDeliveries = Delivery\Services\Manager::getRestrictedObjectsList($shipment);
    $allDeliveryIDs = $order->getDeliveryIdList();
    $checkedDeliveryId = end($allDeliveryIDs);

    foreach (OrderHelper::calcDeliveries($shipment, $availableDeliveries, $arParams) as $deliveryID => $calculationResult) {
        /**
         * @var Delivery\Services\Base $obDelivery
         */
        $obDelivery = $availableDeliveries[$deliveryID];

        $arDelivery = [];
        $arDelivery['DESCRIPTION'] = $obDelivery->getDescription();
        $arDelivery['EXTRA_SERVICE'] = $obDelivery->getExtraServices()->getItems();
        $arDelivery['ID'] = $obDelivery->getId();
        $arDelivery['IMG'] = CFile::GetPath($obDelivery->getLogotip());
        $arDelivery['NAME'] = $obDelivery->getName();
        $arDelivery['CHECKED'] = $checkedDeliveryId === $obDelivery->getId();
        $arDelivery['PRICE'] = $calculationResult->isSuccess() ? $calculationResult->getPrice() : 'error';
        $arDelivery['PRICE_DISPLAY'] = SaleFormatCurrency(
            $calculationResult->getDeliveryPrice(),
            $order->getCurrency()
        );

        $arResult['DELIVERY'][$deliveryID] = $arDelivery;
    }
    
    foreach (GetModuleEvents("sale", 'OnSaleComponentOrderOneStepDelivery', true) as $arEvent)
        ExecuteModuleEventEx($arEvent, [&$arResult, &$arModufUser, &$param, true]);
}


/**
 * PAY SYSTEM
 */
$arResult['PAY_SYSTEM_ERRORS'] = [];
foreach ($component->errorCollection->getAllErrorsByCode('payment') as $error) {
    $arResult['PAY_SYSTEM_ERRORS'][] = $error;
}

$arResult['PAY_SYSTEM_LIST'] = [];
$availablePaySystem = OrderHelper::getAvailablePaySystems($order);
$checkedPaySystemId = 0;
if (!$order->getPaymentCollection()->isEmpty()) {
    $payment = $order->getPaymentCollection()->current();
    $checkedPaySystemId = $payment->getPaymentSystemId();
}
foreach ($availablePaySystem as $paySystem) {
    $arPaySystem = [];

    $arPaySystem['ID'] = $paySystem->getField('ID');
    $arPaySystem['NAME'] = $paySystem->getField('NAME');
    $arPaySystem['CHECKED'] = $arPaySystem['ID'] === $checkedPaySystemId;

    $arResult['PAY_SYSTEM_LIST'][$arPaySystem['ID']] = $arPaySystem;
}

/**
 * BASKET
 */
$arResult['BASKET'] = [];
foreach ($order->getBasket() as $basketItem) {
    /**
     * @var BasketItem $basketItem
     */
    $arBasketItem = [];
    $arBasketItem['ID'] = $basketItem->getId();
    $arBasketItem['NAME'] = $basketItem->getField('NAME');
    $arBasketItem['CURRENCY'] = $basketItem->getCurrency();

    $arBasketItem['PROPERTIES'] = [];
    foreach ($basketItem->getPropertyCollection() as $basketPropertyItem):
        /**
         * @var BasketPropertyItem $basketPropertyItem
         */
        $propCode = $basketPropertyItem->getField('CODE');
        if ($propCode !== 'CATALOG.XML_ID' && $propCode !== 'PRODUCT.XML_ID') {
            $arBasketItem['PROPERTIES'][] = [
                'NAME' => $basketPropertyItem->getField('NAME'),
                'VALUE' => $basketPropertyItem->getField('VALUE'),
            ];
        }
    endforeach;

    $arBasketItem['QUANTITY'] = $basketItem->getQuantity();
    $arBasketItem['QUANTITY_DISPLAY'] = $basketItem->getQuantity();
    $arBasketItem['QUANTITY_DISPLAY'] .= ' ' . $basketItem->getField('MEASURE_NAME');

    $arBasketItem['BASE_PRICE'] = $basketItem->getBasePrice();
    $arBasketItem['BASE_PRICE_DISPLAY'] = $arBasketItem['BASE_PRICE'];

    $arBasketItem['PRICE'] = $basketItem->getPrice();
    $arBasketItem['PRICE_DISPLAY'] = $arBasketItem['PRICE'];

    $arBasketItem['SUM'] = $basketItem->getPrice() * $basketItem->getQuantity();
    $arBasketItem['SUM_DISPLAY'] = $arBasketItem['SUM'];
    $arResult['BASKET'][$arBasketItem['ID']] = $arBasketItem;
}

/**
 * ORDER TOTAL BASKET PRICES
 */
//Стоимость товаров без скидок
$arResult['PRODUCTS_BASE_PRICE'] = $order->getBasket()->getBasePrice();
$arResult['PRODUCTS_BASE_PRICE_DISPLAY'] =  $arResult['PRODUCTS_BASE_PRICE'];

//Стоимость товаров со скидами
$arResult['PRODUCTS_PRICE'] = $order->getBasket()->getPrice();
$arResult['PRODUCTS_PRICE_DISPLAY'] = $arResult['PRODUCTS_PRICE'];

//Скидка на товары
$arResult['PRODUCTS_DISCOUNT'] = $arResult['PRODUCTS_BASE_PRICE'] - $arResult['PRODUCTS_PRICE'];
$arResult['PRODUCTS_DISCOUNT_DISPLAY'] = $arResult['PRODUCTS_DISCOUNT'];

/**
 * ORDER TOTAL DELIVERY PRICES
 */
$arShowPrices = $order->getDiscount()
    ->getShowPrices();

//Стоимость доставки без скидок
$arResult['DELIVERY_BASE_PRICE'] = $arShowPrices['DELIVERY']['BASE_PRICE'] ?? 0;
$arResult['DELIVERY_BASE_PRICE_DISPLAY'] = $arResult['DELIVERY_BASE_PRICE'];

//Стоимость доставки с учетом скидок

if($order->getDeliveryPrice() == 0) {
    if($arResult['DELIVERY'][$shipment->getDeliveryId()]['PRICE'] == 'error') {
        $price = 'error';
    }
    else {
        $price = 0;
    }
} else {
    $price = $order->getDeliveryPrice();
}

$arResult['DELIVERY_PRICE'] = $price;
$arResult['DELIVERY_PRICE_DISPLAY'] = $arResult['DELIVERY_PRICE'];

//Скидка на доставку
$arResult['DELIVERY_DISCOUNT'] = $arShowPrices['DELIVERY']['DISCOUNT'] ?? 0;
$arResult['DELIVERY_DISCOUNT_DISPLAY'] = $arResult['DELIVERY_PRICE'];

/**
 * ORDER TOTAL PRICES
 */
//Общая цена без скидок
$arResult['SUM_BASE'] = $arResult['PRODUCTS_BASE_PRICE'] + $arResult['DELIVERY_BASE_PRICE'];
$arResult['SUM_BASE_DISPLAY'] =  $arResult['SUM_BASE'];

//Общая скидка
$arResult['DISCOUNT_VALUE'] = $arResult['SUM_BASE'] - $order->getPrice();
$arResult['DISCOUNT_VALUE_DISPLAY'] = $arResult['DISCOUNT_VALUE'];

//К оплате
$arResult['SUM'] = $order->getPrice();
$arResult['SUM_DISPLAY'] = $arResult['SUM'];

$arResult['PROP'] = $props;
