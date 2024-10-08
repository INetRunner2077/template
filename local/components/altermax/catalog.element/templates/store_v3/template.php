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

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$uri = new \Bitrix\Main\Web\Uri($request->getRequestUri());

$uri->addParams(array("action"=>"ADD2BASKET","id"=>"#ID#"));

$arParams['ADD2BASKET_BUY'] = $uri->getUri();

$uri->deleteParams(array("action"=>"ADD2BASKET","id"=>"#ID#"));

$uri->addParams(array("action"=>"BUY","id"=>"#ID#"));

$arParams['BUY_URL'] = $uri->getUri();

$this->setFrameMode(true);

$templateLibrary = array('popup', 'fx');
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


foreach($arResult['OFFERS'] as $key => $offer ) {

    foreach ($offer['PROPERTIES']['MORE_PHOTO']['VALUE'] as $photo) {

        $rsFile = CFile::GetByID($photo);
        $arFile = $rsFile->Fetch();

        $arResult['OFFERS'][$key]['MORE_PHOTO'][] =
            [
                'ID' => $arFile['ID'],
                'SRC' => $arFile['SRC'],
                'WIDTH' => $arFile['WIDTH'],
                'HEIGHT' => $arFile['HEIGHT'],
            ];

        $arResult['JS_OFFERS'][$key]['SLIDER'][] =
            [
                'ID' => $arFile['ID'],
                'SRC' => $arFile['SRC'],
                'WIDTH' => $arFile['WIDTH'],
                'HEIGHT' => $arFile['HEIGHT'],
            ];

        $arResult['OFFERS'][$key]['MORE_PHOTO_COUNT'] = count($arResult['OFFERS'][$key]['MORE_PHOTO']);
        $arResult['JS_OFFERS'][$key]['SLIDER_COUNT'] = count($arResult['OFFERS'][$key]['MORE_PHOTO']);
    }


}


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
	'BLOCK_PRICE_OLD' => $mainId.'_block_price',
	'OLD_PRICE_ID' => $mainId.'_old_price',
	'PRICE_ID' => $mainId.'_price',
	'DISCOUNT_PRICE_ID' => $mainId.'_price_discount',
	'PRICE_TOTAL' => $mainId.'_price_total',
	'SLIDER_CONT_OF_ID' => $mainId.'_slider_cont_',
	'SLIDER_PAGER_OF_ID' => $mainId.'_slider_pager_',
	'QUANTITY_COUNTER_ID' => $mainId.'_counter',
	'QUANTITY_ID' => $mainId.'_quantity',
	'QUANTITY_DOWN_ID' => $mainId.'_quant_down',
	'QUANTITY_UP_ID' => $mainId.'_quant_up',
	'QUANTITY_MEASURE' => $mainId.'_quant_measure',
	'QUANTITY_MEASURE_CONTAINER' => $mainId.'_quant_measure_container',
	'QUANTITY_LIMIT' => $mainId.'_quant_limit',
	'BUY_LINK' => $mainId.'_buy_link',
	'ADD_BASKET_LINK' => $mainId.'_add_basket_link',
	'BASKET_ACTIONS_ID' => $mainId.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $mainId.'_not_avail',
	'COMPARE_LINK' => $mainId.'_compare_link',
	'TREE_ID' => $haveOffers && !empty($arResult['OFFERS_PROP']) ? $mainId.'_skudiv' : null,
	'DISPLAY_PROP_DIV' => $mainId.'_sku_prop',
	'DESCRIPTION_ID' => $mainId.'_description',
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

    if(empty($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'])) {

        if(!empty($arResult['DETAIL_PICTURE']['ID'])) {
            $rsFile = CFile::GetByID($arResult['DETAIL_PICTURE']['ID']);
            $rsFile = CFile::GetByID($photo);
            $arFile = $rsFile->Fetch();
            $arResult['MORE_PHOTO'][] =
                [
                    'ID' => $arFile['ID'],
                    'SRC' => $arFile['SRC'],
                    'WIDTH' => $arFile['WIDTH'],
                    'HEIGHT' => $arFile['HEIGHT'],
                ];
        } elseif($arResult['PREVIEW_PICTURE']['ID']) {

            $rsFile = CFile::GetByID($arResult['PREVIEW_PICTURE']['ID']);
            $rsFile = CFile::GetByID($photo);
            $arFile = $rsFile->Fetch();
            $arResult['MORE_PHOTO'][] =
                [
                    'ID' => $arFile['ID'],
                    'SRC' => $arFile['SRC'],
                    'WIDTH' => $arFile['WIDTH'],
                    'HEIGHT' => $arFile['HEIGHT'],
                ];

        }
    }

    foreach ($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as $photo) {
        $rsFile = CFile::GetByID($photo);
        $arFile = $rsFile->Fetch();
        $arResult['MORE_PHOTO'][] =
            [
                'ID' => $arFile['ID'],
                'SRC' => $arFile['SRC'],
                'WIDTH' => $arFile['WIDTH'],
                'HEIGHT' => $arFile['HEIGHT'],
            ];
    }
	$actualItem = $arResult;
	$showSliderControls = $arResult['MORE_PHOTO_COUNT'] > 1;
}
$percent = '';
if (!empty($actualItem['MORE_PHOTO']))
{
	$firstPhoto = reset($actualItem['MORE_PHOTO']);
	$percent  = ($firstPhoto['HEIGHT']/$firstPhoto['WIDTH'])*100;
	$percent  = ($percent > 160) ? 160 : $percent;
	$percent = 'padding-top: '.$percent.'%;';
	unset($firstPhoto);
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
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-link';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-link';
$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($arResult['PRODUCT']['SUBSCRIBE'] === 'Y' || $haveOffers);

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

$themeClass = isset($arParams['TEMPLATE_THEME']) ? ' bx-'.$arParams['TEMPLATE_THEME'] : '';
?>

    <div class="main-container">
    <div class="container has-sidebar">
    <div class="row">

<div class="col-xs-12 col-sm-9 col-sm-push-3 <?=$themeClass?>" id="<?=$itemIds['ID']?>">
	<?php
		//region COVER
		?>
    <div class="center_column">
		<div>
			<span class="product-detail-slider-close" data-entity="close-popup"></span>

			<div class="product-view-area" data-entity="images-slider-block" id="<?=$itemIds['BIG_SLIDER_ID']?>">

				<div class="product-big-image col-xs-12 col-sm-5 col-lg-5 col-md-5" data-entity="images-container">

                    <div class="large-image imgheighByWidth1_1">
                        <div class="boximg">
                            <a href="<?=$actualItem['MORE_PHOTO'][0]['SRC'] ?>" class="cloud-zoom" id="zoom1" rel="useWrapper: false, adjustY:0, adjustX:20">
                                <img class="zoom-img" src="<?=$actualItem['MORE_PHOTO'][0]['SRC']?>" alt="<?=$actualItem['MORE_PHOTO'][0]['NAME']?>">
                            </a>
                        </div>
                    </div>

                    <div class="flexslider flexslider-thumb" data-entity="image">
                        <ul class="previews-list slides">
					<?php
					if (!empty($actualItem['MORE_PHOTO']))
					{
						foreach ($actualItem['MORE_PHOTO'] as $key => $photo)
						{
							$xResizedImage = \CFile::ResizeImageGet(
								$photo['ID'],
								[
									'width' => 400,
									'height' => 400,
								],
								BX_RESIZE_IMAGE_PROPORTIONAL,
								true
							);

							$x2ResizedImage = \CFile::ResizeImageGet(
								$photo['ID'],
								[
									'width' => 800,
									'height' => 800,
								],
								BX_RESIZE_IMAGE_PROPORTIONAL,
								true
							);

							if (!$xResizedImage || !$x2ResizedImage)
							{
								$xResizedImage = [
									'src' => $photo['SRC'],
								];
								$x2ResizedImage = $xResizedImage;
							}

							$xResizedImage = \Bitrix\Iblock\Component\Tools::getImageSrc([
								'SRC' => $xResizedImage['src']
							]);
							$x2ResizedImage = \Bitrix\Iblock\Component\Tools::getImageSrc([
								'SRC' => $x2ResizedImage['src']
							]);

							$style = "background-image: url('{$xResizedImage}');";
							$style .= "background-image: -webkit-image-set(url('{$xResizedImage}') 1x, url('{$x2ResizedImage}') 2x);";
							$style .= "background-image: image-set(url('{$xResizedImage}') 1x, url('{$x2ResizedImage}') 2x);";
							?>


                            <li>
                                <a href="<?=$xResizedImage?>" class="cloud-zoom-gallery" rel="useZoom: 'zoom1', smallImage: '<?=$x2ResizedImage?>' ">
                                    <img src="<?=$xResizedImage?>" alt="<?=$title?>" style="width:80px">
                                </a>
                            </li>
			<?
						}
						?>
                        </ul>
                    </div>
						<?
					}

				?>

				</div>

<?php
		//endregion

		$showOffersBlock = $haveOffers && !empty($arResult['OFFERS_PROP']);
		$mainBlockProperties = array_intersect_key($arResult['DISPLAY_PROPERTIES'], $arParams['MAIN_BLOCK_PROPERTY_CODE']);
		$showPropsBlock = !empty($mainBlockProperties) || $arResult['SHOW_OFFERS_PROPS'];
		$showBlockWithOffersAndProps = $showOffersBlock || $showPropsBlock;

		?>
		<div class="col-xs-12 col-sm-7 col-lg-7 col-md-7 product-details-area">
			<div class="product-detail-props-container-inner">
				<?php
				//region PROPS
				if ($showPropsBlock)
				{
					?><div class="mb-3"><?php
						if (!empty($mainBlockProperties))
						{
							?><ul class="product-item-detail-properties"><?php
								foreach ($mainBlockProperties as $property)
								{
									?>
									<li class="product-item-detail-properties-item">
										<span class="product-item-detail-properties-name text-muted"><?=$property['NAME']?>:</span>
										<span class="product-item-detail-properties-value"><?=(is_array($property['DISPLAY_VALUE'])
												? implode(' / ', $property['DISPLAY_VALUE'])
												: $property['DISPLAY_VALUE'])?>
										</span>
									</li>
									<?php
								}
							?></ul><?php
						}

						if ($arResult['SHOW_OFFERS_PROPS'])
						{
							?>
							<ul class="product-item-detail-properties" id="<?=$itemIds['DISPLAY_MAIN_PROP_DIV']?>"></ul>
							<?php
						}
					?></div><?php
				}
				//endregion


                if ($arParams['DISPLAY_NAME'] === 'Y') {
                    ?>
                    <div class="product-name">
                        <h1><?= $name ?></h1>
                    </div>
                <?
                }

				//region SKU
				if ($showOffersBlock)
				{
					?>
					<div class="mb-3" id="<?=$itemIds['TREE_ID']?>">
						<?php
						foreach ($arResult['SKU_PROPS'] as $skuProperty)
						{
							if (!isset($arResult['OFFERS_PROP'][$skuProperty['CODE']]))
								continue;

							$propertyId = $skuProperty['ID'];
							$skuProps[] = array(
								'ID' => $propertyId,
								'SHOW_MODE' => $skuProperty['SHOW_MODE'],
								'VALUES' => $skuProperty['VALUES'],
								'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
							);
							?>
							<div data-entity="sku-line-block" class="mb-3">

								<div class="product-detail-scu-container-title"><?=htmlspecialcharsEx($skuProperty['NAME'])?></div>
								<div class="product-detail-scu-container">
									<div class="product-detail-scu-block">
										<div class="product-detail-scu-list">
											<ul class="product-detail-scu-item-list">
												<?php
												foreach ($skuProperty['VALUES'] as &$value)
												{
													$value['NAME'] = htmlspecialcharsbx($value['NAME']);

													if ($skuProperty['SHOW_MODE'] === 'PICT')
													{
														?>
														<li class="product-detail-scu-item-color-container" title="<?=$value['NAME']?>"
															data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
															data-onevalue="<?=$value['ID']?>">
															<div class="product-detail-scu-item-color-block">
																<div class="product-detail-scu-item-color" title="<?=$value['NAME']?>"
																	style="background-image: url('<?=$value['PICT']['SRC']?>');">
																</div>
															</div>
														</li>
														<?php
													}
													else
													{
														?>
														<li class="product-detail-scu-item-text-container" title="<?=$value['NAME']?>"
															data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
															data-onevalue="<?=$value['ID']?>">
															<div class="product-detail-scu-item-text-block">
																<div class="product-detail-scu-item-text"><?=$value['NAME']?></div>
															</div>
														</li>
														<?php
													}
												}
												?>
											</ul>
											<div style="clear: both;"></div>
										</div>
									</div>
								</div>
							</div>
							<?php
						}
						?>
					</div>

					<?php
				}
				//endregion

				?>

				<div class="d-flex w-100 justify-content-between align-items-center mb-2">
					<?php //region PRICE ?>
					<div class="price-box">
                        <p class="special-price">
						<?php
						if ($arParams['SHOW_OLD_PRICE'] === 'Y')
						{
							?>
							<div id="<?=$itemIds['BLOCK_PRICE_OLD']; ?>" class="product-item-price-discount-container special-price" <?=($showDiscount ? '' : 'style="display: none;"')?>>
								<p class="product-item-price-discount price" id="<?=$itemIds['OLD_PRICE_ID']?>">
										<?=($showDiscount ? $price['PRINT_RATIO_BASE_PRICE'] : '')?>
								</p>
								<p class="product-item-price-discount-diff price" id="<?=$itemIds['DISCOUNT_PRICE_ID']?>"><?=($showDiscount ? $price['PRINT_RATIO_DISCOUNT'] : ''); ?></p>
							</div>
							<?php
						}
						?>
						<span id="<?=$itemIds['PRICE_ID']?>" class="product-item-price price"><?=$price['PRINT_RATIO_PRICE']?></span>
                        </p>
					</div>
					<?php //endregion

					//region USE_PRICE_COUNT
					if ($arParams['USE_PRICE_COUNT'])
					{
						$showRanges = !$haveOffers && count($actualItem['ITEM_QUANTITY_RANGES']) > 1;
						$useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';
						?>
						<div class="mb-3" <?=$showRanges ? '' : 'style="display: none;"'?>
							data-entity="price-ranges-block">
							<?php
							if ($arParams['MESS_PRICE_RANGES_TITLE'])
							{
								?>
								<div class="product-item-detail-info-container-title text-center">
									<?= $arParams['MESS_PRICE_RANGES_TITLE'] ?>
									<span data-entity="price-ranges-ratio-header">
									(<?= (Loc::getMessage(
											'CT_BCE_CATALOG_RATIO_PRICE',
											array('#RATIO#' => ($useRatio ? $measureRatio : '1').' '.$actualItem['ITEM_MEASURE']['TITLE'])
										)) ?>)
									</span>
								</div>
								<?php
							}
							?>
							<ul class="product-item-detail-properties" data-entity="price-ranges-body">
								<?php
								if ($showRanges)
								{
									foreach ($actualItem['ITEM_QUANTITY_RANGES'] as $range)
									{
										if ($range['HASH'] !== 'ZERO-INF')
										{
											$itemPrice = false;

											foreach ($arResult['ITEM_PRICES'] as $itemPrice)
											{
												if ($itemPrice['QUANTITY_HASH'] === $range['HASH'])
												{
													break;
												}
											}

											if ($itemPrice)
											{
												?>
												<li class="product-item-detail-properties-item">
													<span class="product-item-detail-properties-name text-muted">
														<?php
														echo Loc::getMessage(
																'CT_BCE_CATALOG_RANGE_FROM',
																array('#FROM#' => $range['SORT_FROM'].' '.$actualItem['ITEM_MEASURE']['TITLE'])
															).' ';

														if (is_infinite($range['SORT_TO']))
														{
															echo Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
														}
														else
														{
															echo Loc::getMessage(
																'CT_BCE_CATALOG_RANGE_TO',
																array('#TO#' => $range['SORT_TO'].' '.$actualItem['ITEM_MEASURE']['TITLE'])
															);
														}
														?>
													</span>
													<span class="product-item-detail-properties-dots"></span>
													<span class="product-item-detail-properties-value"><?=($useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE'])?></span>
												</li>
												<?php
											}
										}
									}
								}
								?>
							</ul>
						</div>
						<?php
						unset($showRanges, $useRatio, $itemPrice, $range);
					}
					//endregion

					//region USE_PRODUCT_QUANTITY
					if ($arParams['USE_PRODUCT_QUANTITY'])
					{
						?>
						<div class="product-item-detail-quantity-container" data-entity="quantity-block" <?= (!$actualItem['CAN_BUY'] ? ' style="display: none;"' : '') ?>>
							<div class="product-item-detail-quantity-field-container counter_block big_basket">


								<div class="product-item-detail-quantity-btn-minus no-select minus" id="<?=$itemIds['QUANTITY_DOWN_ID']?>"></div>
								<div class="product-item-detail-quantity-field-block">
									<input class="product-item-detail-quantity-field" id="<?=$itemIds['QUANTITY_ID']?>" type="text" inputmode="numeric" value="<?=$measureRatio?>">
									<div class="product-item-detail-quantity-field" id="<?=$itemIds['QUANTITY_COUNTER_ID']?>" contentEditable="true" inputmode="numeric" name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>" value=""><?=$measureRatio?></div>
								</div>
								<div class="product-item-detail-quantity-btn-plus no-select plus" id="<?=$itemIds['QUANTITY_UP_ID']?>"></div>


							</div>


							<span class="product-item-detail-quantity-description" id="<?=$itemIds['QUANTITY_MEASURE_CONTAINER']?>">
								<span class="product-item-detail-quantity-description-text" id="<?=$itemIds['QUANTITY_MEASURE']?>"><?=$actualItem['ITEM_MEASURE']['TITLE']?></span>
								<span class="product-item-detail-quantity-description-price" id="<?=$itemIds['PRICE_TOTAL']?>"></span>
							</span>
						<?php
					}
					//endregion
					?>

				<?php //region BUTTONS?>
				<div data-entity="main-button-container" class="main-button-container" class="mb-3">
					<div id="<?=$itemIds['BASKET_ACTIONS_ID']?>" style="display: <?=($actualItem['CAN_BUY'] ? '' : 'none')?>;">
						<?php
						if ($showAddBtn)
						{
							?>
                            <div class="product-item-detail-info-container in-cart-button buy_button" id="buttonMain" style="display:block">
                                <a <?=$buyButtonClassName?> data-item="<?=$actualItem['ID']?>"  data-count="1" class="button cart-button button-green <?=$buyButtonClassName?>" id="<?=$itemIds['ADD_BASKET_LINK']?>" title="<?=$arParams['MESS_BTN_BUY']?>">
                                    <i class="fa fa-shopping-basket"></i>
                                    <span><?=$arParams['MESS_BTN_ADD_TO_BASKET']?></span>
                                </a>
                            </div>
                        <div class="product-item-detail-info-container in-cart-button href_button" id="buttonMain" style="display:none">
                            <a href="<?=$arParams['BASKET_URL']?>" id="to_basket" title="В корзине">
                                <i class="fa fa-shopping-basket"></i>
                                <span> В корзине </span>
                                </a>
                        </div>

							<?php
						}

						if ($showBuyBtn)
						{
							?>
							<div class="mb-3">
								<a class="product-item-detail-buy-button btn btn-md rounded-pill <?=$buyButtonClassName?>"
									id="<?=$itemIds['BUY_LINK']?>"
									href="javascript:void(0);">
									<?=$arParams['MESS_BTN_BUY']?>
								</a>
							</div>
							<?php
						}
						?>
					</div>
				</div>
                </div>
                </div>
				<?php
				if ($showSubscribe)
				{
					?>
					<div class="mb-3">
						<?php
						$APPLICATION->IncludeComponent(
							'bitrix:catalog.product.subscribe',
							'',
							array(
								'CUSTOM_SITE_ID' => $arParams['CUSTOM_SITE_ID'] ?? null,
								'PRODUCT_ID' => $arResult['ID'],
								'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
								'BUTTON_CLASS' => 'btn u-btn-outline-primary product-item-detail-buy-button',
								'DEFAULT_DISPLAY' => !$actualItem['CAN_BUY'],
								'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
							),
							$component,
							array('HIDE_ICONS' => 'Y')
						);
						?>
					</div>
					<?php
				}
				?>
				<div class="mb-3" id="<?=$itemIds['NOT_AVAILABLE_MESS']?>" style="display: <?=(!$actualItem['CAN_BUY'] ? '' : 'none')?>;">
                    <button class="button cart-button by-one-click" type="submit">
                        <i class="fa fa-shopping-cart"></i><span> Заявка </span>
                    </button>
				</div>
				<?php //endregion

				//region PROPS
				foreach ($arParams['PRODUCT_PAY_BLOCK_ORDER'] as $blockName)
				{
					switch ($blockName)
					{
						case 'rating':
							if ($arParams['USE_VOTE_RATING'] === 'Y')
							{
								?>
								<div class="mb-3">
									<?php
									$APPLICATION->IncludeComponent(
										'bitrix:iblock.vote',
										'bootstrap_v4',
										array(
											'CUSTOM_SITE_ID' => $arParams['CUSTOM_SITE_ID'] ?? null,
											'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
											'IBLOCK_ID' => $arParams['IBLOCK_ID'],
											'ELEMENT_ID' => $arResult['ID'],
											'ELEMENT_CODE' => '',
											'MAX_VOTE' => '5',
											'VOTE_NAMES' => array('1', '2', '3', '4', '5'),
											'SET_STATUS_404' => 'N',
											'DISPLAY_AS_RATING' => $arParams['VOTE_DISPLAY_AS_RATING'],
											'CACHE_TYPE' => $arParams['CACHE_TYPE'],
											'CACHE_TIME' => $arParams['CACHE_TIME']
										),
										$component,
										array('HIDE_ICONS' => 'Y')
									);
									?>
								</div>
								<?php
							}

							break;

						case 'price':

							break;

						case 'priceRanges':

							break;

						case 'quantityLimit':
							if ($arParams['SHOW_MAX_QUANTITY'] !== 'N')
							{
								if ($haveOffers)
								{
									?>
									<div class="mb-3" id="<?=$itemIds['QUANTITY_LIMIT']?>" style="display: none;">
										<div class="product-item-detail-info-container-title text-center">
											<?=$arParams['MESS_SHOW_MAX_QUANTITY']?>:
										</div>
										<span class="product-item-quantity" data-entity="quantity-limit-value"></span>
									</div>
									<?php
								}
								else
								{
									if (
										$measureRatio
										&& (float)$actualItem['PRODUCT']['QUANTITY'] > 0
										&& $actualItem['CHECK_QUANTITY']
									)
									{
										?>
										<div class="mb-3 text-center" id="<?=$itemIds['QUANTITY_LIMIT']?>">
											<span class="product-item-detail-info-container-title"><?=$arParams['MESS_SHOW_MAX_QUANTITY']?>:</span>
											<span class="product-item-quantity" data-entity="quantity-limit-value">
											<?php
											if ($arParams['SHOW_MAX_QUANTITY'] === 'M')
											{
												if ((float)$actualItem['PRODUCT']['QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR'])
												{
													echo $arParams['MESS_RELATIVE_QUANTITY_MANY'];
												}
												else
												{
													echo $arParams['MESS_RELATIVE_QUANTITY_FEW'];
												}
											}
											else
											{
												echo $actualItem['PRODUCT']['QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE'];
											}
											?>
										</span>
										</div>
										<?php
									}
								}
							}

							break;

						case 'quantity':

							break;

						case 'buttons':

							break;
					}
				}
				//endregion

				?>

                <div class="property-area">

                    <?
                    foreach ($arResult['PROPERTIES'] as $key => $property): ?>

                        <?if ($key == "MANUFACTURER" and !empty($property['VALUE'])): ?>
                            <div class="property_line">
                                <div class="name_prop"> <?=$property['NAME']?>: </div>
                                <a href="<?=$APPLICATION->GetCurDir()?>?q=<?=$property['VALUE']?>&s=Поиск"> <?= $property['VALUE'] ?> </a>
                            </div>
                        <?  elseif($key == "VENDOR" and !empty($property['VALUE'])): ?>

                            <div class="property_line">
                                <div class="name_prop"> <?=$property['NAME']?>: </div>
                                <a href="<?=$APPLICATION->GetCurDir()?>?q=<?=$property['VALUE']?>&s=Поиск"> <?= $property['VALUE'] ?> </a>
                            </div>
                        <? else: ?>
                            <? continue; ?>
                        <? endif; ?>
                    <? endforeach; ?>

                </div>


			</div>

		</div>


            </div>

        </div>

		<div class="product-detail-info-container col-12">
			<?php

			//endregion

			//region TABS
			?>
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
                                <?=$arResult['DETAIL_TEXT']?>
                            </div>
                        </div><!-- #description -->

                        <div id="specifications" class="tab-pane fade">
                            <div class="std">
                                <div class="review-ratting">
                                    <table class="table">
                                        <tbody>
                                        <? foreach ($arResult['PROPERTIES'] as $prop): ?>
                                            <? if(($prop['PROPERTY_TYPE'] == 'S' or $prop['PROPERTY_TYPE'] == 'N') and ($prop['ACTIVE'] == 'Y') and (!empty($prop['VALUE'])) and (!is_array($prop['VALUE']))): ?>
                                                <tr>
                                                    <th><?=$prop['NAME']?></th>
                                                    <td><?=$prop['VALUE']?></td>
                                                </tr>
                                            <? endif; ?>
                                        <? endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- #specifications -->

                        <div id="documentation" class="tab-pane fade">
                            <? foreach ($arResult['PROPERTIES'] as $prop): ?>
                                <? if(($prop['PROPERTY_TYPE'] == 'F') and (!empty($prop['VALUE'])) and ($prop['ACTIVE'] == 'Y')): ?>
                                    <?
                                    $rsFile = \CFile::GetList(array(), array('@ID'=>implode(',',$prop['VALUE'])));
                                    while ($arFile = $rsFile->Fetch()) {
                                        if($arFile['CONTENT_TYPE'] == 'application/pdf') {
                                            $arrFiles[$arFile["ID"]]
                                                = $arFile;
                                            $arrFiles[$arFile["ID"]]['SRC'] = '/upload/' . $arFile['SUBDIR'] . '/' . $arFile['FILE_NAME'];
                                        }
                                    }
                                    ?>
                                    <? foreach ($arrFiles as $file): ?>
                                        <div class="file-loadbx-ico">
                                            <i class="fa fa-file-pdf-o color-red"></i>
                                            <h4><?=$file['ORIGINAL_NAME']?></h4>
                                            <a href="<?=$file['SRC']?>" download>Скачать файл</a>
                                        </div>
                                    <? endforeach; ?>
                                    <? unset($arrImages); ?>
                                <? endif; ?>
                            <? endforeach; ?>
                        </div><!-- #documentation -->

                      <?  if ($arParams['USE_COMMENTS'] === 'Y')
                        {
                        ?>
                        <div class="product-item-detail-tab-content" data-entity="tab-container" data-value="comments" style="display: none;">
                            <?php
                            $componentCommentsParams = array(
                                'ELEMENT_ID' => $arResult['ID'],
                                'ELEMENT_CODE' => '',
                                'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                                'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
                                'URL_TO_COMMENT' => '',
                                'WIDTH' => '',
                                'COMMENTS_COUNT' => '5',
                                'BLOG_USE' => $arParams['BLOG_USE'],
                                'FB_USE' => $arParams['FB_USE'],
                                'FB_APP_ID' => $arParams['FB_APP_ID'],
                                'VK_USE' => $arParams['VK_USE'],
                                'VK_API_ID' => $arParams['VK_API_ID'],
                                'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                'CACHE_TIME' => $arParams['CACHE_TIME'],
                                'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                                'BLOG_TITLE' => '',
                                'BLOG_URL' => $arParams['BLOG_URL'],
                                'PATH_TO_SMILE' => '',
                                'EMAIL_NOTIFY' => $arParams['BLOG_EMAIL_NOTIFY'],
                                'AJAX_POST' => 'Y',
                                'SHOW_SPAM' => 'Y',
                                'SHOW_RATING' => 'N',
                                'FB_TITLE' => '',
                                'FB_USER_ADMIN_ID' => '',
                                'FB_COLORSCHEME' => 'light',
                                'FB_ORDER_BY' => 'reverse_time',
                                'VK_TITLE' => '',
                                'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME']
                            );
                            if(isset($arParams["USER_CONSENT"]))
                                $componentCommentsParams["USER_CONSENT"] = $arParams["USER_CONSENT"];
                            if(isset($arParams["USER_CONSENT_ID"]))
                                $componentCommentsParams["USER_CONSENT_ID"] = $arParams["USER_CONSENT_ID"];
                            if(isset($arParams["USER_CONSENT_IS_CHECKED"]))
                                $componentCommentsParams["USER_CONSENT_IS_CHECKED"] = $arParams["USER_CONSENT_IS_CHECKED"];
                            if(isset($arParams["USER_CONSENT_IS_LOADED"]))
                                $componentCommentsParams["USER_CONSENT_IS_LOADED"] = $arParams["USER_CONSENT_IS_LOADED"];
                            $APPLICATION->IncludeComponent(
                                'bitrix:catalog.comments',
                                '',
                                $componentCommentsParams,
                                $component,
                                array('HIDE_ICONS' => 'Y')
                            );
                            ?>
                        </div>
                        <?php
					}
					?>


                    </div><!-- #productTabContent -->
                </div><!-- .product-tab-inner -->
            </div><!-- .product-overview-tab -->




            <?
			//endregion

			//region bitrix:catalog.set.constructor
			if ($haveOffers)
			{
				if ($arResult['OFFER_GROUP'])
				{
					foreach ($arResult['OFFER_GROUP_VALUES'] as $offerId)
					{
						?>
						<span id="<?=$itemIds['OFFER_GROUP'].$offerId?>" style="display: none;">
							<?php
							$APPLICATION->IncludeComponent(
								'bitrix:catalog.set.constructor',
								'bootstrap_v4',
								array(
									'CUSTOM_SITE_ID' => $arParams['CUSTOM_SITE_ID'] ?? null,
									'IBLOCK_ID' => $arResult['OFFERS_IBLOCK'],
									'ELEMENT_ID' => $offerId,
									'PRICE_CODE' => $arParams['PRICE_CODE'],
									'BASKET_URL' => $arParams['BASKET_URL'],
									'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
									'CACHE_TYPE' => $arParams['CACHE_TYPE'],
									'CACHE_TIME' => $arParams['CACHE_TIME'],
									'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
									'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
									'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
									'CURRENCY_ID' => $arParams['CURRENCY_ID'],
									'DETAIL_URL' => $arParams['~DETAIL_URL']
								),
								$component,
								array('HIDE_ICONS' => 'Y')
							);
							?>
						</span>
						<?php
					}
				}
			}
			else
			{
				if ($arResult['MODULES']['catalog'] && $arResult['OFFER_GROUP'])
				{
					?>
					<div class="row">
						<div class="col">
							<?php $APPLICATION->IncludeComponent(
								'bitrix:catalog.set.constructor',
								'bootstrap_v4',
								array(
									'CUSTOM_SITE_ID' => $arParams['CUSTOM_SITE_ID'] ?? null,
									'IBLOCK_ID' => $arParams['IBLOCK_ID'],
									'ELEMENT_ID' => $arResult['ID'],
									'PRICE_CODE' => $arParams['PRICE_CODE'],
									'BASKET_URL' => $arParams['BASKET_URL'],
									'CACHE_TYPE' => $arParams['CACHE_TYPE'],
									'CACHE_TIME' => $arParams['CACHE_TIME'],
									'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
									'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
									'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
									'CURRENCY_ID' => $arParams['CURRENCY_ID']
								),
								$component,
								array('HIDE_ICONS' => 'Y')
							);
							?>
						</div>
					</div>
					<?php
				}
			}
			//endregion

			//region BRAND_USE
			if ($arParams['BRAND_USE'] === 'Y')
			{
				$APPLICATION->IncludeComponent(
					'bitrix:catalog.brandblock',
					'bootstrap_v4',
					array(
						'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
						'IBLOCK_ID' => $arParams['IBLOCK_ID'],
						'ELEMENT_ID' => $arResult['ID'],
						'ELEMENT_CODE' => '',
						'PROP_CODE' => $arParams['BRAND_PROP_CODE'],
						'CACHE_TYPE' => $arParams['CACHE_TYPE'],
						'CACHE_TIME' => $arParams['CACHE_TIME'],
						'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
						'WIDTH' => '',
						'HEIGHT' => ''
					),
					$component,
					array('HIDE_ICONS' => 'Y')
				);
			}
			//endregion

			//region sale.prediction.product.detail
			if ($arResult['CATALOG'] && $actualItem['CAN_BUY'] && \Bitrix\Main\ModuleManager::isModuleInstalled('sale'))
			{
				$APPLICATION->IncludeComponent(
					'bitrix:sale.prediction.product.detail',
					'',
					array(
						'CUSTOM_SITE_ID' => $arParams['CUSTOM_SITE_ID'] ?? null,
						'BUTTON_ID' => $showBuyBtn ? $itemIds['BUY_LINK'] : $itemIds['ADD_BASKET_LINK'],
						'POTENTIAL_PRODUCT_TO_BUY' => array(
							'ID' => $arResult['ID'] ?? null,
							'MODULE' => $arResult['MODULE'] ?? 'catalog',
							'PRODUCT_PROVIDER_CLASS' => $arResult['~PRODUCT_PROVIDER_CLASS'] ?? \Bitrix\Catalog\Product\Basket::getDefaultProviderName(),
							'QUANTITY' => $arResult['QUANTITY'] ?? null,
							'IBLOCK_ID' => $arResult['IBLOCK_ID'] ?? null,

							'PRIMARY_OFFER_ID' => $arResult['OFFERS'][0]['ID'] ?? null,
							'SECTION' => array(
								'ID' => $arResult['SECTION']['ID'] ?? null,
								'IBLOCK_ID' => $arResult['SECTION']['IBLOCK_ID'] ?? null,
								'LEFT_MARGIN' => $arResult['SECTION']['LEFT_MARGIN'] ?? null,
								'RIGHT_MARGIN' => $arResult['SECTION']['RIGHT_MARGIN'] ?? null,
							),
						)
					),
					$component,
					array('HIDE_ICONS' => 'Y')
				);
			}
			//endregion


			//endregion

			//region USE_GIFTS_MAIN_PR_SECTION_LIST > sale.gift.main.products
			if ($arResult['CATALOG'] && $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled('sale'))
			{
				?>
				<div data-entity="parent-container">
					<?php
					if (!isset($arParams['GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE']) || $arParams['GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE'] !== 'Y')
					{
						?>
						<div class="catalog-block-header" data-entity="header" data-showed="false" style="display: none; opacity: 0;">
							<?=($arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_GIFTS_MAIN_BLOCK_TITLE_DEFAULT'))?>
						</div>
						<?php
					}

					$APPLICATION->IncludeComponent('bitrix:sale.gift.main.products', 'bootstrap_v4',
						array(
							'CUSTOM_SITE_ID' => $arParams['CUSTOM_SITE_ID'] ?? null,
							'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
							'LINE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
							'HIDE_BLOCK_TITLE' => 'Y',
							'BLOCK_TITLE' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],

							'OFFERS_FIELD_CODE' => $arParams['OFFERS_FIELD_CODE'],
							'OFFERS_PROPERTY_CODE' => $arParams['OFFERS_PROPERTY_CODE'],

							'AJAX_MODE' => $arParams['AJAX_MODE'],
							'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
							'IBLOCK_ID' => $arParams['IBLOCK_ID'],

							'ELEMENT_SORT_FIELD' => 'ID',
							'ELEMENT_SORT_ORDER' => 'DESC',
							'FILTER_NAME' => 'searchFilter',
							'SECTION_URL' => $arParams['SECTION_URL'],
							'DETAIL_URL' => $arParams['DETAIL_URL'],
							'BASKET_URL' => $arParams['BASKET_URL'],
							'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
							'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
							'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],

							'CACHE_TYPE' => $arParams['CACHE_TYPE'],
							'CACHE_TIME' => $arParams['CACHE_TIME'],

							'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
							'SET_TITLE' => $arParams['SET_TITLE'],
							'PROPERTY_CODE' => $arParams['PROPERTY_CODE'],
							'PRICE_CODE' => $arParams['PRICE_CODE'],
							'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
							'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],

							'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
							'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
							'CURRENCY_ID' => $arParams['CURRENCY_ID'],
							'HIDE_NOT_AVAILABLE' => 'Y',
							'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
							'TEMPLATE_THEME' => ($arParams['TEMPLATE_THEME'] ?? ''),
							'PRODUCT_BLOCKS_ORDER' => $arParams['GIFTS_PRODUCT_BLOCKS_ORDER'],

							'SHOW_SLIDER' => $arParams['GIFTS_SHOW_SLIDER'],
							'SLIDER_INTERVAL' => $arParams['GIFTS_SLIDER_INTERVAL'] ?? '',
							'SLIDER_PROGRESS' => $arParams['GIFTS_SLIDER_PROGRESS'] ?? '',

							'ADD_PICT_PROP' => ($arParams['ADD_PICT_PROP'] ?? ''),
							'LABEL_PROP' => ($arParams['LABEL_PROP'] ?? ''),
							'LABEL_PROP_MOBILE' => ($arParams['LABEL_PROP_MOBILE'] ?? ''),
							'LABEL_PROP_POSITION' => ($arParams['LABEL_PROP_POSITION'] ?? ''),
							'OFFER_ADD_PICT_PROP' => ($arParams['OFFER_ADD_PICT_PROP'] ?? ''),
							'OFFER_TREE_PROPS' => ($arParams['OFFER_TREE_PROPS'] ?? ''),
							'SHOW_DISCOUNT_PERCENT' => ($arParams['SHOW_DISCOUNT_PERCENT'] ?? ''),
							'DISCOUNT_PERCENT_POSITION' => ($arParams['DISCOUNT_PERCENT_POSITION'] ?? ''),
							'SHOW_OLD_PRICE' => ($arParams['SHOW_OLD_PRICE'] ?? ''),
							'MESS_BTN_BUY' => ($arParams['~MESS_BTN_BUY'] ?? ''),
							'MESS_BTN_ADD_TO_BASKET' => ($arParams['~MESS_BTN_ADD_TO_BASKET'] ?? ''),
							'MESS_BTN_DETAIL' => ($arParams['~MESS_BTN_DETAIL'] ?? ''),
							'MESS_NOT_AVAILABLE' => ($arParams['~MESS_NOT_AVAILABLE'] ?? ''),
							'ADD_TO_BASKET_ACTION' => ($arParams['ADD_TO_BASKET_ACTION'] ?? ''),
							'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] ?? ''),
							'DISPLAY_COMPARE' => ($arParams['DISPLAY_COMPARE'] ?? ''),
							'COMPARE_PATH' => ($arParams['COMPARE_PATH'] ?? ''),
						)
						+ array(
							'OFFER_ID' => empty($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'])
								? $arResult['ID']
								: $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'],
							'SECTION_ID' => $arResult['SECTION']['ID'],
							'ELEMENT_ID' => $arResult['ID'],

							'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
							'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
							'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY']
						),
						$component,
						array('HIDE_ICONS' => 'Y')
					);
					?>
				</div>
				<?php
			}
			//endregion
			?>
		</div>

		<!--Small Card-->
		<div class="p-2 product-item-detail-short-card-fixed d-none d-md-block" id="<?=$itemIds['SMALL_CARD_PANEL_ID']?>" style="display: none !important;">
			<div class="product-item-detail-short-card-content-container">
				<div class="product-item-detail-short-card-image">
					<img src="" style="height: 65px;" data-entity="panel-picture">
				</div>
				<div class="product-item-detail-short-title-container" data-entity="panel-title">
					<div class="product-item-detail-short-title-text"><?=$name?></div>
					<?php
					if ($haveOffers)
					{
						?>
						<div>
							<div class="product-item-selected-scu-container" data-entity="panel-sku-container">
								<?php
								$i = 0;

								foreach ($arResult['SKU_PROPS'] as $skuProperty)
								{
									if (!isset($arResult['OFFERS_PROP'][$skuProperty['CODE']]))
									{
										continue;
									}

									$propertyId = $skuProperty['ID'];

									foreach ($skuProperty['VALUES'] as $value)
									{
										$value['NAME'] = htmlspecialcharsbx($value['NAME']);
										if ($skuProperty['SHOW_MODE'] === 'PICT')
										{
											?>
											<div class="product-item-selected-scu product-item-selected-scu-color selected"
												title="<?=$value['NAME']?>"
												style="background-image: url('<?=$value['PICT']['SRC']?>'); display: none;"
												data-sku-line="<?=$i?>"
												data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
												data-onevalue="<?=$value['ID']?>">
											</div>
											<?php
										}
										else
										{
											?>
											<div class="product-item-selected-scu product-item-selected-scu-text selected"
												title="<?=$value['NAME']?>"
												style="display: none;"
												data-sku-line="<?=$i?>"
												data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
												data-onevalue="<?=$value['ID']?>">
												<?=$value['NAME']?>
											</div>
											<?php
										}
									}

									$i++;
								}
								?>
							</div>
						</div>
						<?php
					}
					?>

				</div>
				<div class="product-item-detail-short-card-price">
					<?php
					if ($arParams['SHOW_OLD_PRICE'] === 'Y')
					{
						?>
						<div class="product-item-detail-price-old" style="display: <?=($showDiscount ? '' : 'none')?>;" data-entity="panel-old-price">
							<?=($showDiscount ? $price['PRINT_RATIO_BASE_PRICE'] : '')?>
						</div>
						<?php
					}
					?>
					<div class="product-item-detail-price-current" data-entity="panel-price"><?=$price['PRINT_RATIO_PRICE']?></div>
				</div>
				<?php
				if ($showAddBtn)
				{
					?>
					<div class="product-item-detail-short-card-btn"
						style="display: <?=($actualItem['CAN_BUY'] ? '' : 'none')?>;"
						data-entity="panel-add-button">
						<a class="btn <?=$showButtonClassName?> product-item-detail-buy-button"
							id="<?=$itemIds['ADD_BASKET_LINK']?>"
							href="javascript:void(0);">
							<?=$arParams['MESS_BTN_ADD_TO_BASKET']?>
						</a>
					</div>
					<?php
				}

				if ($showBuyBtn)
				{
					?>
					<div class="product-item-detail-short-card-btn"
						style="display: <?=($actualItem['CAN_BUY'] ? '' : 'none')?>;"
						data-entity="panel-buy-button">
						<a class="btn <?=$buyButtonClassName?> product-item-detail-buy-button"
							id="<?=$itemIds['BUY_LINK']?>"
							href="javascript:void(0);">
							<?=$arParams['MESS_BTN_BUY']?>
						</a>
					</div>
					<?php
				}
				?>
				<div class="product-item-detail-short-card-btn"
					style="display: <?=(!$actualItem['CAN_BUY'] ? '' : 'none')?>;"
					data-entity="panel-not-available-button">
					<a class="btn btn-link product-item-detail-buy-button" href="javascript:void(0)"
						rel="nofollow">
						<?=$arParams['MESS_NOT_AVAILABLE']?>
					</a>
				</div>
			</div>
		</div>





		<!--Top tabs-->
		<div class="pt-2 pb-0 product-item-detail-tabs-container-fixed d-none d-md-block" id="<?=$itemIds['TABS_PANEL_ID']?>" style="display: none !important; ">
			<ul class="product-item-detail-tabs-list">
				<?php
				if ($showDescription)
				{
					?>
					<li class="product-item-detail-tab active" data-entity="tab" data-value="description">
						<a href="javascript:void(0);" class="product-item-detail-tab-link">
							<span><?=$arParams['MESS_DESCRIPTION_TAB']?></span>
						</a>
					</li>
					<?php
				}

				if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS'])
				{
					?>
					<li class="product-item-detail-tab" data-entity="tab" data-value="properties">
						<a href="javascript:void(0);" class="product-item-detail-tab-link">
							<span><?=$arParams['MESS_PROPERTIES_TAB']?></span>
						</a>
					</li>
					<?php
				}

				if ($arParams['USE_COMMENTS'] === 'Y')
				{
					?>
					<li class="product-item-detail-tab" data-entity="tab" data-value="comments">
						<a href="javascript:void(0);" class="product-item-detail-tab-link">
							<span><?=$arParams['MESS_COMMENTS_TAB']?></span>
						</a>
					</li>
					<?php
				}
				?>
			</ul>
		</div>
<?php
		if ($haveOffers)
		{
			$offerIds = array();
			$offerCodes = array();

			$useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';

			foreach ($arResult['JS_OFFERS'] as $ind => &$jsOffer)
			{
				$offerIds[] = (int)$jsOffer['ID'];
				$offerCodes[] = $jsOffer['CODE'];

				$fullOffer = $arResult['OFFERS'][$ind];
				$measureName = $fullOffer['ITEM_MEASURE']['TITLE'];

				$strAllProps = '';
				$strMainProps = '';
				$strPriceRangesRatio = '';
				$strPriceRanges = '';

				if ($arResult['SHOW_OFFERS_PROPS'])
				{
					if (!empty($jsOffer['DISPLAY_PROPERTIES']))
					{
						foreach ($jsOffer['DISPLAY_PROPERTIES'] as $property)
						{
							$current = '<li class="product-item-detail-properties-item">
						<span class="product-item-detail-properties-name">'.$property['NAME'].'</span>
						<span class="product-item-detail-properties-dots"></span>
						<span class="product-item-detail-properties-value">'.(
							is_array($property['VALUE'])
								? implode(' / ', $property['VALUE'])
								: $property['VALUE']
							).'</span></li>';
							$strAllProps .= $current;

                          /*  $current = '<tr>
                                                    <th>'.$property['NAME'].'</th>
                                                    <td>'.(
                                is_array($property['VALUE'])
                                    ? implode(' / ', $property['VALUE'])
                                    : $property['VALUE']
                                ).'</td>
                                                </tr>';
                            $strAllProps .= $current; */

							if (isset($arParams['MAIN_BLOCK_OFFERS_PROPERTY_CODE'][$property['CODE']]))
							{
								$strMainProps .= $current;
							}
						}

						unset($current);
					}
				}

				if ($arParams['USE_PRICE_COUNT'] && count($jsOffer['ITEM_QUANTITY_RANGES']) > 1)
				{
					$strPriceRangesRatio = '('.Loc::getMessage(
							'CT_BCE_CATALOG_RATIO_PRICE',
							array('#RATIO#' => ($useRatio
									? $fullOffer['ITEM_MEASURE_RATIOS'][$fullOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']
									: '1'
								).' '.$measureName)
						).')';

					foreach ($jsOffer['ITEM_QUANTITY_RANGES'] as $range)
					{
						if ($range['HASH'] !== 'ZERO-INF')
						{
							$itemPrice = false;

							foreach ($jsOffer['ITEM_PRICES'] as $itemPrice)
							{
								if ($itemPrice['QUANTITY_HASH'] === $range['HASH'])
								{
									break;
								}
							}

							if ($itemPrice)
							{
								$strPriceRanges .= '<dt>'.Loc::getMessage(
										'CT_BCE_CATALOG_RANGE_FROM',
										array('#FROM#' => $range['SORT_FROM'].' '.$measureName)
									).' ';

								if (is_infinite($range['SORT_TO']))
								{
									$strPriceRanges .= Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
								}
								else
								{
									$strPriceRanges .= Loc::getMessage(
										'CT_BCE_CATALOG_RANGE_TO',
										array('#TO#' => $range['SORT_TO'].' '.$measureName)
									);
								}

								$strPriceRanges .= '</dt><dd>'.($useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE']).'</dd>';
							}
						}
					}

					unset($range, $itemPrice);
				}

				$jsOffer['DISPLAY_PROPERTIES'] = $strAllProps;
				$jsOffer['DISPLAY_PROPERTIES_MAIN_BLOCK'] = $strMainProps;
				$jsOffer['PRICE_RANGES_RATIO_HTML'] = $strPriceRangesRatio;
				$jsOffer['PRICE_RANGES_HTML'] = $strPriceRanges;

				$jsOffer['RESIZED_SLIDER'] = [
					'X' => [],
					'X2' => [],
				];
				foreach ($jsOffer['SLIDER'] as $morePhoto)
				{
					$xResizedImage = \CFile::ResizeImageGet(
						$morePhoto['ID'],
						[
							'width' => 400,
							'height' => 400,
						],
						BX_RESIZE_IMAGE_PROPORTIONAL,
						true
					);

					$x2ResizedImage = \CFile::ResizeImageGet(
						$morePhoto['ID'],
						[
							'width' => 800,
							'height' => 800,
						],
						BX_RESIZE_IMAGE_PROPORTIONAL,
						true
					);

					if (!$xResizedImage || !$x2ResizedImage)
					{
						$xResizedImage = [
							'src' => $morePhoto['SRC'],
							'width' => $morePhoto['WIDTH'],
							'height' => $morePhoto['HEIGHT'],
						];
						$x2ResizedImage = $xResizedImage;
					}

					$xResizedImage['src'] = \Bitrix\Iblock\Component\Tools::getImageSrc([
						'SRC' => $xResizedImage['src']
					]);
					$x2ResizedImage['src'] = \Bitrix\Iblock\Component\Tools::getImageSrc([
						'SRC' => $x2ResizedImage['src']
					]);

					$jsOffer['RESIZED_SLIDER']['X'][] = [
						'ID' => $morePhoto['ID'],
						'SRC' => $xResizedImage['src'],
						'WIDTH' => $xResizedImage['width'],
						'HEIGHT' => $xResizedImage['height'],
					];
					$jsOffer['RESIZED_SLIDER']['X2'][] = [
						'ID' => $morePhoto['ID'],
						'SRC' => $x2ResizedImage['src'],
						'WIDTH' => $x2ResizedImage['width'],
						'HEIGHT' => $x2ResizedImage['height'],
					];
				}
			}

			$templateData['OFFER_IDS'] = $offerIds;
			$templateData['OFFER_CODES'] = $offerCodes;
			unset($jsOffer, $strAllProps, $strMainProps, $strPriceRanges, $strPriceRangesRatio, $useRatio, $xResizedImage, $x2ResizedImage);

			$jsParams = array(
				'CONFIG' => array(
					'USE_CATALOG' => $arResult['CATALOG'],
					'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
					'SHOW_PRICE' => true,
					'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
					'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
					'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
					'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
					'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
					'OFFER_GROUP' => $arResult['OFFER_GROUP'],
					'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
					'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
					'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
					'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
					'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
					'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
					'USE_STICKERS' => true,
					'USE_SUBSCRIBE' => $showSubscribe,
					'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
					'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
					'ALT' => $alt,
					'TITLE' => $title,
					'MAGNIFIER_ZOOM_PERCENT' => 200,
					'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
					'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
					'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
						? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
						: null,
					'SHOW_SKU_DESCRIPTION' => $arParams['SHOW_SKU_DESCRIPTION'],
					'DISPLAY_PREVIEW_TEXT_MODE' => $arParams['DISPLAY_PREVIEW_TEXT_MODE']
				),
				'PRODUCT_TYPE' => $arResult['PRODUCT']['TYPE'],
				'VISUAL' => $itemIds,
				'DEFAULT_PICTURE' => array(
					'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
					'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
				),
				'PRODUCT' => array(
                    'PRICE_CODE' => $arParams['PRICE_CODE'],
                    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                    'REPLACE_BUY' => $arParams['BUY_URL'],
                    'REPLACE_ADD' => $arParams['ADD2BASKET_BUY'],
                    'CATEGORY_NAME' => $arResult['SECTION']['NAME'],
					'ID' => $arResult['ID'],
					'ACTIVE' => $arResult['ACTIVE'],
					'NAME' => $arResult['~NAME'],
					'CATEGORY' => $arResult['CATEGORY_PATH'],
					'DETAIL_TEXT' => $arResult['DETAIL_TEXT'],
					'DETAIL_TEXT_TYPE' => $arResult['DETAIL_TEXT_TYPE'],
					'PREVIEW_TEXT' => $arResult['PREVIEW_TEXT'],
					'PREVIEW_TEXT_TYPE' => $arResult['PREVIEW_TEXT_TYPE']
				),
				'BASKET' => array(
					'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
					'BASKET_URL' => $arParams['BASKET_URL'],
					'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
					'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
					'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
				),
				'OFFERS' => $arResult['JS_OFFERS'],
				'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
				'TREE_PROPS' => $skuProps
			);

            if($USER->IsAuthorized()) {
                $jsParams['USER']['NAME'] = $USER->GetFullName();
                $jsParams['USER']['EMAIL'] = $USER->GetEmail();
            }

		}
		else
		{
			$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
			if ($arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !$emptyProductProperties)
			{
				?>
				<div id="<?=$itemIds['BASKET_PROP_DIV']?>" style="display: none;">
					<?php
					if (!empty($arResult['PRODUCT_PROPERTIES_FILL']))
					{
						foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propId => $propInfo)
						{
							?>
							<input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]" value="<?=htmlspecialcharsbx($propInfo['ID'])?>">
							<?php
							unset($arResult['PRODUCT_PROPERTIES'][$propId]);
						}
					}

					$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
					if (!$emptyProductProperties)
					{
						?>
						<table>
							<?php
							foreach ($arResult['PRODUCT_PROPERTIES'] as $propId => $propInfo)
							{
								?>
								<tr>
									<td><?=$arResult['PROPERTIES'][$propId]['NAME']?></td>
									<td>
										<?php
										if (
											$arResult['PROPERTIES'][$propId]['PROPERTY_TYPE'] === 'L'
											&& $arResult['PROPERTIES'][$propId]['LIST_TYPE'] === 'C'
										)
										{
											foreach ($propInfo['VALUES'] as $valueId => $value)
											{
												?>
												<label>
													<input type="radio" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]"
														value="<?=$valueId?>" <?=($valueId == $propInfo['SELECTED'] ? '"checked"' : '')?>>
													<?=$value?>
												</label>
												<br>
												<?php
											}
										}
										else
										{
											?>
											<select name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]">
												<?php
												foreach ($propInfo['VALUES'] as $valueId => $value)
												{
													?>
													<option value="<?=$valueId?>" <?=($valueId == $propInfo['SELECTED'] ? '"selected"' : '')?>>
														<?=$value?>
													</option>
													<?php
												}
												?>
											</select>
											<?php
										}
										?>
									</td>
								</tr>
								<?php
							}
							?>
						</table>
						<?php
					}
					?>
				</div>
				<?php
			}

			$resizedSlider = [
				'X' => [],
				'X2' => [],
			];

			foreach ($arResult['MORE_PHOTO'] as $morePhoto)
			{
				$xResizedImage = \CFile::ResizeImageGet(
					$morePhoto['ID'],
					[
						'width' => 400,
						'height' => 400,
					],
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true
				);

				$x2ResizedImage = \CFile::ResizeImageGet(
					$morePhoto['ID'],
					[
						'width' => 800,
						'height' => 800,
					],
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true
				);

				if (!$xResizedImage || !$x2ResizedImage)
				{
					$xResizedImage = [
						'src' => $morePhoto['SRC'],
						'width' => $morePhoto['WIDTH'],
						'height' => $morePhoto['HEIGHT'],
					];
					$x2ResizedImage = $xResizedImage;
				}

				$resizedSlider['X'][] = [
					'ID' => $morePhoto['ID'],
					'SRC' => $xResizedImage['src'],
					'WIDTH' => $xResizedImage['width'],
					'HEIGHT' => $xResizedImage['height'],
				];
				$resizedSlider['X2'][] = [
					'ID' => $morePhoto['ID'],
					'SRC' => $x2ResizedImage['src'],
					'WIDTH' => $x2ResizedImage['width'],
					'HEIGHT' => $x2ResizedImage['height'],
				];
			}


			$jsParams = array(
				'CONFIG' => array(
					'USE_CATALOG' => $arResult['CATALOG'],
					'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
					'SHOW_PRICE' => !empty($arResult['ITEM_PRICES']),
					'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
					'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
					'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
					'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
					'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
					'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
					'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
					'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
					'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
					'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
					'USE_STICKERS' => true,
					'USE_SUBSCRIBE' => $showSubscribe,
					'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
					'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
					'ALT' => $alt,
					'TITLE' => $title,
					'MAGNIFIER_ZOOM_PERCENT' => 200,
					'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
					'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
					'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
						? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
						: null
					),
				'VISUAL' => $itemIds,
				'PRODUCT_TYPE' => $arResult['PRODUCT']['TYPE'],
				'PRODUCT' => array(
				    'PRICE_CODE' => $arParams['~PRICE_CODE'],
                    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                    'REPLACE_BUY' => $arParams['BUY_URL'],
                    'REPLACE_ADD' => $arParams['ADD2BASKET_BUY'],
                    'CATEGORY_NAME' => $arResult['SECTION']['NAME'],
					'ID' => $arResult['ID'],
					'ACTIVE' => $arResult['ACTIVE'],
					'PICT' => reset($arResult['MORE_PHOTO']),
					'NAME' => $arResult['~NAME'],
					'SUBSCRIPTION' => true,
					'ITEM_PRICE_MODE' => $arResult['ITEM_PRICE_MODE'],
					'ITEM_PRICES' => $arResult['ITEM_PRICES'],
					'ITEM_PRICE_SELECTED' => $arResult['ITEM_PRICE_SELECTED'],
					'ITEM_QUANTITY_RANGES' => $arResult['ITEM_QUANTITY_RANGES'],
					'ITEM_QUANTITY_RANGE_SELECTED' => $arResult['ITEM_QUANTITY_RANGE_SELECTED'],
					'ITEM_MEASURE_RATIOS' => $arResult['ITEM_MEASURE_RATIOS'],
					'ITEM_MEASURE_RATIO_SELECTED' => $arResult['ITEM_MEASURE_RATIO_SELECTED'],
					'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
					'SLIDER' => $arResult['MORE_PHOTO'],
					'RESIZED_SLIDER' => $resizedSlider,
					'CAN_BUY' => $arResult['CAN_BUY'],
					'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
					'QUANTITY_FLOAT' => is_float($arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
					'MAX_QUANTITY' => $arResult['PRODUCT']['QUANTITY'],
					'STEP_QUANTITY' => $arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
					'CATEGORY' => $arResult['CATEGORY_PATH']
				),
				'BASKET' => array(
					'ADD_PROPS' => $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y',
					'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
					'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
					'EMPTY_PROPS' => $emptyProductProperties,
					'BASKET_URL' => $arParams['BASKET_URL'],
					'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
					'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
				)
			);
            if($USER->IsAuthorized()) {
                $jsParams['USER']['NAME'] = $USER->GetFullName();
                $jsParams['USER']['EMAIL'] = $USER->GetEmail();
            }
			unset($emptyProductProperties, $resizedSlider, $xResizedImage, $x2ResizedImage);
		}

		$jsParams['IS_FACEBOOK_CONVERSION_CUSTOMIZE_PRODUCT_EVENT_ENABLED'] =
			$arResult['IS_FACEBOOK_CONVERSION_CUSTOMIZE_PRODUCT_EVENT_ENABLED']
		;

		?>

        <div class="show_product_table">

            <div class="related-product-area" id="related-product-area">
                <div class="page-header">
                    <h2>Результаты поиска по позиции</h2>
                </div>
            </div>

        </div>

    </div>
    </div>

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


    </div>
    </div>
    </div>
<script>
	BX.message({
		ECONOMY_INFO_MESSAGE: '<?=GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO2')?>',
		TITLE_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR')?>',
		TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS')?>',
		BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR')?>',
		BTN_SEND_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS')?>',
		BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
		BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE')?>',
		BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
		TITLE_SUCCESSFUL: '<?=GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK')?>',
		COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK')?>',
		COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
		COMPARE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE')?>',
		BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
		PRODUCT_GIFT_LABEL: '<?=GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL')?>',
		PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
		RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
		RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
		SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>'
	});

	var <?=$obName?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
</script>

<?php
	$arrayData = array(
		"@context" => "https://schema.org/",
		"@type" => "Product",
	);

	$arrayData["name"] = $name;

	//region PREVIEW_TEXT
	if (isset($arResult['PREVIEW_TEXT']))
	{
		$arrayData["description"] = $arResult['PREVIEW_TEXT'];
	}

	//endregion

	//region category
	if(isset($arResult['CATEGORY_PATH']))
	{
		$arrayData['category'] = $arResult['CATEGORY_PATH'];
	}

	//endregion

	//region link
	if(isset($arResult['DETAIL_PAGE_URL']))
	{
		$arrayData['link'] = $arResult['DETAIL_PAGE_URL'];
	}

	//endregion

	//region MORE_PHOTO
	if (!empty($actualItem['MORE_PHOTO']))
	{
		foreach ($actualItem['MORE_PHOTO'] as $key => $photo)
		{
			$arrayData['image'][] = $photo['SRC'];
		}
	}

	//endregion

	//region $haveOffers
	if ($haveOffers)
	{
		foreach ($arResult['JS_OFFERS'] as $offer)
		{
			$currentOffersList = array();

			if (!empty($offer['TREE']) && is_array($offer['TREE']))
			{
				foreach ($offer['TREE'] as $propName => $skuId)
				{
					$propId = (int)substr($propName, 5);

					foreach ($skuProps as $prop)
					{
						if ($prop['ID'] == $propId)
						{
							foreach ($prop['VALUES'] as $propId => $propValue)
							{
								if ($propId == $skuId)
								{
									$currentOffersList[] = $propValue['NAME'];
									break;
								}
							}
						}
					}
				}
			}

			$offerPrice = $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']];

			$arrayDataOffers[] = array(
				"sku" => htmlspecialcharsbx(implode('/', $currentOffersList)),
				"price" => $offerPrice['RATIO_PRICE'],
				"priceCurrency" => $offerPrice['CURRENCY'],
				"availability" => ($offer['CAN_BUY'] ? 'InStock' : 'OutOfStock')
			);
		}


		unset($offerPrice, $currentOffersList);
	}
	else
	{
		$arrayDataOffers[] = array(
			"price" => $price['RATIO_PRICE'],
			"priceCurrency" => $price['CURRENCY'],
			"availability" => ($actualItem['CAN_BUY'] ? 'InStock' : 'OutOfStock')
		);
	}

	$arrayData["offers"] = $arrayDataOffers;

	//endregion

	//region USE_VOTE_RATING
	//todo: need to add ratingCount
	if ($arParams['USE_VOTE_RATING'] === 'Y' && false)
	{
		$arrayData["aggregateRating"] = array(
			"@type" => "AggregateRating",
			"ratingValue" => $arResult["PROPERTIES"]["rating"]['VALUE'],
			"reviewCount" => $arResult["PROPERTIES"]["rating"]['VALUE']
		);
	}

	//endregion

?><script type="application/ld+json"><?=json_encode($arrayData, JSON_UNESCAPED_UNICODE ), "\n\n";?></script><?php

unset($actualItem, $itemIds, $jsParams);
?>
<div id="quick_view_popup-wrap"></div>
