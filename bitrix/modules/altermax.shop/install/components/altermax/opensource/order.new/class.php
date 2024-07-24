<?php

use Bitrix\Main\Context;
use Bitrix\Main\Error;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Result;
use Bitrix\Main\Web\Json;
use Bitrix\Sale;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Delivery;
use Bitrix\Sale\Fuser;
use Bitrix\Sale\Order;
use Bitrix\Sale\Payment;
use Bitrix\Sale\PaySystem;
use Bitrix\Sale\PropertyValue;
use Bitrix\Sale\Shipment;
use Bitrix\Sale\ShipmentCollection;
use Bitrix\Sale\ShipmentItem;
use Bitrix\Sale\ShipmentItemCollection;
use OpenSource\Order\ErrorCollection;
use OpenSource\Order\OrderHelper;
use OpenSource\Order\LocationHelper;

class OpenSourceOrderComponent extends CBitrixComponent
{
    /**
     * @var Order
     */
    public $order;

    /**
     * @var ErrorCollection
     */
    public $errorCollection;

    protected $personTypes = [];

    /**
     * CustomOrder constructor.
     * @param CBitrixComponent|null $component
     * @throws Bitrix\Main\LoaderException
     */
    public function __construct(CBitrixComponent $component = null)
    {
        parent::__construct($component);

        Loader::includeModule('sale');
        Loader::includeModule('catalog');
        Loader::includeModule('opensource.order');

        $this->errorCollection = new ErrorCollection();
    }

    public function onIncludeComponentLang()
    {
        Loc::loadLanguageFile(__FILE__);
    }

    public function onPrepareComponentParams($arParams = []): array
    {
        if (isset($arParams['DEFAULT_PERSON_TYPE_ID']) && (int)$arParams['DEFAULT_PERSON_TYPE_ID'] > 0) {
            $arParams['DEFAULT_PERSON_TYPE_ID'] = (int)$arParams['DEFAULT_PERSON_TYPE_ID'];
        } else {
            $arPersonTypes = $this->getPersonTypes();
            $arPersonType = reset($arPersonTypes);
            if (is_array($arPersonType)) {
                $arParams['DEFAULT_PERSON_TYPE_ID'] = (int)reset($arPersonTypes)['ID'];
            } else {
                $arParams['DEFAULT_PERSON_TYPE_ID'] = 1;
            }
        }

        if (isset($this->request['person_type_id']) && (int)$this->request['person_type_id'] > 0) {
            $arParams['PERSON_TYPE_ID'] = (int)$this->request['person_type_id'];
        } else {
            $arParams['PERSON_TYPE_ID'] = $arParams['DEFAULT_PERSON_TYPE_ID'];
        }

        if (isset($arParams['SAVE'])) {
            $arParams['SAVE'] = $arParams['SAVE'] === 'Y';
        } elseif (isset($this->request['save'])) {
            $arParams['SAVE'] = $this->request['save'] === 'y';
        } else {
            $arParams['SAVE'] = false;
        }

        return $arParams;
    }

    /**
     * @return array
     */
    public function getPersonTypes(): array
    {
        if (empty($this->personTypes)) {
            $personType = new CSalePersonType();
            $rsPersonTypes = $personType->GetList(['SORT' => 'ASC']);
            while ($arPersonType = $rsPersonTypes->Fetch()) {
                $arPersonType['ID'] = (int)$arPersonType['ID'];
                $this->personTypes[$arPersonType['ID']] = $arPersonType;
            }
        }

        return $this->personTypes;
    }

    /**
     * @param int $personTypeId
     * @return Order
     * @throws Exception
     */
    public function createVirtualOrder(int $personTypeId, $id = null)
    {
        global $USER;

      if(is_null($id)) {
          $ids = $USER->GetID();
      } else {
          $ids = $id;
      }

        if (!isset($this->getPersonTypes()[$personTypeId])) {
            throw new RuntimeException(Loc::getMessage('OPEN_SOURCE_ORDER_UNKNOWN_PERSON_TYPE'));
        }

        $siteId = Context::getCurrent()
            ->getSite();

        $basketItems = Basket::loadItemsForFUser(Fuser::getId(), $siteId)
            ->getOrderableItems();

        if (count($basketItems) === 0) {
            $new_url = $this->arParams['PATH_TO_BASKET'];
            LocalRedirect($new_url);
            die();
        }

        $this->order = Order::create($siteId, $ids);
        $this->order->setPersonTypeId($personTypeId);
        $this->order->setBasket($basketItems);

        return $this->order;
    }

    /**
     * @param array $propertyValues
     * @throws Exception
     */
    public function setOrderProperties(array $propertyValues)
    {
        foreach ($this->order->getPropertyCollection() as $prop) {
            /**
             * @var PropertyValue $prop
             */
            if ($prop->isUtil()) {
                continue;
            }

            $value = $propertyValues[$prop->getField('CODE')] ?? null;

            if (empty($value)) {
                $value = $prop->getProperty()['DEFAULT_VALUE'];
            }

            if (!empty($value)) {
                $prop->setValue($value);
            }
        }
    }

    /**
     * @param int $deliveryId
     * @return Shipment
     * @throws Exception
     */
    public function createOrderShipment(int $deliveryId = 0, $service = array())
    {
        /* @var $shipmentCollection ShipmentCollection */
        $shipmentCollection = $this->order->getShipmentCollection();

        $shipment = $shipmentCollection->createItem(
            Bitrix\Sale\Delivery\Services\Manager::getObjectById($deliveryId)
        );

        $availableDeliveries = Delivery\Services\Manager::getRestrictedObjectsList($shipment);

        if(empty($availableDeliveries[$deliveryId])) {

            $shipment->delete();
            $deliveryId = 2;
            unset($shipmentCollection);
            $shipmentCollection = $this->order->getShipmentCollection();
            $shipment = $shipmentCollection->createItem(
                Bitrix\Sale\Delivery\Services\Manager::getObjectById($deliveryId)
            );
            $availableDeliveries = Delivery\Services\Manager::getRestrictedObjectsList($shipment);

        }


        if ($deliveryId > 0) {


            foreach($availableDeliveries as  $objDelivery) {

                foreach($objDelivery->getExtraServices()->getItems() as $extraServiceId => $extraServiceValue) {

                    $objDelivery->getExtraServices()->setValues(array($extraServiceId => 'Y'));
                }

            }
            /* Это нужно только для финального action оформления заказа */
            if(!empty($service)) {
                $this->arParams['SERVICE'] = $service;
            }
            /* Это нужно только для финального action оформления заказа */





            foreach($availableDeliveries[$deliveryId]->getExtraServices()->getItems() as $extraServiceId => $extraServiceValue)
            {
                if( (!empty($this->arParams['SERVICE'])) && (in_array($extraServiceId, $this->arParams['SERVICE']))) {
                    $arExtraServices[$extraServiceId]
                        = 'N';
                } else {
                    $arExtraServices[$extraServiceId]
                        = 'Y';
                }

            }
            if(is_array($arExtraServices)) {
                $availableDeliveries[$deliveryId]->getExtraServices()->setValues($arExtraServices);
                $shipment->setExtraServices($arExtraServices);
            }




        } else {
            $shipment = $shipmentCollection->createItem();
        }


        /** @var $shipmentItemCollection ShipmentItemCollection */
        $shipmentItemCollection = $shipment->getShipmentItemCollection();
        $shipment->setField('CURRENCY', $this->order->getCurrency());

        foreach ($this->order->getBasket()->getOrderableItems() as $basketItem) {
            /**
             * @var $basketItem BasketItem
             * @var $shipmentItem ShipmentItem
             */
            $shipmentItem = $shipmentItemCollection->createItem($basketItem);
            $shipmentItem->setQuantity($basketItem->getQuantity());
        }

        if(($deliveryId > 0) and (!\Bitrix\Sale\Delivery\Services\Manager::getObjectById($deliveryId)->calculate($shipment)->isSuccess())) {
            $shipment->setBasePriceDelivery(0);
        }
        $this->deliveryID = $deliveryId;
        return $shipment;
    }

    /**
     * @param int $paySystemId
     * @return Payment
     * @throws Exception
     */
    public function createOrderPayment(int $paySystemId)
    {
        $paymentCollection = $this->order->getPaymentCollection();
        $payment = $paymentCollection->createItem(
            Bitrix\Sale\PaySystem\Manager::getObjectById($paySystemId)
        );
        $payment->setField('SUM', $this->order->getPrice());
        $payment->setField('CURRENCY', $this->order->getCurrency());

        return $payment;
    }

    /**
     * @return Result
     *
     * @throws Exception
     */
    public function validateProperties()
    {
        $result = new Result();

        foreach ($this->order->getPropertyCollection() as $prop) {
            /**
             * @var PropertyValue $prop
             */
            if ($prop->isUtil()) {
                continue;
            }

            $r = $prop->checkRequiredValue($prop->getField('CODE'), $prop->getValue());
            if ($r->isSuccess()) {
                $r = $prop->checkValue($prop->getField('CODE'), $prop->getValue());
                if (!$r->isSuccess()) {
                    $result->addErrors($r->getErrors());
                }
            } else {
                $result->addErrors($r->getErrors());
            }
        }

        return $result;
    }

    /**
     * @return Result
     * @throws Exception
     */
    public function validateDelivery()
    {
        $result = new Result();

        $shipment = OrderHelper::getFirstNonSystemShipment($this->order);

        if ($shipment !== null) {
            if ($shipment->getDelivery() instanceof Delivery\Services\Base) {
                $obDelivery = $shipment->getDelivery();
                $availableDeliveries = Delivery\Services\Manager::getRestrictedObjectsList($shipment);
                if (!isset($availableDeliveries[$obDelivery->getId()])) {
                    $result->addError(new Error(
                                          Loc::getMessage(
                                              'OPEN_SOURCE_ORDER_DELIVERY_UNAVAILABLE',
                                              [
                                                  '#DELIVERY_NAME#' => $obDelivery->getNameWithParent()
                                              ]
                                          ),
                                          'delivery',
                                          [
                                              'type' => 'unavailable'
                                          ]
                                      ));
                }
            } else {
                $result->addError(new Error(
                                      Loc::getMessage('OPEN_SOURCE_ORDER_NO_DELIVERY_SELECTED'),
                                      'delivery',
                                      [
                                          'type' => 'undefined'
                                      ]
                                  ));
            }
        } else {
            $result->addError(new Error(
                                  Loc::getMessage('OPEN_SOURCE_ORDER_SHIPMENT_NOT_FOUND'),
                                  'delivery',
                                  [
                                      'type' => 'undefined'
                                  ]
                              ));
        }

        return $result;
    }

    /**
     * @return Result
     * @throws Exception
     */
    public function validatePayment()
    {
        $result = new Result();

        if (!$this->order->getPaymentCollection()->isEmpty()) {
            $payment = $this->order->getPaymentCollection()->current();
            /**
             * @var Payment $payment
             */
            $obPaySystem = $payment->getPaySystem();
            if ($obPaySystem instanceof PaySystem\Service) {
                $availablePaySystems = PaySystem\Manager::getListWithRestrictions($payment);
                if (!isset($availablePaySystems[$payment->getPaymentSystemId()])) {
                    $result->addError(new Error(
                                          Loc::getMessage(
                                              'OPEN_SOURCE_ORDER_PAYMENT_UNAVAILABLE',
                                              [
                                                  '#PAYMENT_NAME#' => $payment->getPaymentSystemName()
                                              ]
                                          ),
                                          'payment',
                                          [
                                              'type' => 'unavailable'
                                          ]
                                      ));
                }
            } else {
                $result->addError(new Error(
                                      Loc::getMessage('OPEN_SOURCE_ORDER_NO_PAY_SYSTEM_SELECTED'),
                                      'payment',
                                      [
                                          'type' => 'undefined'
                                      ]
                                  ));
            }
        } else {
            $result->addError(new Error(
                                  Loc::getMessage('OPEN_SOURCE_ORDER_NO_PAY_SYSTEM_SELECTED'),
                                  'payment',
                                  [
                                      'type' => 'undefined'
                                  ]
                              ));
        }

        return $result;
    }

    /**
     * @return Result
     * @throws Exception
     */
    public function validateOrder()
    {
        $result = new Result();

        $propValidationResult = $this->validateProperties();
        if (!$propValidationResult->isSuccess()) {
            $result->addErrors($propValidationResult->getErrors());
        }

        $deliveryValidationResult = $this->validateDelivery();
        if (!$deliveryValidationResult->isSuccess()) {
            $result->addErrors($deliveryValidationResult->getErrors());
        }

        $paymentValidationResult = $this->validatePayment();
        if (!$paymentValidationResult->isSuccess()) {
            $result->addErrors($paymentValidationResult->getErrors());
        }

        return $result;
    }

    public function refreshAjax() {

      global $USER, $APPLICATION;

        if ($USER->IsAuthorized()) {
            $filter = array("ID" => $USER->getId());
            $rsUsers = CUser::GetList(
                array(),
                ($order = "desc"),
                $filter,
                array('SELECT' => array('UF_TYPE_OF_BUYER'))
            );
            while ($res = $rsUsers->GetNext()) {
                if (!empty($res['UF_TYPE_OF_BUYER'])) {
                    switch ($res['UF_TYPE_OF_BUYER']) {
                        case 17:

                            $this->arResult['PROP']['PERSON_ID'] = 1;
                            $this->arParams['PERSON_TYPE_ID'] = 1;

                            break;

                        case 18:

                            $this->arResult['PROP']['PERSON_ID'] = 4;
                            $this->arParams['PERSON_TYPE_ID'] = 4;

                            break;

                        case 19:

                            $this->arResult['PROP']['PERSON_ID'] = 2;
                            $this->arParams['PERSON_TYPE_ID'] = 2;

                            break;

                        default:
                            $this->arResult['PROP']['PERSON_ID'] = 1;
                            $this->arParams['PERSON_TYPE_ID'] = 1;
                            break;
                    }
                } else {
                    $this->arResult['PROP']['PERSON_ID'] = 1;
                    $this->arParams['PERSON_TYPE_ID'] = 1;
                }
            }


        $this->createVirtualOrder($this->arParams['PERSON_TYPE_ID']);
        $propertiesList = $this->request['properties'] ?? $this->arParams['DEFAULT_PROPERTIES'] ?? [];
        if (!empty($propertiesList)) {
            $this->setOrderProperties($propertiesList);
        }
        if(!empty($this->arParams['location'])) {
            $changeLocation = $this->order->getPropertyCollection();
            $changeLocation->getDeliveryLocation()->setValue($this->arParams['location']);
        }
        $deliveryId = $this->request['delivery_id'] ?? $this->arParams['DEFAULT_DELIVERY_ID'] ?? 0;

        $this->createOrderShipment($deliveryId);

        $arResult['DELIVERY_ID'] = $this->deliveryID;

        $paySystemId = $this->request['pay_system_id'] ?? $this->arParams['DEFAULT_PAY_SYSTEM_ID'] ?? 0;
        $arResult['PAY_SYSTEM_ID'] = $paySystemId;

        if ($paySystemId > 0) {
            $this->createOrderPayment($paySystemId);
        }

        $arResult['DELIVERY_ERRORS'] = [];
        foreach ($this->errorCollection->getAllErrorsByCode('delivery') as $error) {
            $arResult['DELIVERY_ERRORS'][] = $error;
        }

        $arResult['DELIVERY_LIST'] = [];
        $shipment = OrderHelper::getFirstNonSystemShipment($this->order);
        if ($shipment !== null) {
            $availableDeliveries = Delivery\Services\Manager::getRestrictedObjectsList($shipment);
            $allDeliveryIDs = $this->order->getDeliveryIdList();
            $checkedDeliveryId = end($allDeliveryIDs);

            foreach (OrderHelper::calcDeliveries($shipment, $availableDeliveries, $this->arParams) as $deliveryID => $calculationResult) {
                /**
                 * @var Delivery\Services\Base $obDelivery
                 */
                $obDelivery = $availableDeliveries[$deliveryID];

                $arDelivery = [];
                $arDelivery['DESCRIPTION'] = $obDelivery->getDescription();

                if($arResult['DELIVERY_ID'] == $obDelivery->getId()) {
                    $arDelivery['EXTRA_SERVICE_OBJECTS']
                        = $obDelivery->getExtraServices()->getItems();

                    foreach ($arDelivery['EXTRA_SERVICE_OBJECTS'] as $key => $service) {
                        if($service->canUserEditValue()) {
                            $arDelivery['EXTRA_SERVICE'][$key]['CODE'] = $service->getCode();
                            $arDelivery['EXTRA_SERVICE'][$key]['KEY'] = $key;
                            $arDelivery['EXTRA_SERVICE'][$key]['NAME'] = $service->getName();

                            if($service->getValue() == 'Y') {
                                $arDelivery['EXTRA_SERVICE'][$key]['ACTIVE'] = 'Y';
                            }
                        }
                    }
                }
                $arDelivery['ID'] = $obDelivery->getId();
                $arDelivery['IMG'] = CFile::GetPath($obDelivery->getLogotip());
                $arDelivery['NAME'] = $obDelivery->getName();
                $arDelivery['PRICE'] = $calculationResult->isSuccess() ? $calculationResult->getPrice() : 'error';
                $arDelivery['PRICE_DISPLAY'] = SaleFormatCurrency(
                    $calculationResult->getDeliveryPrice(),
                    $this->order->getCurrency()
                );

                $arResult['DELIVERY'][$deliveryID] = $arDelivery;
            }

            foreach (GetModuleEvents("sale", 'OnSaleComponentOrderOneStepDelivery', true) as $arEvent)
                ExecuteModuleEventEx($arEvent, [&$arResult, &$arModufUser, &$param, true]);
            
                $i = 0; // Простите за костыль
                foreach($arResult['DELIVERY'] as $key => $delivery) {
                    $arResult['DELIVERY_TEMP'][$i++] = $delivery;
                 }
                 unset($arResult['DELIVERY']);
                 $arResult['DELIVERY'] = $arResult['DELIVERY_TEMP'];

        }


        /**
         * PAY SYSTEM
         */
        $arResult['PAY_SYSTEM_ERRORS'] = [];
        foreach ($this->errorCollection->getAllErrorsByCode('payment') as $error) {
            $arResult['PAY_SYSTEM_ERRORS'][] = $error;
        }

        $arResult['PAY_SYSTEM_LIST'] = [];
        $availablePaySystem = OrderHelper::getAvailablePaySystems($this->order);
        $checkedPaySystemId = 0;
        if (!$this->order->getPaymentCollection()->isEmpty()) {
            $payment = $this->order->getPaymentCollection()->current();
            $checkedPaySystemId = $payment->getPaymentSystemId();
        }
        $i = 0; // Простите за костыль
        foreach ($availablePaySystem as $paySystem) {
            $arPaySystem = [];

            $arPaySystem['ID'] = $paySystem->getField('ID');
            $arPaySystem['NAME'] = $paySystem->getField('NAME');
            $arPaySystem['CHECKED'] = $arPaySystem['ID'] === $checkedPaySystemId;

            $arResult['PAY_SYSTEM_LIST'][$i++] = $arPaySystem;
        }

        /**
         * BASKET
         */
        $arResult['BASKET'] = [];
        foreach ($this->order->getBasket() as $basketItem) {
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



        $arResult['PRODUCTS_BASE_PRICE'] = $this->order->getBasket()->getBasePrice();
        $arResult['PRODUCTS_BASE_PRICE_DISPLAY'] =  $arResult['PRODUCTS_BASE_PRICE'];


        $arResult['PRODUCTS_PRICE'] = $this->order->getBasket()->getPrice();
        $arResult['PRODUCTS_PRICE_DISPLAY'] = $arResult['PRODUCTS_PRICE'];


        $arResult['PRODUCTS_DISCOUNT'] = $arResult['PRODUCTS_BASE_PRICE'] - $arResult['PRODUCTS_PRICE'];
        $arResult['PRODUCTS_DISCOUNT_DISPLAY'] = $arResult['PRODUCTS_DISCOUNT'];

        /**
         * ORDER TOTAL DELIVERY PRICES
         */
        $arShowPrices = $this->order->getDiscount()
            ->getShowPrices();


        $arResult['DELIVERY_BASE_PRICE'] = $arShowPrices['DELIVERY']['BASE_PRICE'] ?? 0;
        $arResult['DELIVERY_BASE_PRICE_DISPLAY'] = $arResult['DELIVERY_BASE_PRICE'];


        if($this->order->getDeliveryPrice() == 0) {
            if($arResult['DELIVERY'][$arResult['DELIVERY_ID']]['PRICE'] == 'error') {
                $price = 'error';
            }
            else {
                $price = 0;
            }
        } else {
            $price = $this->order->getDeliveryPrice();
        }

        $arResult['DELIVERY_PRICE'] = $price;
        $arResult['DELIVERY_PRICE_DISPLAY'] = $arResult['DELIVERY_PRICE'];


        $arResult['DELIVERY_DISCOUNT'] = $arShowPrices['DELIVERY']['DISCOUNT'] ?? 0;
        $arResult['DELIVERY_DISCOUNT_DISPLAY'] = $arResult['DELIVERY_PRICE'];


        $arResult['SUM_BASE'] = $arResult['PRODUCTS_BASE_PRICE'] + $arResult['DELIVERY_BASE_PRICE'];
        $arResult['SUM_BASE_DISPLAY'] =  $arResult['SUM_BASE'];


        $arResult['DISCOUNT_VALUE'] = $arResult['SUM_BASE'] - $this->order->getPrice();
        $arResult['DISCOUNT_VALUE_DISPLAY'] = $arResult['DISCOUNT_VALUE'];

        $arResult['SUM'] = $this->order->getPrice();
        $arResult['SUM_DISPLAY'] = $arResult['SUM'];

      return $arResult;
        }
    }

    public function executeComponent()
    {
         global $USER, $APPLICATION;

        if($_REQUEST['refresh'] == 'Y') {
            $arResult =   self::refreshAjax();
            $APPLICATION->RestartBuffer();
            header('Content-Type: application/json');
            echo Json::encode($arResult);
            CMain::FinalActions();
            die();
        }

        if(!$USER->IsAuthorized()) {

            $this->arResult['REGISTER'] = 'N';
            $this->includeComponentTemplate();
            return;
        }



        try {
            $this->arResult['REGISTER'] = 'Y';
            global $USER;
            if ($USER->IsAuthorized())  {

                $filter = Array("ID" => $USER->getId());
                $rsUsers = CUser::GetList(array(), ($order="desc"), $filter, array('SELECT' => array('UF_INN','UF_LEGAL_ADRESS','UF_INN','UF_NAME_ORGANIZATION','UF_KPP','UF_SURVEY','UF_TYPE_OF_BUYER','UF_HOUSE')));
                while($res = $rsUsers->GetNext()) {


                    if(!empty($res['UF_TYPE_OF_BUYER'])) {
                        switch ($res['UF_TYPE_OF_BUYER']) {
                            case 17:

                                $this->arResult['PROP']['PERSON_ID'] = 1;
                                $this->arParams['PERSON_TYPE_ID'] = 1;

                                break;

                            case 18:

                                $this->arResult['PROP']['PERSON_ID'] = 4;
                                $this->arParams['PERSON_TYPE_ID'] = 4;

                                break;

                            case 19:

                                $this->arResult['PROP']['PERSON_ID'] = 2;
                                $this->arParams['PERSON_TYPE_ID'] = 2;

                                break;

                            default:
                                $this->arResult['PROP']['PERSON_ID'] = 1;
                                $this->arParams['PERSON_TYPE_ID'] = 1;
                                break;

                        }
                    }


                    if(!empty($res['PERSONAL_ZIP'])) {
                        $this->arResult['PROP']['ZIP'] = $res['PERSONAL_ZIP'];
                    }

                    if(!empty($res['PERSONAL_CITY'])) {
                        $this->arResult['PROP']['CITY'] = $res['PERSONAL_CITY'];
                    }

                    if(!empty($res['PERSONAL_STATE'])) {
                        $this->arResult['PROP']['REGION'] = $res['PERSONAL_STATE'];
                    }

                    if(!empty($res['PERSONAL_STATE'])) {
                        $this->arResult['PROP']['REGION'] = $res['PERSONAL_STATE'];
                    }

                    if(!empty($res['PERSONAL_STREET'])) {
                     $this->arResult['PROP']['STREET'] = $res['PERSONAL_STREET'];
                     }
                    if(!empty($res['UF_HOUSE'])) {
                        $this->arResult['PROP']['HOUSE'] = $res['UF_HOUSE'];
                    }


                    if(!empty($res['LAST_NAME'])) {
                        $this->arResult['PROP']['CONTACT_PERSON'] = $res['NAME'].' '.$res['LAST_NAME'].' '.$res['SECOND_NAME'];
                    }

                    if(!empty($res['NAME'])) {
                        $this->arResult['PROP']['NAME'] = $res['NAME'];
                    }

                    if(!empty($res['LAST_NAME'])) {
                        $this->arResult['PROP']['FAMILY'] = $res['LAST_NAME'];
                    }

                    if(!empty($res['SECOND_NAME'])) {
                        $this->arResult['PROP']['LAST_NAME'] = $res['SECOND_NAME'];
                    }

                    if(!empty($res['UF_LEGAL_ADRESS'])) {
                        $this->arResult['PROP']['COMPANY_ADR'] = $res['UF_LEGAL_ADRESS'];
                    }

                    if(!empty($res['EMAIL'])) {
                        $this->arResult['PROP']['EMAIL'] = $res['EMAIL'];
                    }

                    if(!empty($res['PERSONAL_PHONE'])) {
                        $this->arResult['PROP']['PHONE'] = $res['PERSONAL_PHONE'];
                    }
                    
                    if(!empty($res['WORK_PHONE'])) {
                        $this->arResult['PROP']['WORK_PHONE'] = $res['WORK_PHONE'];
                    }

                    if(!empty($res['UF_NAME_ORGANIZATION'])) {
                        $this->arResult['PROP']['COMPANY'] = $res['UF_NAME_ORGANIZATION'];
                    }


                    if(!empty($res['UF_INN'])) {
                        $this->arResult['PROP']['INN'] = $res['UF_INN'];
                    }
                    if(!empty($res['UF_KPP'])) {
                        $this->arResult['PROP']['KPP'] = $res['UF_KPP'];
                    }

                }
            }

            if($_REQUEST['step'] != '2') {
                $this->includeComponentTemplate();
                return;
            }

            $this->createVirtualOrder($this->arParams['PERSON_TYPE_ID']);

            $propertiesList = $this->request['properties'] ?? $this->arParams['DEFAULT_PROPERTIES'] ?? [];
            if (!empty($propertiesList)) {
                $this->setOrderProperties($propertiesList);
            }

            if(!empty($this->arParams['location'])) {
                $changeLocation = $this->order->getPropertyCollection();
                $changeLocation->getDeliveryLocation()->setValue($this->arParams['location']);

               // $zip =   Sale\Location\Admin\LocationHelper::getZipByLocation($this->arParams['location']);

              /*  while($zipData = $zip->fetch()) {
                    if($zipData['SERVICE_CODE'] == "ZIP") {
                        $zipValue = $zipData['XML_ID'];
                        $changeLocation->getDeliveryLocationZip()->setValue($zipValue);
                        break;
                    }
                }*/
            }



            $deliveryId = $this->request['delivery_id'] ?? $this->arParams['DEFAULT_DELIVERY_ID'] ?? 0;
            $this->createOrderShipment($deliveryId);

            $paySystemId = $this->request['pay_system_id'] ?? $this->arParams['DEFAULT_PAY_SYSTEM_ID'] ?? 0;
            if ($paySystemId > 0) {
                $this->createOrderPayment($paySystemId);
            }

        } catch (Exception $exception) {
            $this->errorCollection->setError(new Error($exception->getMessage()));
        }

        $this->includeComponentTemplate();
    }

}
?>

