<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Sale;
/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
$basketItems = $basket->getBasketItems();

$arResult['ITEM_HAS_IN_CART'] = false;

foreach ($basketItems as $basketItem) {
    if ($basketItem->getField('PRODUCT_ID') == $arResult['ID'] && $basketItem->getField('ORDER_ID') === null && $basketItem->getField('DELAY') == 'N') {
        $arResult['ITEM_HAS_IN_CART'] = true;
        break;
    }
}