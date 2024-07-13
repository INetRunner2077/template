<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Catalog\ProductTable;
use Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);
$this->addExternalCss('/bitrix/css/main/bootstrap.css');

$templateLibrary = array('popup', 'fx', 'ui.fonts.opensans');
$currencyList = '';

if (!empty($arResult['CURRENCIES']))
{
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$haveOffers = !empty($arResult['OFFERS']);

$templateData = [
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList,
	'ITEM' => [
		'ID' => $arResult['ID'],
		'IBLOCK_ID' => $arResult['IBLOCK_ID'],
	],
];
if ($haveOffers)
{
	$templateData['ITEM']['OFFERS_SELECTED'] = $arResult['OFFERS_SELECTED'];
	$templateData['ITEM']['JS_OFFERS'] = $arResult['JS_OFFERS'];
}
unset($currencyList, $templateLibrary);

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
	'ID' => $mainId,
	'DISCOUNT_PERCENT_ID' => $mainId.'_dsc_pict',
	'STICKER_ID' => $mainId.'_sticker',
	'BIG_SLIDER_ID' => $mainId.'_big_slider',
	'BIG_IMG_CONT_ID' => $mainId.'_bigimg_cont',
	'SLIDER_CONT_ID' => $mainId.'_slider_cont',
	'OLD_PRICE_ID' => $mainId.'_old_price',
	'PRICE_ID' => $mainId.'_price',
	'DESCRIPTION_ID' => $mainId.'_description',
	'DISCOUNT_PRICE_ID' => $mainId.'_price_discount',
	'PRICE_TOTAL' => $mainId.'_price_total',
	'SLIDER_CONT_OF_ID' => $mainId.'_slider_cont_',
	'QUANTITY_ID' => $mainId.'_quantity',
	'QUANTITY_DOWN_ID' => $mainId.'_quant_down',
	'QUANTITY_UP_ID' => $mainId.'_quant_up',
	'QUANTITY_MEASURE' => $mainId.'_quant_measure',
	'QUANTITY_LIMIT' => $mainId.'_quant_limit',
	'BUY_LINK' => $mainId.'_buy_link',
	'ADD_BASKET_LINK' => $mainId.'_add_basket_link',
	'BASKET_ACTIONS_ID' => $mainId.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $mainId.'_not_avail',
	'COMPARE_LINK' => $mainId.'_compare_link',
	'TREE_ID' => $mainId.'_skudiv',
	'DISPLAY_PROP_DIV' => $mainId.'_sku_prop',
	'DISPLAY_MAIN_PROP_DIV' => $mainId.'_main_sku_prop',
	'OFFER_GROUP' => $mainId.'_set_group_',
	'BASKET_PROP_DIV' => $mainId.'_basket_prop',
	'SUBSCRIBE_LINK' => $mainId.'_subscribe',
	'TABS_ID' => $mainId.'_tabs',
	'TAB_CONTAINERS_ID' => $mainId.'_tab_containers',
	'SMALL_CARD_PANEL_ID' => $mainId.'_small_card_panel',
	'TABS_PANEL_ID' => $mainId.'_tabs_panel'
);
$obName = $templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
$name = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
	: $arResult['NAME'];
$title = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
	: $arResult['NAME'];
$alt = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
	: $arResult['NAME'];

if ($haveOffers)
{
	$actualItem = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']] ?? reset($arResult['OFFERS']);
	$showSliderControls = false;

	foreach ($arResult['OFFERS'] as $offer)
	{
		if ($offer['MORE_PHOTO_COUNT'] > 1)
		{
			$showSliderControls = true;
			break;
		}
	}
}
else
{
	$actualItem = $arResult;
	$showSliderControls = $arResult['MORE_PHOTO_COUNT'] > 1;
}

$skuProps = array();
$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
$measureRatio = $actualItem['ITEM_MEASURE_RATIOS'][$actualItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
$showDiscount = $price['PERCENT'] > 0;

if ($arParams['SHOW_SKU_DESCRIPTION'] === 'Y')
{
	$skuDescription = false;
	foreach ($arResult['OFFERS'] as $offer)
	{
		if ($offer['DETAIL_TEXT'] != '' || $offer['PREVIEW_TEXT'] != '')
		{
			$skuDescription = true;
			break;
		}
	}
	$showDescription = $skuDescription || !empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']);
}
else
{
	$showDescription = !empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']);
}

$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';
$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($arResult['PRODUCT']['SUBSCRIBE'] === 'Y' || $haveOffers);
$productType = $arResult['PRODUCT']['TYPE'];

$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCE_CATALOG_BUY');
$arParams['MESS_BTN_ADD_TO_BASKET'] = $arParams['MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCE_CATALOG_ADD');

if ($arResult['MODULES']['catalog'] && $arResult['PRODUCT']['TYPE'] === ProductTable::TYPE_SERVICE)
{
	$arParams['~MESS_NOT_AVAILABLE'] = $arParams['~MESS_NOT_AVAILABLE_SERVICE']
		?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE_SERVICE')
	;
	$arParams['MESS_NOT_AVAILABLE'] = $arParams['MESS_NOT_AVAILABLE_SERVICE']
		?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE_SERVICE')
	;
}
else
{
	$arParams['~MESS_NOT_AVAILABLE'] = $arParams['~MESS_NOT_AVAILABLE']
		?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE')
	;
	$arParams['MESS_NOT_AVAILABLE'] = $arParams['MESS_NOT_AVAILABLE']
		?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE')
	;
}

$arParams['MESS_BTN_COMPARE'] = $arParams['MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCE_CATALOG_COMPARE');
$arParams['MESS_PRICE_RANGES_TITLE'] = $arParams['MESS_PRICE_RANGES_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_PRICE_RANGES_TITLE');
$arParams['MESS_DESCRIPTION_TAB'] = $arParams['MESS_DESCRIPTION_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_DESCRIPTION_TAB');
$arParams['MESS_PROPERTIES_TAB'] = $arParams['MESS_PROPERTIES_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_PROPERTIES_TAB');
$arParams['MESS_COMMENTS_TAB'] = $arParams['MESS_COMMENTS_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_COMMENTS_TAB');
$arParams['MESS_SHOW_MAX_QUANTITY'] = $arParams['MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCE_CATALOG_SHOW_MAX_QUANTITY');
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW');

$positionClassMap = array(
	'left' => 'product-item-label-left',
	'center' => 'product-item-label-center',
	'right' => 'product-item-label-right',
	'bottom' => 'product-item-label-bottom',
	'middle' => 'product-item-label-middle',
	'top' => 'product-item-label-top'
);

$discountPositionClass = 'product-item-label-big';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION']))
{
	foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos)
	{
		$discountPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$labelPositionClass = 'product-item-label-big';
if (!empty($arParams['LABEL_PROP_POSITION']))
{
	foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos)
	{
		$labelPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}
if(!empty($actualItem['DETAIL_PICTURE'])) {
    $detail_name = $actualItem['DETAIL_PICTURE']['ALT'];
    $detail_src = $actualItem['DETAIL_PICTURE']['SRC'];
} else {
    $detail_name = $actualItem['PREVIEW_PICTURE']['ALT'];
    $detail_src = $actualItem['PREVIEW_PICTURE']['SRC'];
}

?>


<div class="main-container">
    <div class="container has-sidebar">
        <div class="row">
            <div class="col-xs-12 col-sm-9 col-sm-push-3" id="product_column">
                <div class="center_column">
                    <div class="product-view-area">
                        <div class="product-big-image col-xs-12 col-sm-5 col-lg-5 col-md-5">
                            <div class="icon-sale-label sale-left"> </div>
                            <div class="large-image imgheighByWidth1_1">
                                <div class="boximg">
                                    <a href="<?=$detail_src?>" class="cloud-zoom" id="zoom1" rel="useWrapper: false, adjustY:0, adjustX:20" style="position: relative; display: block;">
                                        <img class="zoom-img" src="<?=$detail_src?>" alt="<?=$detail_name?>" style="display: block; visibility: visible;">
                                    </a>
                                </div>
                            </div>
                            <? if(!empty($actualItem['PROPERTIES']['MORE_PHOTO'])): ?>
                            <div class="flexslider flexslider-thumb">
                                <div class="flex-viewport" style="overflow: hidden; position: relative;">
                                    <ul class="previews-list slides" style="width: 1000%; transition-duration: 0s; transform: translate3d(-343px, 0px, 0px);">
                                        <? foreach ($actualItem['PROPERTIES']['MORE_PHOTO']['VALUE'] as $idFile): ?>
                                        <? $filesrc = CFile::GetPath($idFile)?>
                                        <li style="width: 100px; float: left; display: block;">
                                            <a href="<?=$filesrc?>" class="cloud-zoom-gallery" rel="useZoom: 'zoom1', smallImage: '<?=$filesrc?>' ">
                                                <img src="<?=$filesrc?>" alt="<?=$actualItem['NAME']?>" draggable="false">
                                            </a>
                                        </li>
                                         <? endforeach; ?>
                                    </ul>
                                </div>
                                <ul class="flex-direction-nav"><li><a class="flex-prev" href="#"></a></li><li><a class="flex-next flex-disabled" href="#" tabindex="-1"></a></li></ul>
                            </div>
                            <? endif; ?>

                            <!-- end: more-images -->
                        </div><!-- .product-big-image -->
                        <div class="col-xs-12 col-sm-7 col-lg-7 col-md-7 product-details-area">
                            <div class="product-name">
                                <h1><?=$actualItem['NAME']?></h1>
                            </div>
                            <div class="price-box">
                                <p class="special-price"> <span class="price-label">Цена</span>
                                    <span class="price"><?=$price['PRINT_RATIO_PRICE']?></span> </p>
                            </div>
                            <div class="ratings">
                                <div class="rating">
                                    <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>                     </div>
                            </div>
                            <div class="short-description">
                                <h2>Описание <?=$actualItem['NAME']?></h2>
                                <?=$actualItem['PREVIEW_TEXT']?>                 </div>
                            <div class="product-cart-option">
                                <ul>
                                    <li><a href="#"><i class="fa fa-heart-o"></i><span>В избранное</span></a></li>
                                    <li><a href="#"><i class="fa fa-envelope"></i><span>Поделится</span></a></li>
                                </ul>
                            </div>
                            <div class="product-cart-buy">
                                <div class="product-item-detail-info-container" style="<?=(!$actualItem['CAN_BUY'] ? 'display: none;' : '')?>"
                                     data-entity="quantity-block">
                                    <div class="product-item-detail-info-container-title"><?=Loc::getMessage('CATALOG_QUANTITY')?></div>
                                    <div class="product-item-amount">

                                        <? echo '123'?>

                                        <? if($actualItem['PRODUCT']['QUANTITY'] > 0): ?>
                                        <div class="counter_wrapp">
                                            <div class="counter_block big_basket" data-item="<?=$actualItem['ID']?>" style="/* display: none; */">
                                                <span class="minus" id="bx_117848907_100406_quant_down">-</span>
                                                <input type="text" class="text" id="bx_117848907_100406_quantity" name="quantity" value="1">
                                                <span class="plus" id="bx_117848907_100406_quant_up">+</span>
                                            </div>
                                        </div>
                                        <div class="button_wrap">
                                            <div class="product-item-detail-info-container in-cart-button">
                                                <button <?=$buyButtonClassName?> class="button cart-button button-green" id="in_basket" title="<?=$arParams['MESS_BTN_BUY']?>">
                                                    <i class="fa fa-shopping-basket"></i>
                                                    <span><?=$arParams['MESS_BTN_BUY']?></span>
                                                </button>
                                            </div>
                                        </div>
                                        <? endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- .product-details-area -->
                    </div><!-- .product-view-area -->
                    <div class="product-overview-tab">
                        <div class="product-tab-inner">
                            <ul id="product-detail-tab" class="nav nav-tabs product-tabs">
                                <li class="active"> <a href="#description" data-toggle="tab">Описание</a> </li>
                                <li> <a href="#specifications" data-toggle="tab">Характеристики</a> </li>
                                <li><a href="#documentation" data-toggle="tab">Документация</a></li>
                            </ul>
                            <div id="productTabContent" class="tab-content">

                                <div class="tab-pane fade in active" id="description">
                                    <div class="std">
                                       <?=$actualItem['DETAIL_TEXT']?>
                                    </div>
                                </div><!-- #description -->

                                <div id="specifications" class="tab-pane fade">
                                    <div class="std">
                                        <div class="review-ratting">
                                            <table class="table">
                                                <tbody><tr>
                                                    <th>Напряжение в открытом состоянии(Iоткр 0.2А)</th>
                                                    <td>5В</td>
                                                </tr>
                                                <tr>
                                                    <th>Максимально допустимый средний ток в открытом состоянии</th>
                                                    <td>0.3А</td>
                                                </tr>
                                                <tr>
                                                    <th>Импульсный ток в открытом состоянии(tи&lt;10мс)</th>
                                                    <td>2А</td>
                                                </tr>
                                                <tr>
                                                    <th>Максимальное напряжение в закрытом состоянии</th>
                                                    <td>40В</td>
                                                </tr>
                                                <tr>
                                                    <th>Постоянный ток в закрытом состоянии</th>
                                                    <td>10мкА</td>
                                                </tr>
                                                <tr>
                                                    <th>Максимальное импульсное неотпирающее напряжение(tимп&lt;2мкс)</th>
                                                    <td>5В</td>
                                                </tr>
                                                <tr>
                                                    <th>Диапазон рабочих температур</th>
                                                    <td>-40…70C</td>
                                                </tr>
                                                <tr>
                                                    <th>Вес</th>
                                                    <td>0.2г</td>
                                                </tr>
                                                </tbody></table>
                                        </div>
                                    </div>
                                </div><!-- #specifications -->

                                <div id="documentation" class="tab-pane fade">
                                    <div class="std">
                                        <p>В разработке.</p>
                                    </div>
                                    <div class="file-loadbx-ico">
                                        <i class="fa fa-file-pdf-o color-red"></i>
                                        <h4>Характеристики, параметры</h4>
                                        <a href="#">Скачать файл</a>
                                    </div>
                                </div><!-- #documentation -->

                            </div><!-- #productTabContent -->
                        </div><!-- .product-tab-inner -->
                    </div><!-- .product-overview-tab -->
                    <br>
                    <div class="related-product-area">
                        <div class="page-header">
                            <h2>Результаты поиска по позиции</h2>
                        </div>
                    </div>
                    <div id="productTabContent" class="product-list-area">
                        <ul class="products-list" id="products-list">
                        </ul>
                    </div><!-- .product-list-area -->
                </div><!-- .center_column -->
            </div><!-- #product_column -->
            <!-- Left colunm -->
        </div>
        <!-- service section -->
        <div class="jtv-service-area" data-file="baner_line_serv.php">
            <!--div class="container"-->
            <div class="row">
                <div class="col col-md-4 col-sm-6 col-xs-12 ">
                    <div class="block-wrapper return">
                        <div class="text-des">
                            <div class="icon-wrapper"><i class="fa fa-truck"></i></div>
                            <div class="service-wrapper">
                                <h3>Доставка в регионы</h3>
                                <p>Доставляем по всей России.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-md-4 col-sm-6 col-xs-12">
                    <div class="block-wrapper support">
                        <div class="text-des">
                            <div class="icon-wrapper"><i class="far fa-question-circle"></i></div>
                            <div class="service-wrapper">
                                <h3>Тех. поддержка</h3>
                                <p>Всегда вам поможем.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-md-4 col-sm-6 col-xs-12">
                    <div class="block-wrapper user">
                        <div class="text-des">
                            <div class="icon-wrapper"><i class="fa fa-tags"></i></div>
                            <div class="service-wrapper">
                                <h3>Скидки и акции</h3>
                                <p>С нами выгоднее.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/div-->
        </div><!-- .jtv-service-area -->
    </div><!-- .container -->
</div>

<?php



?>
<script>
    BX.Sale.ItemComponent.init({
        BasketUrl: '<?=$arParams['BASKET_URL']?>',
        siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
    });
</script>