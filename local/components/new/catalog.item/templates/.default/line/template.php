<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var bool $itemHasDetailUrl
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var string $discountPositionClass
 * @var string $labelPositionClass
 * @var CatalogSectionComponent $component
 */
$arParams['SHOW_MAX_QUANTITY'] = 'Y';
if ($haveOffers)
{
	$showDisplayProps = !empty($item['DISPLAY_PROPERTIES']);
	$showProductProps = $arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $item['OFFERS_PROPS_DISPLAY'];
	$showPropsBlock = $showDisplayProps || $showProductProps;
	$showSkuBlock = $arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && !empty($item['OFFERS_PROP']);
}
else
{
	$showDisplayProps = !empty($item['DISPLAY_PROPERTIES']);
	$showProductProps = $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !empty($item['PRODUCT_PROPERTIES']);
	$showPropsBlock = $showDisplayProps || $showProductProps;
	$showSkuBlock = false;
}
?>



<div id="productTabContent" class="product-list-area">
    <ul class="products-list" id="products-list">
        <li class="item no-image">
            <div class="product-shop">
                <table class="table table-bordered cart_summary slim-order jq-sortertable_order_short">
                    <thead class="valign-middle">
                    <tr>
                        <th style="width:20%;" class="header">Наименование</th>
                        <?if($showSkuBlock): ?>
                        <th style="width:40%;" class="header">Харктеристики</th>
                        <? endif; ?>
                        <th class="txt-trns-normal header">Наличие, шт.</th>
                        <th class="text-right txt-trns-normal header headerSortDown">
                            Цена, руб
                        </th>
                        <th class="ord-summ txt-trns-normal">Нужное к-во, шт.
                        </th>
                        <th class="action">
                            <i class="fa fa-shopping-basket"></i>
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <td class="cart_description"  id="<?=$itemIds['PICT_SLIDER']?>">
                            <p class="product-name" id="<?=$itemIds['PICT']?>">
                                <? if ($itemHasDetailUrl): ?>
                                <a href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$productTitle?>">
                                    <? endif; ?>
                                    <?=$productTitle?>
                                    <? if ($itemHasDetailUrl): ?>
                                </a>
                            <? endif; ?>
                            </p>
                        </td>
                        <?if($showSkuBlock): ?>
                        <td class="store-td">
                            <div id="<?=$itemIds['PROP_DIV']?>">
                                <?
                                foreach ($arParams['SKU_PROPS'] as $skuProperty)
                                {
                                    $propertyId = $skuProperty['ID'];
                                    $skuProperty['NAME'] = htmlspecialcharsbx($skuProperty['NAME']);
                                    if (!isset($item['SKU_TREE_VALUES'][$propertyId]))
                                        continue;
                                    ?>
                                    <div class="product-item-info-container" data-entity="sku-block">
                                        <div class="product-item-scu-container" data-entity="sku-line-block">
                                            <?=$skuProperty['NAME']?>
                                            <div class="product-item-scu-block">
                                                <div class="product-item-scu-list">
                                                    <ul class="product-item-scu-item-list">
                                                        <?
                                                        foreach ($skuProperty['VALUES'] as $value)
                                                        {
                                                            if (!isset($item['SKU_TREE_VALUES'][$propertyId][$value['ID']]))
                                                                continue;

                                                            $value['NAME'] = htmlspecialcharsbx($value['NAME']);

                                                            if ($skuProperty['SHOW_MODE'] === 'PICT')
                                                            {
                                                                ?>
                                                                <li class="product-item-scu-item-color-container" title="<?=$value['NAME']?>"
                                                                    data-treevalue="<?=$propertyId?>_<?=$value['ID']?>" data-onevalue="<?=$value['ID']?>">
                                                                    <div class="product-item-scu-item-color-block">
                                                                        <div class="product-item-scu-item-color" title="<?=$value['NAME']?>"
                                                                             style="background-image: url('<?=$value['PICT']['SRC']?>');">
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <?
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                <li class="product-item-scu-item-text-container" title="<?=$value['NAME']?>"
                                                                    data-treevalue="<?=$propertyId?>_<?=$value['ID']?>" data-onevalue="<?=$value['ID']?>">
                                                                    <div class="product-item-scu-item-text-block">
                                                                        <div class="product-item-scu-item-text"><?=$value['NAME']?></div>
                                                                    </div>
                                                                </li>
                                                                <?
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                    <div style="clear: both;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                }
                                ?>
                            </div>
                        </td>
                         <? endif; ?>
                        <td class="availability">
                          <?  if ($arParams['SHOW_MAX_QUANTITY'] !== 'N')
                            {
                            if ($haveOffers)
                            {
                            if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
                            {
                            ?>
                            <div class="product-item-info-container"
                                 id="<?=$itemIds['QUANTITY_LIMIT']?>"
                                 style="display: none;"
                                 data-entity="quantity-limit-block">
                                <div class="product-item-info-container-title">
                                    <?=$arParams['MESS_SHOW_MAX_QUANTITY']?>:
                                    <span class="product-item-quantity" data-entity="quantity-limit-value"></span>
                                </div>
                            </div>
                            <?
								}
							}
							else
							{
								if (
									$measureRatio
									&& (float)$actualItem['CATALOG_QUANTITY'] > 0
									&& $actualItem['CATALOG_QUANTITY_TRACE'] === 'Y'
									&& $actualItem['CATALOG_CAN_BUY_ZERO'] === 'N'
								)
								{
									?>
                            <div class="product-item-info-container product-item-hidden" id="<?=$itemIds['QUANTITY_LIMIT']?>">
                                <div class="product-item-info-container-title">
                                    <?=$arParams['MESS_SHOW_MAX_QUANTITY']?>:
                                    <span class="product-item-quantity" data-entity="quantity-limit-value">
												<?
                                                if ($arParams['SHOW_MAX_QUANTITY'] === 'M')
                                                {
                                                    if ((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR'])
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
                                                    echo $actualItem['CATALOG_QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE'];
                                                }
                                                ?>
											</span>
                                </div>
                            </div>
                            <?
								}
							}
						}?>
                        </td>
                        <td class="text-right">

                            <div class="product-item-info-container product-item-price-container" data-entity="price-block">
                                <?
                                if ($arParams['SHOW_OLD_PRICE'] === 'Y')
                                {
                                    ?>
                                    <span class="product-item-price-old" id="<?=$itemIds['PRICE_OLD']?>"
									<?=($price['RATIO_PRICE'] >= $price['RATIO_BASE_PRICE'] ? 'style="display: none;"' : '')?>>
									<?=$price['PRINT_RATIO_BASE_PRICE']?>
								</span>&nbsp;
                                    <?
                                }
                                ?>
                                <span class="product-item-price-current" id="<?=$itemIds['PRICE']?>">
								<?
                                if (!empty($price))
                                {
                                    if ($arParams['PRODUCT_DISPLAY_MODE'] === 'N' && $haveOffers)
                                    {
                                        echo Loc::getMessage(
                                            'CT_BCI_TPL_MESS_PRICE_SIMPLE_MODE',
                                            array(
                                                '#PRICE#' => $price['PRINT_RATIO_PRICE'],
                                                '#VALUE#' => $measureRatio,
                                                '#UNIT#' => $minOffer['ITEM_MEASURE']['TITLE']
                                            )
                                        );
                                    }
                                    else
                                    {
                                        echo $price['PRINT_RATIO_PRICE'];
                                    }
                                }
                                ?>
							</span>
                            </div>

                        </td>
                        <td class="ord-summ">
                            <?
                            if ($haveOffers)
                            {
                                if ($actualItem['CAN_BUY'] && $arParams['USE_PRODUCT_QUANTITY'])
                                {
                                    ?>
                                    <div class="product-item-info-container" data-entity="quantity-block">
                                        <div class="product-item-amount">
                                            <div class="product-item-amount-field-container">
                                                <span class="product-item-amount-field-btn-minus no-select" id="<?=$itemIds['QUANTITY_DOWN']?>"></span>
                                                <input class="product-item-amount-field" id="<?=$itemIds['QUANTITY']?>" type="number"
                                                       name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>"
                                                       value="<?=$measureRatio?>">
                                                <span class="product-item-amount-field-btn-plus no-select" id="<?=$itemIds['QUANTITY_UP']?>"></span>
                                                <div class="product-item-amount-description-container">
												<span id="<?=$itemIds['QUANTITY_MEASURE']?>">
													<?=$actualItem['ITEM_MEASURE']['TITLE']?>
												</span>
                                                    <span id="<?=$itemIds['PRICE_TOTAL']?>"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                }
                            }
                            elseif ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
                            {
                                if ($arParams['USE_PRODUCT_QUANTITY'])
                                {
                                    ?>
                                    <div class="product-item-info-container" data-entity="quantity-block">
                                        <div class="product-item-amount">
                                            <div class="product-item-amount-field-container">
                                                <span class="product-item-amount-field-btn-minus no-select" id="<?=$itemIds['QUANTITY_DOWN']?>"></span>
                                                <input class="product-item-amount-field" id="<?=$itemIds['QUANTITY']?>" type="number"
                                                       name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>"
                                                       value="<?=$measureRatio?>">
                                                <span class="product-item-amount-field-btn-plus no-select" id="<?=$itemIds['QUANTITY_UP']?>"></span>
                                                <div class="product-item-amount-description-container">
                                                    <span id="<?=$itemIds['QUANTITY_MEASURE']?>"></span>
                                                    <span id="<?=$itemIds['PRICE_TOTAL']?>"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                }
                            }
                            ?>
                        </td>
                        <td class="action">

                            <?if (!$haveOffers)
							{
								if ($actualItem['CAN_BUY'])
								{
									?>
									<div class="product-item-button-container" id="<?=$itemIds['BASKET_ACTIONS']?>">
										<a class="button cart-button button-green btn-link buy_btn_altermax <?=$buttonSizeClass?>" id="<?=$itemIds['BUY_LINK']?>"
											href="javascript:void(0)" rel="nofollow">
											<?=($arParams['ADD_TO_BASKET_ACTION'] === 'BUY' ? $arParams['MESS_BTN_BUY'] : $arParams['MESS_BTN_ADD_TO_BASKET'])?>
										</a>
									</div>
									<?
								}
								else
								{
									?>
									<div class="product-item-button-container">
										<?
										if ($showSubscribe)
										{
											$APPLICATION->IncludeComponent(
												'bitrix:catalog.product.subscribe',
												'',
												array(
													'PRODUCT_ID' => $actualItem['ID'],
													'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
													'BUTTON_CLASS' => 'btn btn-default '.$buttonSizeClass,
													'DEFAULT_DISPLAY' => true,
													'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
												),
												$component,
												array('HIDE_ICONS' => 'Y')
											);
										}
										?>
										<a class="button cart-button button-green btn-link buy_btn_altermax <?=$buttonSizeClass?>" id="<?=$itemIds['NOT_AVAILABLE_MESS']?>"
											href="javascript:void(0)" rel="nofollow">
											<?=$arParams['MESS_NOT_AVAILABLE']?>
										</a>
									</div>
									<?
								}
							}
							else
							{
								if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
								{
									?>
									<div class="product-item-button-container">
										<?
										if ($showSubscribe)
										{
											$APPLICATION->IncludeComponent(
												'bitrix:catalog.product.subscribe',
												'',
												array(
													'PRODUCT_ID' => $item['ID'],
													'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
													'BUTTON_CLASS' => 'button cart-button button-green btn-link buy_btn_altermax '.$buttonSizeClass,
													'DEFAULT_DISPLAY' => !$actualItem['CAN_BUY'],
													'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
												),
												$component,
												array('HIDE_ICONS' => 'Y')
											);
										}
										?>
										<a class="btn btn-link <?=$buttonSizeClass?>"
											id="<?=$itemIds['NOT_AVAILABLE_MESS']?>" href="javascript:void(0)" rel="nofollow"
											<?=($actualItem['CAN_BUY'] ? 'style="display: none;"' : '')?>>
											<?=$arParams['MESS_NOT_AVAILABLE']?>
										</a>
										<div id="<?=$itemIds['BASKET_ACTIONS']?>" <?=($actualItem['CAN_BUY'] ? '' : 'style="display: none;"')?>>
											<a class="button cart-button button-green btn-link buy_btn_altermax buy_btn_altermax <?=$buttonSizeClass?>" id="<?=$itemIds['BUY_LINK']?>"
												href="javascript:void(0)" rel="nofollow">
												<?=($arParams['ADD_TO_BASKET_ACTION'] === 'BUY' ? $arParams['MESS_BTN_BUY'] : $arParams['MESS_BTN_ADD_TO_BASKET'])?>
											</a>
										</div>
									</div>
									<?
								}
								else
								{
									?>
									<div class="product-item-button-container">
										<a class="btn btn-default buy_btn_altermax <?=$buttonSizeClass?>" href="<?=$item['DETAIL_PAGE_URL']?>">
											<?=$arParams['MESS_BTN_DETAIL']?>
										</a>
									</div>
									<?
								}
							}?>


                            <form class="product-add hide-this to-order-ajax-form"
                                  id="frm-by-one-click-165049327"
                                  action="FSCQ0765RTYDTU.html" method="post">
                                <input type="hidden" name="fnc-form"
                                       value="order_cart">
                                <input type="hidden" name="fnc-elem"
                                       value="product_toclick">
                                <input type="hidden" name="id"
                                       value="165049327">
                                <input type="hidden" name="name"
                                       value="FSCQ0765RTYDTU">
                                <input type="hidden" name="provider_id"
                                       value="93">
                                <input type="hidden" name="price"
                                       value="187.50">
                            </form>
                            <button form="frm-by-one-click-165049327"
                                    class="button cart-button by-one-click"
                                    type="submit">
                                <i class="fa fa-shopping-cart"></i><span>Заявка</span>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="7"></td>
                    </tr>
                    </tfoot>
                </table>
            </div><!-- .product-shop -->
        </li><!-- .item -->
    </ul>
</div>




	<?
	if ($haveOffers)
	{
        if ($showSkuBlock)
        {
            foreach ($arParams['SKU_PROPS'] as $skuProperty)
            {
                if (!isset($item['OFFERS_PROP'][$skuProperty['CODE']]))
                    continue;

                $skuProps[] = array(
                    'ID' => $skuProperty['ID'],
                    'SHOW_MODE' => $skuProperty['SHOW_MODE'],
                    'VALUES' => $skuProperty['VALUES'],
                    'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
                );
            }

            unset($skuProperty, $value);

            if ($item['OFFERS_PROPS_DISPLAY'])
            {
                foreach ($item['JS_OFFERS'] as $keyOffer => $jsOffer)
                {
                    $strProps = '';

                    if (!empty($jsOffer['DISPLAY_PROPERTIES']))
                    {
                        foreach ($jsOffer['DISPLAY_PROPERTIES'] as $displayProperty)
                        {
                            $strProps .= '<dt>'.$displayProperty['NAME'].'</dt><dd>'
                                .(is_array($displayProperty['VALUE'])
                                    ? implode(' / ', $displayProperty['VALUE'])
                                    : $displayProperty['VALUE'])
                                .'</dd>';
                        }
                    }
                    $item['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;
                }
                unset($jsOffer, $strProps);
            }
            ?>
            <?
        }

	}

	?>