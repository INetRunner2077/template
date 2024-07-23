<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Request;
use Bitrix\Sale\Delivery;
use Bitrix\Sale\Location\Search\Finder;
use Bitrix\Sale\Location\TypeTable;
use OpenSource\Order\LocationHelper;
use OpenSource\Order\OrderHelper;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserTable;
use \Bitrix\Main\Security\Random;
class OpenSourceOrderAjaxController extends Controller
{

    /**
     * @param Request|null $request
     * @throws LoaderException
     */
    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        Loader::includeModule('sale');
        Loader::includeModule('opensource.order');
    }

    public function cityByCodeAction($code) {

        $location =  LocationHelper::getDisplayByCode($code);

        if(!empty ($location['parts']['CITY'])) {
            return $location['parts']['CITY'];
        } else {
            return;
        }

    }


    /**
     * @return array
     */
    public function configureActions(): array
    {
        return [
            'searchLocation' => [
                'prefilters' => []
            ],
            'calculateDeliveries' => [
                'prefilters' => []
            ],
            'saveOrder' => [
                'prefilters' => []
            ],
            'cityByCode' => [
                'prefilters' => []
            ],
            'searchInn' => [
                'prefilters' => []
            ],
            'register' => [
                'prefilters' => []
            ],
            'locationBitrixKlav' => [
                'prefilters' => []
            ]
        ];
    }


    public static function locationBitrixKlavAction($city) {
\Bitrix\Main\Loader::includeModule('sale');
        $res = \Bitrix\Sale\Location\LocationTable::getList(
            array(
                'filter' => array(
                    '=NAME.LANGUAGE_ID' => LANGUAGE_ID,
                    'TYPE_CODE'         => 'CITY',
                    'NAME_RU'           => $city,
                ),
                'select' => array(
                    '*',
                    'NAME_RU' => 'NAME.NAME',
                    'TYPE_CODE' => 'TYPE.CODE',
                ),
            )
        );

if ($item = $res->fetch()) {
    return $item;
} else {
    header("HTTP/1.0 404 Not Found");
    return $city;
}

    }

    /**
     * @param string $q
     * @param int $limit
     * @param string $typeCode
     * @param array $excludeParts
     * @param string $sortOrder
     * @return array
     * @throws Exception
     */
    public static function searchLocationAction(
        string $q,
        int $limit = 5,
        string $typeCode = '',
        array $excludeParts = [],
        string $sortOrder = 'desc'
    ): array {
        $foundLocations = [];

        if ($q !== '') {
            if ($limit > 50 || $limit < 1) {
                $limit = 50;
            }

            //getting location type
            $typeId = null;
            if(!empty($typeCode)) {
                $arType = TypeTable::getList([
                                                 'select' => [
                                                     'ID',
                                                     'CODE'
                                                 ],
                                                 'filter' => [
                                                     '=CODE' => $typeCode
                                                 ]
                                             ])
                    ->fetch();

                if (!empty($arType)) {
                    $typeId = $arType['ID'];
                }
            }

            $result = Finder::find([
                                       'select' => [
                                           'ID',
                                           'CODE',
                                       ],
                                       'filter' => [
                                           'PHRASE' => $q,
                                           'TYPE_ID' => $typeId
                                       ],
                                       'limit' => $limit
                                   ]);

            while ($arLocation = $result->fetch()) {
                $foundLocations[] = LocationHelper::getDisplayByCode($arLocation['CODE'], $excludeParts, $sortOrder);
            }
        }

        return $foundLocations;
    }


    public static function searchInnAction(
        string $q,
    ) {


        $get = array(
            'inn'  => $q,
        );

        $ch = curl_init('https://almi.ru/ajax/autocomplete/companies?' . http_build_query($get));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $html = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($html);
        $innData = array();

        if(empty($response->suggestions)) {
            $innData[0]['text'] = $q;
            $innData[0]['id'] = rand(1, 1000);
        } else {

            foreach ($response->suggestions as $key => $data) {

                $innData[$key]['text'] = $data->data->name->short;
                $innData[$key]['id'] = $key;
                $innData[$key]['kpp'] = $data->data->kpp;
                $innData[$key]['long__name'] = $data->data->name->short_with_opf;
                $innData[$key]['address'] = $data->data->address->unrestricted_value;
                $innData[$key]['inn'] = $data->data->inn;
            }
        }
        return $innData;

    }

    /**
     * @param int $person_type_id
     * @param array $properties
     * @param array $delivery_ids
     * @return array
     *
     * @throws Exception
     */
    public static function calculateDeliveriesAction(
        int $person_type_id,
        array $PROPERTIES,
        array $delivery_ids = []
    ): array {
        Loader::includeModule("sale");
        CBitrixComponent::includeComponentClass('opensource:order');
        $componentClass = new OpenSourceOrderComponent();
        $componentClass->createVirtualOrder($person_type_id);
        $componentClass->setOrderProperties($PROPERTIES);
        $shipment = $componentClass->createOrderShipment();

        $availableDeliveries = Delivery\Services\Manager::getRestrictedObjectsList($shipment);


        //calc only needed if argument given
        if (!empty($delivery_ids)) {
            $availableDeliveries = array_intersect(
                $availableDeliveries,
                array_flip($delivery_ids)
            );
        }

        $calculatedDeliveries = [];
        foreach (OrderHelper::calcDeliveries($shipment, $availableDeliveries) as $deliveryId => $calculationResult) {
            $obDelivery = $availableDeliveries[$deliveryId];

            $arDelivery = [
                'id' => $obDelivery->getId(),
                'success' => $calculationResult->isSuccess(),
                'name' => $obDelivery->getName(),
                'logo_path' => $obDelivery->getLogotipPath(),
                'period' => $calculationResult->getPeriodDescription(),
                'base_price' =>  $calculationResult->isSuccess() ? $calculationResult->getPrice() : 0,
                'base_price_display' =>$calculationResult->isSuccess() ? SaleFormatCurrency(
                    $calculationResult->isSuccess() ? $calculationResult->getPrice() : 'error',
                    $componentClass->order->getCurrency()
                ) : 'Не расчитана',
            ];

            $data = $calculationResult->getData();
            if (!empty($data['DISCOUNT_DATA'])) {
                $arDelivery['price'] = $data['DISCOUNT_DATA']['PRICE'];
                $arDelivery['price_display'] = SaleFormatCurrency(
                    $arDelivery['price'],
                    $componentClass->order->getCurrency()
                );
                $arDelivery['discount'] = $data['DISCOUNT_DATA']['DISCOUNT'];
                $arDelivery['discount_display'] = SaleFormatCurrency(
                    $arDelivery['discount'],
                    $componentClass->order->getCurrency()
                );
            } else {
                $arDelivery['price'] = $arDelivery['base_price'];
                $arDelivery['price_display'] = $arDelivery['base_price_display'];
                $arDelivery['discount'] = 0;
            }

            $arDelivery['errors'] = [];
            if (!$calculationResult->isSuccess()) {
                foreach ($calculationResult->getErrorMessages() as $message) {
                    $arDelivery['errors'][] = $message;
                }
            }

            $calculatedDeliveries[$arDelivery['id']] = $arDelivery;
        }

        return $calculatedDeliveries;
    }

    protected static function getExternalUserTypes(): array
    {
        return array_diff(\Bitrix\Main\UserTable::getExternalUserTypes(), ['shop']);
    }

    protected static function needToRegister(): bool
    {
        global $USER;

        if (!$USER->IsAuthorized())
        {
            $isRealUserAuthorized = false;
        }
        else
        {
            $user = UserTable::getList(
                [
                    'filter' => [
                        '=ID' => (int)$USER->getId(),
                        '=ACTIVE' => 'Y',
                        '!=EXTERNAL_AUTH_ID' => $this->getExternalUserTypes()
                    ]
                ]
            )->fetchObject();

            if ($user)
            {
                $isRealUserAuthorized = true;
            }
            else
            {
                $isRealUserAuthorized = false;
            }
        }
        $arParams['ALLOW_AUTO_REGISTER'] = 'Y';
        if (!$isRealUserAuthorized && $arParams['ALLOW_AUTO_REGISTER'] === 'Y')
        {
            return true;
        }

        return false;
    }

    public static function registerAction($PROPERTIES) {
        global $USER, $DB, $APPLICATION;

        $filter = Array
        (
            "EMAIL" => $PROPERTIES['EMAIL'],
        );
        $rsUsers = CUser::GetList(array(), array(), $filter);
        if($arUser = $rsUsers->Fetch()){
            return throw new Exception('EMAIL_EXIST');
        }

        if(empty($PROPERTIES['EMAIL']) or empty($PROPERTIES['NAME'])) {
            return;
        }
        $arResult['VALUES']['EMAIL'] = $PROPERTIES['EMAIL'];
        $arResult['VALUES']['LOGIN'] = $PROPERTIES['EMAIL'];


        if (!filter_var($arResult['VALUES']['EMAIL'], FILTER_VALIDATE_EMAIL)) {

            return throw new Exception('Не валидный EMAIL');

        }


        !empty($PROPERTIES['FAMILY']) ? $arResult['VALUES']['LAST_NAME'] = $PROPERTIES['FAMILY'] : '';
        !empty($PROPERTIES['NAME']) ? $arResult['VALUES']['NAME'] = $PROPERTIES['NAME'] : '';
        !empty($PROPERTIES['LAST_NAME']) ? $arResult['VALUES']['SECOND_NAME'] = $PROPERTIES['LAST_NAME'] : '';


        $arResult['VALUES']["GROUP_ID"] = array();
        $def_group = COption::GetOptionString("main", "new_user_registration_def_group", "");
        if($def_group != "")
            $arResult['VALUES']["GROUP_ID"] = explode(",", $def_group);

        $arResult['VALUES']['PASSWORD'] = \CUser::GeneratePasswordByPolicy($arResult['VALUES']["GROUP_ID"]);
        $arResult['VALUES']["CHECKWORD"] = Random::getString(32);
        $arResult['VALUES']["~CHECKWORD_TIME"] = $DB->CurrentTimeFunction();
        $arResult['VALUES']["ACTIVE"] = 'Y';
        $arResult['VALUES']["LID"] = SITE_ID;
        $arResult['VALUES']["LANGUAGE_ID"] = LANGUAGE_ID;

        $arResult['VALUES']["USER_IP"] = $_SERVER["REMOTE_ADDR"];
        $arResult['VALUES']["USER_HOST"] = @gethostbyaddr($_SERVER["REMOTE_ADDR"]);

        if($arResult["VALUES"]["AUTO_TIME_ZONE"] <> "Y" && $arResult["VALUES"]["AUTO_TIME_ZONE"] <> "N")
            $arResult["VALUES"]["AUTO_TIME_ZONE"] = "";
        $bOk = true;

        $events = GetModuleEvents("main", "OnBeforeUserRegister", true);
        foreach($events as $arEvent)
        {
            if(ExecuteModuleEventEx($arEvent, array(&$arResult['VALUES'])) === false)
            {
                if($err = $APPLICATION->GetException())
                    $arResult['ERRORS'][] = $err->GetString();

                $bOk = false;
                break;
            }
        }

        /* Присваиваем дефолтное значение пользовательскому полю с типом покупателя (частное лицо) */
        $obEnum = new \CUserFieldEnum;
        $rsEnum = $obEnum->GetList(array(), array("USER_FIELD_NAME" => 'UF_TYPE_OF_BUYER', 'XML_ID' => 'PRIVATE'));
        $enum = array();
        if($arEnum = $rsEnum->Fetch())
        {
            $personIdDefault = $arEnum['ID'];
        }

        $arResult['VALUES']['UF_TYPE_OF_BUYER'] = $personIdDefault;
        /* Присваиваем дефолтное значение пользовательскому полю с типом покупателя (частное лицо) */

        $ID = 0;
        $user = new CUser();
        if ($bOk)
        {
            $ID = $user->Add($arResult["VALUES"]);
        }

        if (intval($ID) > 0)
        {

            $register_done = true;

            if (!$arAuthResult = $USER->Login($arResult["VALUES"]["LOGIN"], $arResult["VALUES"]["PASSWORD"]))
                $arResult["ERRORS"][] = $arAuthResult;

        }
        //CUser::SendUserInfo($USER->GetID(), SITE_ID, Loc::getMessage('INFO_REQ'), true);
        $arEventFields['EMAIL'] = $arResult['VALUES']['EMAIL'];
        $arEventFields['PASSWORD'] =  $arResult['VALUES']['PASSWORD'];
        $arEventFields['CHECKWORD'] = $arResult['VALUES']["CHECKWORD"];
        $arEventFields['URL_LOGIN'] = $arResult['VALUES']['LOGIN'];

        $event = new CEvent;
        $event->SendImmediate("USER_INFO", SITE_ID, $arEventFields,'N',107);
        
        if(empty($arResult['ERRORS'])) {
            return 'OK';
        } else {
            return $arResult['ERRORS'];
        }

    }

    /**
     * @param int $person_type_id
     * @param array $properties
     * @param int $delivery_id
     * @param int $pay_system_id
     * @return array
     * @throws Exception
     */
    public function saveOrderAction(array $PROPERTIES, int $delivery_id, int $pay_system_id, $service = array()): array
    {
        global $USER;

        if (!$USER->IsAuthorized())  {
            return 'Не авторизован';
        }
            $filter = Array("ID" => $USER->getId());
            $rsUsers = CUser::GetList(array(), ($order="desc"), $filter, array('SELECT' => array('UF_INN','UF_LEGAL_ADRESS','UF_INN','UF_NAME_ORGANIZATION','UF_KPP','UF_SURVEY','UF_TYPE_OF_BUYER')));

            /*  заполняем данные с аккаунта */
            while($res = $rsUsers->GetNext()) {
                if(!empty($res['UF_TYPE_OF_BUYER'])) {
                    switch ($res['UF_TYPE_OF_BUYER']) {
                        case 17:
                            $person_type_id = 1;
                            break;

                        case 18:
                            $person_type_id = 4;
                            break;

                        case 19:
                            $person_type_id = 2;
                            break;

                        default:
                            $person_type_id = 1;
                            break;
                    }
                } else { $person_type_id = 1;  }


                if(!empty($res['NAME'])) {
                    $PROPERTIES['NAME'] = $res['NAME'];
                }

                if(!empty($res['SECOND_NAME'])) {
                    $PROPERTIES['SECOND_NAME'] = $res['SECOND_NAME'];
                }

                if(!empty($res['LAST_NAME'])) {
                    $PROPERTIES['FAMILY'] = $res['LAST_NAME'];
                }

                if(!empty($res['EMAIL'])) {
                    $PROPERTIES['EMAIL'] = $res['EMAIL'];
                }
            
                /* если телефон не указан, то берем из профиля */

                if(empty($PROPERTIES['PHONE'])) {
            
                    if(!empty($res['PERSONAL_PHONE'])) {

                        $PROPERTIES['PHONE'] = $res['PERSONAL_PHONE'];
                }
            }

            if(empty($PROPERTIES['WORK_PHONE'])) {
            
                if(!empty($res['WORK_PHONE'])) {

                    $PROPERTIES['WORK_PHONE'] = $res['WORK_PHONE'];
            }
        }
               /* если телефон не указан, то берем из профиля */

            /*  заполняем данные с аккаунта */
        }
           /* Обновляем данные о месте доставке */


        if (!empty($PROPERTIES['CITY'])) {
            $updateFields['PERSONAL_CITY'] = $PROPERTIES['CITY'];
        }
        if (!empty($PROPERTIES['WORK_PHONE'])) {
            $updateFields['WORK_PHONE'] = $PROPERTIES['WORK_PHONE'];
        }


        if (!empty($PROPERTIES['REGION'])) {
            $updateFields['PERSONAL_STATE'] = $PROPERTIES['REGION'];
        }

        if (!empty($PROPERTIES['STREET'])) {
            $updateFields['PERSONAL_STREET'] = $PROPERTIES['STREET'];
        }

        if (!empty($PROPERTIES['HOUSE'])) {
            $updateFields['UF_HOUSE'] = $PROPERTIES['HOUSE'];
        }

        if (!empty($PROPERTIES['ZIP'])) {
            $updateFields['PERSONAL_ZIP'] = $PROPERTIES['ZIP'];
        }
            /* Обновляем данные о месте доставке */


            if(empty($PROPERTIES['NAME'])) {
                $PROPERTIES['NAME'] = $PROPERTIES['FAMILY'];
            }
            if(empty($PROPERTIES['PHONE'])) {
                $PROPERTIES['PHONE'] = '+799999999';
            }
            if($delivery_id == 41) {
                if (!empty($PROPERTIES['PHONE_PDP'])) {
                    $PROPERTIES['PHONE'] = $PROPERTIES['PHONE_PDP'];
                }
            }
            if(empty($PROPERTIES['FAMILY'])) {
                $PROPERTIES['FAMILY'] = "Без имени";
            }
            if(empty($PROPERTIES['NAME'])) {
                $PROPERTIES['NAME'] = "Без имени";
            }

        $user = new CUser;
        $user->Update($USER->getId(), $updateFields);



        CBitrixComponent::includeComponentClass('opensource:order');
        $componentClass = new OpenSourceOrderComponent();
        if(!empty($service)) {

            foreach ($service as $value) {
                $this->arParams['SERVICE'][] =  $value;
            }

        }


        $userId = $USER->getId();

        $componentClass->createVirtualOrder($person_type_id, $userId);
        $componentClass->setOrderProperties($PROPERTIES);
        $componentClass->createOrderShipment($delivery_id, $this->arParams['SERVICE']);
        $componentClass->createOrderPayment($pay_system_id);


        $validationResult = $componentClass->validateOrder();
        if ($validationResult->isSuccess()) {
           $saveResult =  $componentClass->order->save();
            if ( $saveResult->isSuccess()) {
                $data['saved'] = true;
                $data['order_id'] = $saveResult->getId();
                foreach (GetModuleEvents('sale', 'OnSaleComponentOrderOneStepComplete', true) as $arEvent)
                {
                    ExecuteModuleEventEx($arEvent, [$data['order_id'], $componentClass->order->getFieldValues(), $this->arParams]);
                }
            } else {
                $this->errorCollection->add($saveResult->getErrors());
            }
        } else {

            $this->errorCollection->add($validationResult->getErrors());
            return $validationResult->getErrors();
        }

        return $data;
    }



}