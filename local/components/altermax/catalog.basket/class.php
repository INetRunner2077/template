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

        $idProduct = array();
        foreach ($basketItems as $basketItem) {
            $arrBasketItems = $basketItem->toArray();
            $idProduct[] = $arrBasketItems['PRODUCT_ID'];
            $arResult['BASKET'][$arrBasketItems['PRODUCT_ID']]['NAME'] = $arrBasketItems['NAME'];
            $arResult['BASKET'][$arrBasketItems['PRODUCT_ID']]['BASE_PRICE'] = number_format($arrBasketItems['BASE_PRICE'], 2, '.', '');
            $arResult['BASKET'][$arrBasketItems['PRODUCT_ID']]['QUANTITY'] = (int)$arrBasketItems['QUANTITY'];
            $arResult['BASKET'][$arrBasketItems['PRODUCT_ID']]['NAME'] = $arrBasketItems['NAME'];
            $arResult['BASKET'][$arrBasketItems['PRODUCT_ID']]['COST_ITEM'] = number_format((int)$arrBasketItems['QUANTITY'] * (float)$arrBasketItems['BASE_PRICE'], 2, '.', '');
            $arResult['BASKET'][$arrBasketItems['PRODUCT_ID']]['DETAIL_PAGE_URL'] = $arrBasketItems['DETAIL_PAGE_URL'];

            }


        $arrCountProduct = \Bitrix\Catalog\ProductTable::getList(
            [
                'select' => [
                    'ID',
                    'QUANTITY',
                ],
                'filter' => ['ID' => $idProduct],
            ]
        );

        while ($CountProduct = $arrCountProduct->fetch()) {
            $arResult['BASKET'][$CountProduct['ID']]['MAX_QUANTITY'] = $CountProduct['QUANTITY'];
        }


        $basket = Sale\Basket::loadItemsForFUser($userId, $siteId);
        $order = Sale\Order::create($siteId, $userId);
        $order->setPersonTypeId(1);
        $order->setBasket($basket);

        $discounts = $order->getDiscount();
        $discounts->getApplyResult();

        $arResult['TOTAL_COST'] = number_format($basket->getPrice(), 2, '.', '');
        $this->arResult = $arResult;
        $this->includeComponentTemplate();
        }

    }



