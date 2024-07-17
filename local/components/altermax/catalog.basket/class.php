<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Sale;
use Bitrix\Main\Loader;
class Registry extends CBitrixComponent
{

    public function executeComponent()
    {
        global $APPLICATION, $USER;
        Loader::includeModule('sale');
        $userId = Sale\Fuser::getId();
        $siteId = Bitrix\Main\Context::getCurrent()->getSite();
        $basket = Sale\Basket::loadItemsForFUser($userId, $siteId);
        $basketItems = $basket->getBasketItems();


        foreach ($basketItems as $basketItem) {
            $arrBasketItems = $basketItem->toArray();
            $arResult['BASKET'][$arrBasketItems['PRODUCT_ID']]['NAME'] = $arrBasketItems['NAME'];
            $arResult['BASKET'][$arrBasketItems['PRODUCT_ID']]['BASE_PRICE'] = $arrBasketItems['BASE_PRICE'];
            $arResult['BASKET'][$arrBasketItems['PRODUCT_ID']]['QUANTITY'] = (int)$arrBasketItems['QUANTITY'];
            $arResult['BASKET'][$arrBasketItems['PRODUCT_ID']]['NAME'] = $arrBasketItems['NAME'];
            $arResult['BASKET'][$arrBasketItems['PRODUCT_ID']]['COST_ITEM'] = (int)$arrBasketItems['QUANTITY'] * (float)$arrBasketItems['BASE_PRICE'];

            }
        $basket = Sale\Basket::loadItemsForFUser($userId, $siteId);
        $order = Sale\Order::create($siteId, $userId);
        $order->setPersonTypeId(1);
        $order->setBasket($basket);

        $discounts = $order->getDiscount();
        $discounts->getApplyResult();

        $arResult['TOTAL_COST'] = $basket->getPrice();
        $this->arResult = $arResult;
        $this->includeComponentTemplate();
        }

    }



