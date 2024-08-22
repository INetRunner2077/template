<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
use Bitrix\Catalog\ProductTable;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 *
 *  _________________________________________________________________________
 * |	Attention!
 * |	The following comments are for system use
 * |	and are required for the component to work correctly in ajax mode:
 * |	<!-- items-container -->
 * |	<!-- pagination-container -->
 * |	<!-- component-end -->
 */

$this->setFrameMode(true);
$this->addExternalCss('/bitrix/css/main/bootstrap.css');

if (!empty($arResult['NAV_RESULT']))
{
    $navParams =  array(
        'NavPageCount' => $arResult['NAV_RESULT']->NavPageCount,
        'NavPageNomer' => $arResult['NAV_RESULT']->NavPageNomer,
        'NavNum' => $arResult['NAV_RESULT']->NavNum
    );
}
else
{
    $navParams = array(
        'NavPageCount' => 1,
        'NavPageNomer' => 1,
        'NavNum' => $this->randString()
    );
}

$showTopPager = false;
$showBottomPager = false;
$showLazyLoad = false;

if ($arParams['PAGE_ELEMENT_COUNT'] > 0 && $navParams['NavPageCount'] > 1)
{
    $showTopPager = $arParams['DISPLAY_TOP_PAGER'];
    $showBottomPager = $arParams['DISPLAY_BOTTOM_PAGER'];
    $showLazyLoad = $arParams['LAZY_LOAD'] === 'Y' && $navParams['NavPageNomer'] != $navParams['NavPageCount'];
}

$templateLibrary = array('popup', 'ajax', 'fx');
$currencyList = '';

if (!empty($arResult['CURRENCIES']))
{
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$templateData = array(
    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList,
    'USE_PAGINATION_CONTAINER' => $showTopPager || $showBottomPager,
);
unset($currencyList, $templateLibrary);

$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = array('CONFIRM' => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

$positionClassMap = array(
    'left' => 'product-item-label-left',
    'center' => 'product-item-label-center',
    'right' => 'product-item-label-right',
    'bottom' => 'product-item-label-bottom',
    'middle' => 'product-item-label-middle',
    'top' => 'product-item-label-top'
);

$discountPositionClass = '';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION']))
{
    foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos)
    {
        $discountPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
    }
}

$labelPositionClass = '';
if (!empty($arParams['LABEL_PROP_POSITION']))
{
    foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos)
    {
        $labelPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
    }
}

$arParams['~MESS_BTN_BUY'] = ($arParams['~MESS_BTN_BUY'] ?? '') ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_BUY');
$arParams['~MESS_BTN_DETAIL'] = ($arParams['~MESS_BTN_DETAIL'] ?? '') ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_DETAIL');
$arParams['~MESS_BTN_COMPARE'] = ($arParams['~MESS_BTN_COMPARE'] ?? '') ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_COMPARE');
$arParams['~MESS_BTN_SUBSCRIBE'] = ($arParams['~MESS_BTN_SUBSCRIBE'] ?? '') ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_SUBSCRIBE');
$arParams['~MESS_BTN_ADD_TO_BASKET'] = ($arParams['~MESS_BTN_ADD_TO_BASKET'] ?? '') ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET');
$arParams['~MESS_NOT_AVAILABLE'] = ($arParams['~MESS_NOT_AVAILABLE'] ?? '') ?: Loc::getMessage('CT_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE');
$arParams['~MESS_NOT_AVAILABLE_SERVICE'] = ($arParams['~MESS_NOT_AVAILABLE_SERVICE'] ?? '') ?: Loc::getMessage('CP_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE_SERVICE');
$arParams['~MESS_SHOW_MAX_QUANTITY'] = ($arParams['~MESS_SHOW_MAX_QUANTITY'] ?? '') ?: Loc::getMessage('CT_BCS_CATALOG_SHOW_MAX_QUANTITY');
$arParams['~MESS_RELATIVE_QUANTITY_MANY'] = ($arParams['~MESS_RELATIVE_QUANTITY_MANY'] ?? '') ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = ($arParams['MESS_RELATIVE_QUANTITY_MANY'] ?? '') ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['~MESS_RELATIVE_QUANTITY_FEW'] = ($arParams['~MESS_RELATIVE_QUANTITY_FEW'] ?? '') ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_FEW');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = ($arParams['MESS_RELATIVE_QUANTITY_FEW'] ?? '') ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_FEW');

$arParams['MESS_BTN_LAZY_LOAD'] = $arParams['MESS_BTN_LAZY_LOAD'] ?: Loc::getMessage('CT_BCS_CATALOG_MESS_BTN_LAZY_LOAD');

$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($navParams['NavNum']));
$containerName = 'container-'.$navParams['NavNum'];

        $areaIds = [];
        $itemParameters = [];

        foreach ($arResult['ITEMS'] as $item)
        {
            $uniqueId = $item['ID'].'_'.md5($this->randString().$component->getAction());
            $areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
            $this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
            $this->AddDeleteAction($uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);

            $itemParameters[$item['ID']] = [
                'SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']],
                'MESS_NOT_AVAILABLE' => ($arResult['MODULES']['catalog'] && $item['PRODUCT']['TYPE'] === ProductTable::TYPE_SERVICE
                    ? $arParams['~MESS_NOT_AVAILABLE_SERVICE']
                    : $arParams['~MESS_NOT_AVAILABLE']
                ),
            ];
        }

$generalParams = [
    'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
    'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
    'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
    'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'],
    'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
    'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
    'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
    'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
    'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
    'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
    'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
    'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
    'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'],
    'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
    'COMPARE_PATH' => $arParams['COMPARE_PATH'],
    'COMPARE_NAME' => $arParams['COMPARE_NAME'],
    'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
    'PRODUCT_BLOCKS_ORDER' => $arParams['PRODUCT_BLOCKS_ORDER'],
    'LABEL_POSITION_CLASS' => $labelPositionClass,
    'DISCOUNT_POSITION_CLASS' => $discountPositionClass,
    'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
    'SLIDER_PROGRESS' => $arParams['SLIDER_PROGRESS'],
    '~BASKET_URL' => $arParams['~BASKET_URL'],
    '~ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
    '~BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
    '~COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
    '~COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
    'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
    'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
    'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],
    'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
    'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
    'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'],
    'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
    'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
];

?>


<?php if(!empty($arResult['ITEMS'])): ?>
<div class="main-container">
    <div class="container has-sidebar">
        <div class="row">
            <div class="col-xs-12 col-sm-9 col-sm-push-3" id="product_column">
                <div class="center_column">
                    <div class="shop-inner">
                        <div class="page-title">
                            <h2><?=$arResult['NAME']?></h2>
                        </div>
                        <div class="product-grid-area">
                            <style>
                                .product-grid-area > .products-grid > .item{
                                    height: 406px;
                                }
                                @media(max-width:960px) {

                                }
                            </style>
                             <?
                if (($_REQUEST['show'] == 'list')): ?>
                    <ul class="products-grid litle-list">


                        <?
                        foreach ($arResult['ITEM_ROWS'] as $rowData)
                        {
                        $rowItems = array_splice(
                            $arResult['ITEMS'],
                            0,
                            $rowData['COUNT']
                        );
                        ?>
                        <div class="row <?= $rowData['CLASS'] ?>"
                             data-entity="items-row">
                            <?
                            foreach ($rowItems as $item) {
                                ?>

                                <?
                                $APPLICATION->IncludeComponent(
                                    'altermax:catalog.item',
                                    'catalog',
                                    array(
                                        'RESULT' => array(
                                            'ITEM'                 => $item,
                                            'AREA_ID'              => $areaIds[$item['ID']],
                                            'TYPE'                 => $rowData['TYPE'],
                                            'BIG_LABEL'            => 'N',
                                            'BIG_DISCOUNT_PERCENT' => 'N',
                                            'BIG_BUTTONS'          => 'N',
                                            'SCALABLE'             => 'N',
                                        ),
                                        'PARAMS' => $generalParams
                                            + $itemParameters[$item['ID']],
                                    ),
                                    $component,
                                    array('HIDE_ICONS' => 'Y')
                                );
                                ?>

                                <?
                            }
                            }

                            ?>


                    </ul>

                <?
                elseif ((($_REQUEST['show'] == 'tile'))
                    or (empty($_REQUEST['show']))
                ): ?>
                    <ul class="products-grid">
                        <div class="products_show">
                            <a href="<?= $APPLICATION->GetCurPageParam(
                                "show=list",
                                array('show')
                            ) ?>">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </a>
                            <a href="<?= $APPLICATION->GetCurPageParam(
                                "show=tile",
                                array('show')
                            ) ?>">
                                <i class="fa fa-table" aria-hidden="true"></i>
                            </a>
                        </div>
                        <?
                        foreach ($arResult['ITEMS'] as $item): ?>

                                <?
                                if(!empty($item['PREVIEW_PICTURE']['ID'])) {

                                    $img = CFile::ResizeImageGet(
                                        $item['PREVIEW_PICTURE']['ID'],
                                        array('width' => 250, 'height' => 250),
                                        BX_RESIZE_IMAGE_EXACT,
                                        true
                                    );
                                    $picture['SRC'] = $img['src'];

                                } elseif ($item['DETAIL_PICTURE']['ID']) {

                                    $img = CFile::ResizeImageGet(
                                        $item['DETAIL_PICTURE']['ID'],
                                        array('width' => 250, 'height' => 250),
                                        BX_RESIZE_IMAGE_EXACT,
                                        true
                                    );
                                    $picture['SRC'] = $img['src'];

                                } else {
                                    $picture['SRC'] = SITE_TEMPLATE_PATH.'/img/altermax_logo.jpg';
                                }
                                ?>

                            <li class="item col-lg-4 col-sm-6">
                                <div class="product-item"
                                     id="<?= $areaIds[$item['ID']] ?>">
                                    <div class="item-inner">
                                        <div class="icon-sale-label sale-left"></div>
                                        <div class="product-thumbnail">
                                            <div class="pr-img-area">
                                                <a title="<?= $item['NAME'] ?>"
                                                   href="<?= $item['DETAIL_PAGE_URL'] ?>">
                                                    <figure>
                                                        <img class="first-img"
                                                             src="<?= $picture['SRC']  ?>"
                                                             alt="<?= $item['NAME'] ?>">
                                                        <img class="hover-img"
                                                             src="<?= $picture['SRC']  ?>"
                                                             alt="<?= $item['NAME'] ?>">
                                                    </figure>
                                                </a>
                                            </div>
                                            <div class="pr-info-area">
                                            </div>
                                        </div>
                                        <div class="item-info">
                                            <div class="info-inner">
                                                <div class="item-title">
                                                    <a title="<?= $item['NAME'] ?>"
                                                       href="<?= $item['DETAIL_PAGE_URL'] ?>"><?= $item['NAME'] ?></a>
                                                </div>
                                                <div class="item-content">
                                                    <p> <?= $item['PREVIEW_TEXT'] ?> </p>
                                                    <div class="item-price">
                                                        <div class="price-box">
                                                            <span class="regular-price"> <span
                                                                        class="price"> <?=$item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']]['PRINT_RATIO_BASE_PRICE']?> </span> </span>
                                                        </div>
                                                    </div>
                                                    <div class="pro-action">
                                                        <a type="button"
                                                           class="add-to-cart hashref"
                                                           href="<?= $item['DETAIL_PAGE_URL'] ?>"><span> Подробнее</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                        <?
                        endforeach; ?>

                    </ul>

                <?
                endif; ?>
                        </div><!-- .product-grid-area -->

                       <? if ($showBottomPager)
                        {
                        ?>
                        <div data-pagination-num="<?=$navParams['NavNum']?>">
                            <!-- pagination-container -->
                            <?=$arResult['NAV_STRING']?>
                            <!-- pagination-container -->
                        </div>
                        <?
                         }
?>
                    </div><!-- .shop-inner -->
                </div><!-- .center_column -->
            </div><!-- #product_column -->
            <!-- Left colunm -->
            <?$APPLICATION->IncludeComponent(
                "altermax:section",
                "sections_list_danil",
                array(
                    "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                    "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                    "DISPLAY_PANEL" => '',
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => 3600,
                    "CACHE_GROUPS" => "N",
                    "COUNT_ELEMENTS" => "N",
                    "SECTION_URL" => '',
                    "SECTIONS_LIST_PREVIEW_DESCRIPTION" => '',
                    "SECTIONS_LIST_PREVIEW_PROPERTY" => '',
                    "SHOW_SUBSECTION" => '',
                    "SHOW_SECTION_LIST_PICTURES" => '',
                    "TOP_DEPTH" => (($arParams["SECTION_TOP_DEPTH"]&&$arParams["SECTION_TOP_DEPTH"]<=2)?$arParams["SECTION_TOP_DEPTH"]:2),
                    "COMPONENT_TEMPLATE" => "sections_list_danil",
                    "SECTION_ID" => '',
                    "SECTION_CODE" => "",
                    "COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE",
                    "ADDITIONAL_COUNT_ELEMENTS_FILTER" => "additionalCountFilter",
                    "HIDE_SECTIONS_WITH_ZERO_COUNT_ELEMENTS" => "N",
                    "SECTION_FIELDS" => array(
                        0 => "",
                        1 => "",
                    ),
                    "SECTION_USER_FIELDS" => array(
                        0 => "",
                        1 => "",
                    ),
                    "FILTER_NAME" => "sectionsFilter",
                    "CACHE_FILTER" => "N",
                    "ADD_SECTIONS_CHAIN" => "Y"
                ),
                $component
            ); ?>


<?php endif; ?>