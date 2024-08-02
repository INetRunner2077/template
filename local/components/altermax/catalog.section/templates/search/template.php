<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Catalog\ProductTable;
use Bitrix\Main\Localization\Loc;

/**
 * @global CMain                 $APPLICATION
 * @var array                    $arParams
 * @var array                    $arResult
 * @var CatalogSectionComponent  $component
 * @var CBitrixComponentTemplate $this
 * @var string                   $templateName
 * @var string                   $componentPath
 *
 *  _________________________________________________________________________
 * |    Attention!
 * |    The following comments are for system use
 * |    and are required for the component to work correctly in ajax mode:
 * |    <!-- items-container -->
 * |    <!-- pagination-container -->
 * |    <!-- component-end -->
 */

$this->setFrameMode(true);
$this->addExternalCss('/bitrix/css/main/bootstrap.css');

if (!empty($arResult['NAV_RESULT'])) {
    $navParams = array(
        'NavPageCount' => $arResult['NAV_RESULT']->NavPageCount,
        'NavPageNomer' => $arResult['NAV_RESULT']->NavPageNomer,
        'NavNum'       => $arResult['NAV_RESULT']->NavNum,
    );
} else {
    $navParams = array(
        'NavPageCount' => 1,
        'NavPageNomer' => 1,
        'NavNum'       => $this->randString(),
    );
}

$showTopPager = false;
$showBottomPager = false;
$showLazyLoad = false;

if ($arParams['PAGE_ELEMENT_COUNT'] > 0 && $navParams['NavPageCount'] > 1) {
    $showTopPager = $arParams['DISPLAY_TOP_PAGER'];
    $showBottomPager = $arParams['DISPLAY_BOTTOM_PAGER'];
    $showLazyLoad = $arParams['LAZY_LOAD'] === 'Y'
        && $navParams['NavPageNomer'] != $navParams['NavPageCount'];
}

$templateLibrary = array('popup', 'ajax', 'fx');
$currencyList = '';

if (!empty($arResult['CURRENCIES'])) {
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject(
        $arResult['CURRENCIES'],
        false,
        true,
        true
    );
}

$templateData = array(
    'TEMPLATE_THEME'           => $arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY'         => $templateLibrary,
    'CURRENCIES'               => $currencyList,
    'USE_PAGINATION_CONTAINER' => $showTopPager || $showBottomPager,
);
unset($currencyList, $templateLibrary);

$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID(
    $arParams['IBLOCK_ID'],
    'ELEMENT_DELETE'
);
$elementDeleteParams = array(
    'CONFIRM' => GetMessage(
        'CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'
    ),
);

$positionClassMap = array(
    'left'   => 'product-item-label-left',
    'center' => 'product-item-label-center',
    'right'  => 'product-item-label-right',
    'bottom' => 'product-item-label-bottom',
    'middle' => 'product-item-label-middle',
    'top'    => 'product-item-label-top',
);

$discountPositionClass = '';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y'
    && !empty($arParams['DISCOUNT_PERCENT_POSITION'])
) {
    foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos) {
        $discountPositionClass .= isset($positionClassMap[$pos]) ? ' '
            .$positionClassMap[$pos] : '';
    }
}

$labelPositionClass = '';
if (!empty($arParams['LABEL_PROP_POSITION'])) {
    foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos) {
        $labelPositionClass .= isset($positionClassMap[$pos]) ? ' '
            .$positionClassMap[$pos] : '';
    }
}

$arParams['~MESS_BTN_BUY'] = ($arParams['~MESS_BTN_BUY'] ?? '')
    ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_BUY');
$arParams['~MESS_BTN_DETAIL'] = ($arParams['~MESS_BTN_DETAIL'] ?? '')
    ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_DETAIL');
$arParams['~MESS_BTN_COMPARE'] = ($arParams['~MESS_BTN_COMPARE'] ?? '')
    ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_COMPARE');
$arParams['~MESS_BTN_SUBSCRIBE'] = ($arParams['~MESS_BTN_SUBSCRIBE'] ?? '')
    ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_SUBSCRIBE');
$arParams['~MESS_BTN_ADD_TO_BASKET'] = ($arParams['~MESS_BTN_ADD_TO_BASKET'] ??
    '') ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET');
$arParams['~MESS_NOT_AVAILABLE'] = ($arParams['~MESS_NOT_AVAILABLE'] ?? '')
    ?: Loc::getMessage('CT_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE');
$arParams['~MESS_NOT_AVAILABLE_SERVICE']
    = ($arParams['~MESS_NOT_AVAILABLE_SERVICE'] ?? '')
    ?: Loc::getMessage(
        'CP_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE_SERVICE'
    );
$arParams['~MESS_SHOW_MAX_QUANTITY'] = ($arParams['~MESS_SHOW_MAX_QUANTITY'] ??
    '') ?: Loc::getMessage('CT_BCS_CATALOG_SHOW_MAX_QUANTITY');
$arParams['~MESS_RELATIVE_QUANTITY_MANY']
    = ($arParams['~MESS_RELATIVE_QUANTITY_MANY'] ?? '')
    ?: Loc::getMessage(
        'CT_BCS_CATALOG_RELATIVE_QUANTITY_MANY'
    );
$arParams['MESS_RELATIVE_QUANTITY_MANY']
    = ($arParams['MESS_RELATIVE_QUANTITY_MANY'] ?? '')
    ?: Loc::getMessage(
        'CT_BCS_CATALOG_RELATIVE_QUANTITY_MANY'
    );
$arParams['~MESS_RELATIVE_QUANTITY_FEW']
    = ($arParams['~MESS_RELATIVE_QUANTITY_FEW'] ?? '')
    ?: Loc::getMessage(
        'CT_BCS_CATALOG_RELATIVE_QUANTITY_FEW'
    );
$arParams['MESS_RELATIVE_QUANTITY_FEW']
    = ($arParams['MESS_RELATIVE_QUANTITY_FEW'] ?? '')
    ?: Loc::getMessage(
        'CT_BCS_CATALOG_RELATIVE_QUANTITY_FEW'
    );

$arParams['MESS_BTN_LAZY_LOAD'] = $arParams['MESS_BTN_LAZY_LOAD']
    ?: Loc::getMessage('CT_BCS_CATALOG_MESS_BTN_LAZY_LOAD');

$obName = 'ob'.preg_replace(
        '/[^a-zA-Z0-9_]/',
        'x',
        $this->GetEditAreaId($navParams['NavNum'])
    );
$containerName = 'container-'.$navParams['NavNum'];

$areaIds = [];
$itemParameters = [];

foreach ($arResult['ITEMS'] as $item) {
    $uniqueId = $item['ID'].'_'.md5(
            $this->randString().$component->getAction()
        );
    $areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
    $this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
    $this->AddDeleteAction(
        $uniqueId,
        $item['DELETE_LINK'],
        $elementDelete,
        $elementDeleteParams
    );

    $itemParameters[$item['ID']] = [
        'SKU_PROPS'          => $arResult['SKU_PROPS'][$item['IBLOCK_ID']],
        'MESS_NOT_AVAILABLE' => ($arResult['MODULES']['catalog']
        && $item['PRODUCT']['TYPE'] === ProductTable::TYPE_SERVICE
            ? $arParams['~MESS_NOT_AVAILABLE_SERVICE']
            : $arParams['~MESS_NOT_AVAILABLE']
        ),
    ];
}

?>
<?php
if (!empty($arResult['ITEMS'])): ?>


    <div class="center_column">
        <div class="shop-inner">
            <div class="page-title">
                <h2><?= $arResult['NAME'] ?></h2>
            </div>
            <div class="toolbar">
                <div class="sorter" style="display:none;">
                    <div class="short-by">
                        <label>Sort By:</label>
                        <select>
                            <option selected="selected">Position</option>
                            <option>Name</option>
                            <option>Price</option>
                            <option>Size</option>
                        </select>
                    </div>
                    <div class="short-by page">
                        <label>Show:</label>
                        <select>
                            <option selected="selected">9</option>
                            <option>12</option>
                            <option>16</option>
                            <option>30</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="product-grid-area">
                <style>
                    .product-grid-area > .products-grid > .item {
                        height: 406px;
                    }

                    @media (max-width: 960px) {

                    }
                </style>
                <?
                if ($_REQUEST['show'] == 'list'): ?>
                    <ul class="products-grid litle-list">
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

                            <li>
                                <div class="product-item litle_list_li"
                                     id="<?= $areaIds[$item['ID']] ?>">
                                    <div class="item-inner litle-inner">
                                        <div class="icon-sale-label sale-left"></div>
                                        <div class="product-thumbnail litle-product">
                                            <div class="pr-img-area litle-img-area">
                                                <a title="<?= $item['NAME'] ?>"
                                                   href="<?= $item['DETAIL_PAGE_URL'] ?>">
                                                    <?
                                                    if (empty($item['PREVIEW_PICTURE']['SRC'])): ?>

                                                        <?
                                                        $item['PREVIEW_PICTURE']['SRC']
                                                            = SITE_TEMPLATE_PATH
                                                            .'/img/altermax_logo.jpg'; ?>

                                                    <?
                                                    endif; ?>
                                                    <?
                                                    if (empty($item['PREVIEW_PICTURE']['FILE_NAME'])) {
                                                        $item['PREVIEW_PICTURE']['FILE_NAME']
                                                            = 'no_photo.png';
                                                    }
                                                    ?>
                                                    <figure>
                                                        <img class="first-img litle"
                                                             src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>"
                                                             alt="<?= $item['PREVIEW_PICTURE']['FILE_NAME'] ?>">
                                                        <img class="hover-img litle"
                                                             src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>"
                                                             alt="<?= $item['PREVIEW_PICTURE']['FILE_NAME'] ?>">
                                                    </figure>
                                                </a>
                                            </div>
                                            <div class="item-info">
                                                <div class="info-inner">
                                                    <div class="item-title">
                                                        <a title="<?= $item['NAME'] ?>"
                                                           href="<?= $item['DETAIL_PAGE_URL'] ?>"><?= $item['NAME'] ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pr-info-area">
                                            </div>
                                        </div>
                                        <div class="litle-description">
                                            <p> <?= $item['PREVIEW_TEXT'] ?> </p>
                                            <div class="item-price">
                                                <div class="price-box"><span
                                                            class="regular-price"> <span
                                                                class="price"> <?= $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']]['BASE_PRICE'] ?> <?= $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']]['CURRENCY'] ?> </span> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="item-content litle-item-content">
                                            <div class="pro-action litle-pro-action">
                                                <a type="button"
                                                   class="add-to-cart hashref"
                                                   href="<?= $item['DETAIL_PAGE_URL'] ?>"><span> Подробнее</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                        <?
                        endforeach; ?>
                    </ul>

                <?
                elseif (($_REQUEST['show'] == 'tile')
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
                                                             src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>"
                                                             alt="<?= $item['NAME'] ?>">
                                                        <img class="hover-img"
                                                             src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>"
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
                                                                        class="price"> <?= $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']]['BASE_PRICE'] ?> <?= $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']]['CURRENCY'] ?> </span> </span>
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
            <?
            if ($showBottomPager) {
                ?>
                <div data-pagination-num="<?= $navParams['NavNum'] ?>">
                    <!-- pagination-container -->
                    <?= $arResult['NAV_STRING'] ?>
                    <!-- pagination-container -->
                </div>
                <?
            }
            ?>
        </div><!-- .shop-inner -->
    </div><!-- .center_column -->
    </div><!-- #product_column -->
    <!-- Left colunm -->

<?php
endif; ?>

<?
$APPLICATION->IncludeComponent(
    "altermax:section",
    "sections_list_danil",
    array(
        "IBLOCK_TYPE"                            => $arParams['IBLOCK_TYPE'],
        "IBLOCK_ID"                              => $arParams['IBLOCK_ID'],
        "DISPLAY_PANEL"                          => '',
        "CACHE_TYPE"                             => "A",
        "CACHE_TIME"                             => 3600,
        "CACHE_GROUPS"                           => "N",
        "COUNT_ELEMENTS"                         => "N",
        "SECTION_URL"                            => '',
        "SECTIONS_LIST_PREVIEW_DESCRIPTION"      => '',
        "SECTIONS_LIST_PREVIEW_PROPERTY"         => '',
        "SHOW_SUBSECTION"                        => '',
        "SHOW_SECTION_LIST_PICTURES"             => '',
        "TOP_DEPTH"                              => (($arParams["SECTION_TOP_DEPTH"]
            && $arParams["SECTION_TOP_DEPTH"] <= 2)
            ? $arParams["SECTION_TOP_DEPTH"] : 2),
        "COMPONENT_TEMPLATE"                     => "sections_list_danil",
        "SECTION_ID"                             => '',
        "SECTION_CODE"                           => "",
        "COUNT_ELEMENTS_FILTER"                  => "CNT_ACTIVE",
        "ADDITIONAL_COUNT_ELEMENTS_FILTER"       => "additionalCountFilter",
        "HIDE_SECTIONS_WITH_ZERO_COUNT_ELEMENTS" => "N",
        "SECTION_FIELDS"                         => array(
            0 => "",
            1 => "",
        ),
        "SECTION_USER_FIELDS"                    => array(
            0 => "",
            1 => "",
        ),
        "FILTER_NAME"                            => "sectionsFilter",
        "CACHE_FILTER"                           => "N",
        "ADD_SECTIONS_CHAIN"                     => "Y",
    ),
    $component
); ?>
