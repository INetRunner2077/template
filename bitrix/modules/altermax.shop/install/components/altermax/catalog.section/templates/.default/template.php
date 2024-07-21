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

if ($showTopPager)
{
    ?>
    <div data-pagination-num="<?=$navParams['NavNum']?>">
        <!-- pagination-container -->
        <?=$arResult['NAV_STRING']?>
        <!-- pagination-container -->
    </div>
    <?
}

if (!isset($arParams['HIDE_SECTION_DESCRIPTION']) || $arParams['HIDE_SECTION_DESCRIPTION'] !== 'Y')
{
    ?>
    <div class="bx-section-desc bx-<?=$arParams['TEMPLATE_THEME']?>">
        <p class="bx-section-desc-post"><?=$arResult['DESCRIPTION'] ?? ''?></p>
    </div>
    <?
}
?>

<div class="catalog-section bx-<?=$arParams['TEMPLATE_THEME']?>" data-entity="<?=$containerName?>">
    <?
    if (!empty($arResult['ITEMS']) && !empty($arResult['ITEM_ROWS']))
    {
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
        ?>
        <!-- items-container -->
        <?
        foreach ($arResult['ITEM_ROWS'] as $rowData)
        {
          // $rowItems = array_splice($arResult['ITEMS'], 0, $rowData['COUNT']);
            ?>
            <div class="row <?=$rowData['CLASS']?>" data-entity="items-row">
                <?
                switch ($rowData['VARIANT'])
                {
                    case 0:
                        ?>
                        <div class="col-xs-12 product-item-small-card">
                            <div class="row">
                                <div class="col-xs-12 product-item-big-card">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?
                                            $item = reset($rowItems);
                                            $APPLICATION->IncludeComponent(
                                                'bitrix:catalog.item',
                                                '',
                                                array(
                                                    'RESULT' => array(
                                                        'ITEM' => $item,
                                                        'AREA_ID' => $areaIds[$item['ID']],
                                                        'TYPE' => $rowData['TYPE'],
                                                        'BIG_LABEL' => 'N',
                                                        'BIG_DISCOUNT_PERCENT' => 'N',
                                                        'BIG_BUTTONS' => 'N',
                                                        'SCALABLE' => 'N'
                                                    ),
                                                    'PARAMS' => $generalParams + $itemParameters[$item['ID']],
                                                ),
                                                $component,
                                                array('HIDE_ICONS' => 'Y')
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?
                        break;

                    case 1:
                        ?>
                        <div class="col-xs-12 product-item-small-card">
                            <div class="row">
                                <?
                                foreach ($rowItems as $item)
                                {
                                    ?>
                                    <div class="col-xs-6 product-item-big-card">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?
                                                $APPLICATION->IncludeComponent(
                                                    'bitrix:catalog.item',
                                                    '',
                                                    array(
                                                        'RESULT' => array(
                                                            'ITEM' => $item,
                                                            'AREA_ID' => $areaIds[$item['ID']],
                                                            'TYPE' => $rowData['TYPE'],
                                                            'BIG_LABEL' => 'N',
                                                            'BIG_DISCOUNT_PERCENT' => 'N',
                                                            'BIG_BUTTONS' => 'N',
                                                            'SCALABLE' => 'N'
                                                        ),
                                                        'PARAMS' => $generalParams + $itemParameters[$item['ID']],
                                                    ),
                                                    $component,
                                                    array('HIDE_ICONS' => 'Y')
                                                );
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                }
                                ?>
                            </div>
                        </div>
                        <?
                        break;

                    case 2:
                        ?>
                        <div class="col-xs-12 product-item-small-card">
                            <div class="row">
                                <?
                                foreach ($rowItems as $item)
                                {
                                    ?>
                                    <div class="col-sm-4 product-item-big-card">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?
                                                $APPLICATION->IncludeComponent(
                                                    'altermax:catalog.item',
                                                    '',
                                                    array(
                                                        'RESULT' => array(
                                                            'ITEM' => $item,
                                                            'AREA_ID' => $areaIds[$item['ID']],
                                                            'TYPE' => $rowData['TYPE'],
                                                            'BIG_LABEL' => 'N',
                                                            'BIG_DISCOUNT_PERCENT' => 'N',
                                                            'BIG_BUTTONS' => 'Y',
                                                            'SCALABLE' => 'N'
                                                        ),
                                                        'PARAMS' => $generalParams + $itemParameters[$item['ID']],
                                                    ),
                                                    $component,
                                                    array('HIDE_ICONS' => 'Y')
                                                );
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                }
                                ?>
                            </div>
                        </div>
                        <?
                        break;

                    case 3:
                        ?>
                        <div class="col-xs-12 product-item-small-card">
                            <div class="row">
                                <?
                                foreach ($rowItems as $item)
                                {
                                    ?>
                                    <div class="col-xs-6 col-md-3">
                                        <?
                                        $APPLICATION->IncludeComponent(
                                            'bitrix:catalog.item',
                                            '',
                                            array(
                                                'RESULT' => array(
                                                    'ITEM' => $item,
                                                    'AREA_ID' => $areaIds[$item['ID']],
                                                    'TYPE' => $rowData['TYPE'],
                                                    'BIG_LABEL' => 'N',
                                                    'BIG_DISCOUNT_PERCENT' => 'N',
                                                    'BIG_BUTTONS' => 'N',
                                                    'SCALABLE' => 'N'
                                                ),
                                                'PARAMS' => $generalParams + $itemParameters[$item['ID']],
                                            ),
                                            $component,
                                            array('HIDE_ICONS' => 'Y')
                                        );
                                        ?>
                                    </div>
                                    <?
                                }
                                ?>
                            </div>
                        </div>
                        <?
                        break;

                    case 4:
                        $rowItemsCount = count($rowItems);
                        ?>
                        <div class="col-sm-6 product-item-big-card">
                            <div class="row">
                                <div class="col-md-12">
                                    <?
                                    $item = array_shift($rowItems);
                                    $APPLICATION->IncludeComponent(
                                        'bitrix:catalog.item',
                                        '',
                                        array(
                                            'RESULT' => array(
                                                'ITEM' => $item,
                                                'AREA_ID' => $areaIds[$item['ID']],
                                                'TYPE' => $rowData['TYPE'],
                                                'BIG_LABEL' => 'N',
                                                'BIG_DISCOUNT_PERCENT' => 'N',
                                                'BIG_BUTTONS' => 'Y',
                                                'SCALABLE' => 'Y'
                                            ),
                                            'PARAMS' => $generalParams + $itemParameters[$item['ID']],
                                        ),
                                        $component,
                                        array('HIDE_ICONS' => 'Y')
                                    );
                                    unset($item);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 product-item-small-card">
                            <div class="row">
                                <?
                                for ($i = 0; $i < $rowItemsCount - 1; $i++)
                                {
                                    ?>
                                    <div class="col-xs-6">
                                        <?
                                        $APPLICATION->IncludeComponent(
                                            'bitrix:catalog.item',
                                            '',
                                            array(
                                                'RESULT' => array(
                                                    'ITEM' => $rowItems[$i],
                                                    'AREA_ID' => $areaIds[$rowItems[$i]['ID']],
                                                    'TYPE' => $rowData['TYPE'],
                                                    'BIG_LABEL' => 'N',
                                                    'BIG_DISCOUNT_PERCENT' => 'N',
                                                    'BIG_BUTTONS' => 'N',
                                                    'SCALABLE' => 'N'
                                                ),
                                                'PARAMS' => $generalParams + $itemParameters[$rowItems[$i]['ID']],
                                            ),
                                            $component,
                                            array('HIDE_ICONS' => 'Y')
                                        );
                                        ?>
                                    </div>
                                    <?
                                }
                                ?>
                            </div>
                        </div>
                        <?
                        break;

                    case 5:
                        $rowItemsCount = count($rowItems);
                        ?>
                        <div class="col-sm-6 product-item-small-card">
                            <div class="row">
                                <?
                                for ($i = 0; $i < $rowItemsCount - 1; $i++)
                                {
                                    ?>
                                    <div class="col-xs-6">
                                        <?
                                        $APPLICATION->IncludeComponent(
                                            'bitrix:catalog.item',
                                            '',
                                            array(
                                                'RESULT' => array(
                                                    'ITEM' => $rowItems[$i],
                                                    'AREA_ID' => $areaIds[$rowItems[$i]['ID']],
                                                    'TYPE' => $rowData['TYPE'],
                                                    'BIG_LABEL' => 'N',
                                                    'BIG_DISCOUNT_PERCENT' => 'N',
                                                    'BIG_BUTTONS' => 'N',
                                                    'SCALABLE' => 'N'
                                                ),
                                                'PARAMS' => $generalParams + $itemParameters[$rowItems[$i]['ID']],
                                            ),
                                            $component,
                                            array('HIDE_ICONS' => 'Y')
                                        );
                                        ?>
                                    </div>
                                    <?
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-6 product-item-big-card">
                            <div class="row">
                                <div class="col-md-12">
                                    <?
                                    $item = end($rowItems);
                                    $APPLICATION->IncludeComponent(
                                        'bitrix:catalog.item',
                                        '',
                                        array(
                                            'RESULT' => array(
                                                'ITEM' => $item,
                                                'AREA_ID' => $areaIds[$item['ID']],
                                                'TYPE' => $rowData['TYPE'],
                                                'BIG_LABEL' => 'N',
                                                'BIG_DISCOUNT_PERCENT' => 'N',
                                                'BIG_BUTTONS' => 'Y',
                                                'SCALABLE' => 'Y'
                                            ),
                                            'PARAMS' => $generalParams + $itemParameters[$item['ID']],
                                        ),
                                        $component,
                                        array('HIDE_ICONS' => 'Y')
                                    );
                                    unset($item);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?
                        break;

                    case 6:
                        ?>
                        <div class="col-xs-12 product-item-small-card">
                            <div class="row">
                                <?
                                foreach ($rowItems as $item)
                                {
                                    ?>
                                    <div class="col-xs-6 col-sm-4 col-md-2">
                                        <?
                                        $APPLICATION->IncludeComponent(
                                            'bitrix:catalog.item',
                                            '',
                                            array(
                                                'RESULT' => array(
                                                    'ITEM' => $item,
                                                    'AREA_ID' => $areaIds[$item['ID']],
                                                    'TYPE' => $rowData['TYPE'],
                                                    'BIG_LABEL' => 'N',
                                                    'BIG_DISCOUNT_PERCENT' => 'N',
                                                    'BIG_BUTTONS' => 'N',
                                                    'SCALABLE' => 'N'
                                                ),
                                                'PARAMS' => $generalParams + $itemParameters[$item['ID']],
                                            ),
                                            $component,
                                            array('HIDE_ICONS' => 'Y')
                                        );
                                        ?>
                                    </div>
                                    <?
                                }
                                ?>
                            </div>
                        </div>
                        <?
                        break;

                    case 7:
                        $rowItemsCount = count($rowItems);
                        ?>
                        <div class="col-sm-6 product-item-big-card">
                            <div class="row">
                                <div class="col-md-12">
                                    <?
                                    $item = array_shift($rowItems);
                                    $APPLICATION->IncludeComponent(
                                        'bitrix:catalog.item',
                                        '',
                                        array(
                                            'RESULT' => array(
                                                'ITEM' => $item,
                                                'AREA_ID' => $areaIds[$item['ID']],
                                                'TYPE' => $rowData['TYPE'],
                                                'BIG_LABEL' => 'N',
                                                'BIG_DISCOUNT_PERCENT' => 'N',
                                                'BIG_BUTTONS' => 'Y',
                                                'SCALABLE' => 'Y'
                                            ),
                                            'PARAMS' => $generalParams + $itemParameters[$item['ID']],
                                        ),
                                        $component,
                                        array('HIDE_ICONS' => 'Y')
                                    );
                                    unset($item);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 product-item-small-card">
                            <div class="row">
                                <?
                                for ($i = 0; $i < $rowItemsCount - 1; $i++)
                                {
                                    ?>
                                    <div class="col-xs-6 col-md-4">
                                        <?
                                        $APPLICATION->IncludeComponent(
                                            'bitrix:catalog.item',
                                            '',
                                            array(
                                                'RESULT' => array(
                                                    'ITEM' => $rowItems[$i],
                                                    'AREA_ID' => $areaIds[$rowItems[$i]['ID']],
                                                    'TYPE' => $rowData['TYPE'],
                                                    'BIG_LABEL' => 'N',
                                                    'BIG_DISCOUNT_PERCENT' => 'N',
                                                    'BIG_BUTTONS' => 'N',
                                                    'SCALABLE' => 'N'
                                                ),
                                                'PARAMS' => $generalParams + $itemParameters[$rowItems[$i]['ID']],
                                            ),
                                            $component,
                                            array('HIDE_ICONS' => 'Y')
                                        );
                                        ?>
                                    </div>
                                    <?
                                }
                                ?>
                            </div>
                        </div>
                        <?
                        break;

                    case 8:
                        $rowItemsCount = count($rowItems);
                        ?>
                        <div class="col-sm-6 product-item-small-card">
                            <div class="row">
                                <?
                                for ($i = 0; $i < $rowItemsCount - 1; $i++)
                                {
                                    ?>
                                    <div class="col-xs-6 col-md-4">
                                        <?
                                        $APPLICATION->IncludeComponent(
                                            'bitrix:catalog.item',
                                            '',
                                            array(
                                                'RESULT' => array(
                                                    'ITEM' => $rowItems[$i],
                                                    'AREA_ID' => $areaIds[$rowItems[$i]['ID']],
                                                    'TYPE' => $rowData['TYPE'],
                                                    'BIG_LABEL' => 'N',
                                                    'BIG_DISCOUNT_PERCENT' => 'N',
                                                    'BIG_BUTTONS' => 'N',
                                                    'SCALABLE' => 'N'
                                                ),
                                                'PARAMS' => $generalParams + $itemParameters[$rowItems[$i]['ID']],
                                            ),
                                            $component,
                                            array('HIDE_ICONS' => 'Y')
                                        );
                                        ?>
                                    </div>
                                    <?
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-6 product-item-big-card">
                            <div class="row">
                                <div class="col-md-12">
                                    <?
                                    $item = end($rowItems);
                                    $APPLICATION->IncludeComponent(
                                        'bitrix:catalog.item',
                                        '',
                                        array(
                                            'RESULT' => array(
                                                'ITEM' => $item,
                                                'AREA_ID' => $areaIds[$item['ID']],
                                                'TYPE' => $rowData['TYPE'],
                                                'BIG_LABEL' => 'N',
                                                'BIG_DISCOUNT_PERCENT' => 'N',
                                                'BIG_BUTTONS' => 'Y',
                                                'SCALABLE' => 'Y'
                                            ),
                                            'PARAMS' => $generalParams + $itemParameters[$item['ID']],
                                        ),
                                        $component,
                                        array('HIDE_ICONS' => 'Y')
                                    );
                                    unset($item);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?
                        break;

                    case 9:
                        ?>
                        <div class="col-xs-12">
                            <div class="row">
                                <?
                                foreach ($rowItems as $item)
                                {
                                    ?>
                                    <div class="col-xs-12 product-item-line-card">
                                        <?
                                        $APPLICATION->IncludeComponent(
                                            'bitrix:catalog.item',
                                            '',
                                            array(
                                                'RESULT' => array(
                                                    'ITEM' => $item,
                                                    'AREA_ID' => $areaIds[$item['ID']],
                                                    'TYPE' => $rowData['TYPE'],
                                                    'BIG_LABEL' => 'N',
                                                    'BIG_DISCOUNT_PERCENT' => 'N',
                                                    'BIG_BUTTONS' => 'N'
                                                ),
                                                'PARAMS' => $generalParams + $itemParameters[$item['ID']],
                                            ),
                                            $component,
                                            array('HIDE_ICONS' => 'Y')
                                        );
                                        ?>
                                    </div>
                                    <?
                                }
                                ?>

                            </div>
                        </div>
                        <?
                        break;
                }
                ?>
            </div>
            <?
        }
        unset($rowItems);

        unset($itemParameters);
        //unset($areaIds);

        unset($generalParams);
        ?>
        <!-- items-container -->
        <?
    }
    else
    {
        // load css for bigData/deferred load
        $APPLICATION->IncludeComponent(
            'bitrix:catalog.item',
            '',
            array(),
            $component,
            array('HIDE_ICONS' => 'Y')
        );
    }
    ?>
</div>
<?
if ($showLazyLoad)
{
    ?>
    <div class="row bx-<?=$arParams['TEMPLATE_THEME']?>">
        <div class="btn btn-default btn-lg center-block" style="margin: 15px;"
             data-use="show-more-<?=$navParams['NavNum']?>">
            <?=$arParams['MESS_BTN_LAZY_LOAD']?>
        </div>
    </div>
    <?
}
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
                                .product-grid-area > .products-grid > .item{
                                    height: 406px;
                                }
                                @media(max-width:960px) {

                                }
                            </style>
                            <ul class="products-grid">
                                <? foreach ($arResult['ITEMS'] as $item): ?>
                                <li class="item col-lg-4 col-sm-6">
                                    <div class="product-item" id="<?=$areaIds[$item['ID']]?>" >
                                        <div class="item-inner">
                                            <div class="icon-sale-label sale-left">  </div>
                                            <div class="product-thumbnail">
                                                <div class="pr-img-area">
                                                    <a title="<?=$item['NAME']?>" href="<?=$item['DETAIL_PAGE_URL']?>">
                                                        <figure>
                                                            <img class="first-img" src="<?=$item['PREVIEW_PICTURE']['SRC']?>" alt="<?=$item['NAME']?>">
                                                            <img class="hover-img" src="<?=$item['PREVIEW_PICTURE']['SRC']?>" alt="<?=$item['NAME']?>">
                                                        </figure>
                                                    </a>
                                                </div>
                                                <div class="pr-info-area">
                                                </div>
                                            </div>
                                            <div class="item-info">
                                                <div class="info-inner">
                                                    <div class="item-title">
                                                        <a title="<?=$item['NAME']?>" href="<?=$item['DETAIL_PAGE_URL']?>"><?=$item['NAME']?></a> </div>
                                                    <div class="item-content">
                                                        <p> <?=$item['PREVIEW_TEXT'] ?> </p>
                                                        <div class="item-price">
                                                            <div class="price-box"> <span class="regular-price"> <span class="price"> <?=$item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']]['BASE_PRICE']?> Руб </span> </span> </div>
                                                        </div>
                                                        <div class="pro-action">
                                                            <a type="button" class="add-to-cart hashref" href="<?=$item['DETAIL_PAGE_URL']?>"><span> Подробнее</span> </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <? endforeach; ?>
                            </ul>
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
                    "IBLOCK_TYPE" => "xmlcatalog",
                    "IBLOCK_ID" => "11",
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











<?php
$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedTemplate = $signer->sign($templateName, 'catalog.section');
$signedParams = $signer->sign(base64_encode(serialize($arResult['ORIGINAL_PARAMETERS'])), 'catalog.section');
?>
<script>
    BX.message({
        BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
        BASKET_URL: '<?=$arParams['BASKET_URL']?>',
        ADD_TO_BASKET_OK: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
        TITLE_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_TITLE_ERROR')?>',
        TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCS_CATALOG_TITLE_BASKET_PROPS')?>',
        TITLE_SUCCESSFUL: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
        BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_BASKET_UNKNOWN_ERROR')?>',
        BTN_MESSAGE_SEND_PROPS: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_SEND_PROPS')?>',
        BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE')?>',
        BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
        COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_OK')?>',
        COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
        COMPARE_TITLE: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_TITLE')?>',
        PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCS_CATALOG_PRICE_TOTAL_PREFIX')?>',
        RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
        RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
        BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
        BTN_MESSAGE_LAZY_LOAD: '<?=CUtil::JSEscape($arParams['MESS_BTN_LAZY_LOAD'])?>',
        BTN_MESSAGE_LAZY_LOAD_WAITER: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_LAZY_LOAD_WAITER')?>',
        SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>'
    });
    var <?=$obName?> = new JCCatalogSectionComponent({
        siteId: '<?=CUtil::JSEscape($component->getSiteId())?>',
        componentPath: '<?=CUtil::JSEscape($componentPath)?>',
        navParams: <?=CUtil::PhpToJSObject($navParams)?>,
        deferredLoad: false, // enable it for deferred load
        initiallyShowHeader: '<?=!empty($arResult['ITEM_ROWS'])?>',
        bigData: <?=CUtil::PhpToJSObject($arResult['BIG_DATA'])?>,
        lazyLoad: !!'<?=$showLazyLoad?>',
        loadOnScroll: !!'<?=($arParams['LOAD_ON_SCROLL'] === 'Y')?>',
        template: '<?=CUtil::JSEscape($signedTemplate)?>',
        ajaxId: '<?=CUtil::JSEscape($arParams['AJAX_ID'] ?? '')?>',
        parameters: '<?=CUtil::JSEscape($signedParams)?>',
        container: '<?=$containerName?>'
    });
</script>
<!-- component-end -->
