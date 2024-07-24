<?php

use Bitrix\Main\Error;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\Json;
use Bitrix\Main;
/*
Да, в шаблоне куча непонятных айдишников, просто не был времени выносить всё это в параметры компонента
*/
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var OpenSourceOrderComponent $component */
$APPLICATION->SetTitle("Оформление заказа");
?>

<form action="" method="post" name="os-order-form" id="os-order-form" class="os-order-form">
<div class="order_form">


    <div class="order-section order-section-delivery">
        <div class="order-section-header">
            <span class="order-section-header-text">СПОСОБ ДОСТАВКИ</span>
        </div>
        <div class="order-section-body">
            <div class="form-row" id="os-order-delivery-block2">

                <h3 class="info"> Выберете свой город для расчета стоимости доставки </h3>
                <div class="location">
                    <label class="error" data-value="<?= $arResult['PROPERTIES'][1]['LOCATION']['FORM_NAME'] ?>"></label>
                    <select autocomplete="off" class="location-search" name="<?= $arResult['PROPERTIES'][1]['LOCATION']['FORM_NAME'] ?>"
                            id="<?= $arResult['PROPERTIES'][1]['LOCATION']['FORM_LABEL'] ?>" style="height:auto">

                        <option
                                value="<?= $arResult['PROPERTIES'][1]['LOCATION']['VALUE'] ?>"><?= $arResult['PROPERTIES'][1]['LOCATION']['LOCATION_DATA']['name'] ?>
                        </option>
                        <?
                        \Bitrix\Main\Loader::includeModule('sale');
                        $res = \Bitrix\Sale\Location\LocationTable::getList(array(
                          'filter' => array('=NAME.LANGUAGE_ID' => LANGUAGE_ID, 'TYPE_CODE' => 'CITY'),
                           'select' => array('*', 'NAME_RU' => 'NAME.NAME', 'TYPE_CODE' => 'TYPE.CODE')
                           ));
                        ?>

                         <?  while($item = $res->fetch()): ?>
                        <option
                                value="<?= $item['CODE'] ?>"><?= $item['NAME_RU'] ?>
                        </option>
                        <? endwhile; ?>
                    </select>
                </div>

                <div class="delivery_info">
                <? foreach ($arResult['DELIVERY_ERRORS'] as $error):
                    /** @var Error $error */
                    ?>
                    <div class="error"><?= $error->getMessage() ?></div>
                <? endforeach;
                 ?>
                 <div class = "delivery__item__container">
                 <?
                foreach ($arResult['DELIVERY'] as $arDelivery):?>
                    <div class="delivery__item">
                        <img class="delivery-img" src="<?=$arDelivery['IMG']?>">
                        <div class="dilivery--info">
                            <label class="label"> <?= $arDelivery['NAME'] ?> </label>
                            <? if($arDelivery['PRICE'] == 'error'): ?>
                                <label class="label-error-delivery">Не рассчитана</label>
                            <?else:?>
                                <label class="price-delivery"><?= $arDelivery['PRICE'] ?> Р.</label>
                            <? endif; ?>
                        </div>
                        <input id="delivery_id" type="radio" name="delivery_id"
                               value="<?= $arDelivery['ID'] ?>"
                            <?= $arDelivery['CHECKED'] ? 'checked' : '' ?>>
                    </div>
                <? endforeach; ?>
                 </div>

                 <div class="info_container">
                  <? if(!empty($arResult['DELIVERY'][$arResult['DELIVERY_ID']])): ?>
                      <div class="img_container_delivery">
                          <img src="<?=$arResult['DELIVERY'][$arResult['DELIVERY_ID']]['IMG']?>">
                      </div>
                     <div class="description">
                      <?=$arResult['DELIVERY'][$arResult['DELIVERY_ID']]['DESCRIPTION']?>
                     </div>
                     <div class="service__info">

                         <? if(!empty($arResult['DELIVERY'][$arResult['DELIVERY_ID']]['EXTRA_SERVICE'])): ?>
                             <? foreach ($arResult['DELIVERY_LIST'][$arResult['DELIVERY_ID']]['EXTRA_SERVICE'] as $key => $service): ?>
                                 <?if ($service->canUserEditValue()):?>
                                     <input type="checkbox" name="<?=$service->getCode()?>" value="<?=$key?>" name="service__list">
                                     <label class="label"> <?=$service->getName()?> </label>
                                 <? endif; ?>
                             <? endforeach; ?>
                         <? endif; ?>

                     </div>

                     <div class="period__text">
                   <?=$arResult['DELIVERY'][$arResult['DELIVERY_ID']]['PERIOD_TEXT']?>
                  </div>

                  <? endif; ?>
                  </div>


                </div>

            </div>

        </div>


    </div>


    <?
    if($arResult['DELIVERY_ID'] == 41) {
        $disp = 'block';
    } else {
        $disp = 'none';
    }
    ?>


    <div class="order-section order-section-property" id="order-section-property-pdp" style="display:<?=$disp?>">
        <div class="order-section-header">
            <span class="order-section-header-text"> ВВЕДИТЕ НОМЕР ДЛЯ СВЯЗИ </span>
        </div>
        <div class="properties-table properties-1 active" id="properties-table">
            <div class="form-row">
                <? foreach ($arResult['PROPERTIES'][$arParams['PERSON_TYPE_ID']] as $prop): ?>
                    <? switch ($prop['FORM_NAME']): case 'PROPERTIES[PDP_PHONE]': ?>
                        <div class="item">
                            <label>Номер мобильного</label>
                            <label class="error" data-value="PROPERTIES[PDP_PHONE]"></label>
                            <input class="phone form-control" placeholder="+7 (___) ___-__-__" type="text" value="<?=$arResult['PROPERTIES'][$arParams['PERSON_TYPE_ID']]['PHONE']['VALUE']?>" id="property_PDP_PHONE" name="PROPERTIES[PDP_PHONE]" >
                        </div>
                        <? break; ?>
                    <? endswitch; ?>
                <? endforeach; ?>
            </div>
        </div>
    </div>


    <?
if($arResult['DELIVERY_ID'] == 2 or $arResult['DELIVERY_ID'] == 41) {
    $disp = 'none';
} else {
    $disp = 'block';
}
    ?>


    <div class="order-section order-section-property" id="order-section-property-default" style="display:<?=$disp?>">
        <div class="order-section-header">
            <span class="order-section-header-text">АДРЕС ПОЛУЧАТЕЛЯ</span>
        </div>
        <div class="properties-table properties-1 active" id="properties-table">
            <div class="form-row">
                <? foreach ($arResult['PROPERTIES'][$arParams['PERSON_TYPE_ID']] as $prop): ?>
                <? switch ($prop['FORM_NAME']): case 'PROPERTIES[CITY]': ?>
                <div class="item">
                    <label>Город</label>
                    <label class="error" data-value="PROPERTIES[CITY]"></label>
                    <input class="form-control" type="text" value="<?=$prop['VALUE']?>" id="property_CITY" name="PROPERTIES[CITY]" >
                </div>
               <? break; ?>
               <?case 'PROPERTIES[REGION]': ?>
                <div class="item">
                <label>Область</label>
                <label class="error" data-value="PROPERTIES[REGION]"></label>
                <input class="form-control" type="text" value="<?=$prop['VALUE']?>"  id="property_REGION" name="PROPERTIES[REGION]">
                </div>
                <? break; ?>
               <?case 'PROPERTIES[STREET]': ?>
                <div class="item">
                    <label>Улица</label>
                    <label class="error" data-value="PROPERTIES[STREET]"></label>
                    <input class="form-control" type="text" value="<?=$prop['VALUE']?>"  id="property_STREET" name="PROPERTIES[STREET]">
                </div>
               <? break; ?>
               <?case 'PROPERTIES[HOUSE]': ?>
                <div class="item">
                    <label>Дом</label>
                    <label class="error" data-value="PROPERTIES[HOUSE]"></label>
                    <input class="form-control" type="text" value="<?=$prop['VALUE']?>"  id="property_HOUSE" name="PROPERTIES[HOUSE]">
                </div>
                <? break; ?>
               <?case 'PROPERTIES[ZIP]': ?>
                <div class="item">
                    <label>Индекс</label>
                    <label class="error" data-value="PROPERTIES[ZIP]"></label>
                    <input class="form-control" type="text" value="<?=$prop['VALUE']?>" placeholder="Авто заполнение" required="required" id="property_ZIP" name="PROPERTIES[ZIP]">
                </div>
                <? break; ?>
                <?case 'PROPERTIES[PHONE]': ?>
                <div class="item">
                    <label>Телефон</label>
                    <label class="error" data-value="PROPERTIES[PHONE]"></label>
                    <input class="phone form-control" placeholder="+7 (___) ___-__-__" type="text" value="<?=$prop['VALUE']?>" id="property_PHONE" name="PROPERTIES[PHONE]" maxlength="255">
                </div>
                <? break; ?>
                <? endswitch; ?>
                <? endforeach; ?>
            </div>
        </div>
    </div>


    <div class="order-section order-section-pay">
            <div class="order-section-header">
                <span class="order-section-header-text">СПОСОБЫ ОПЛАТЫ</span>
            </div>
            <div class="order-section-body">
                <div class="order-section-body">
                    <div class="form-row form-row-pay">

                        <? foreach ($arResult['PAY_SYSTEM_ERRORS'] as $error):
                            /** @var Error $error */
                            ?>
                            <div class="error"><?= $error->getMessage() ?></div>
                        <? endforeach;
                        foreach ($arResult['PAY_SYSTEM_LIST'] as $arPaySystem): ?>
                        <div class="pay__item">
                                <input type="radio" name="pay_system_id"
                                       value="<?= $arPaySystem['ID'] ?>"
                                    <?= $arPaySystem['CHECKED'] ? 'checked' : '' ?>>
                            <label class="label"> <?= $arPaySystem['NAME'] ?> </label>
                        </div>
                        <? endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
    


        <?
if($arResult['PAY_SYSTEM_ID'] == 8) {
    $disp = 'block';
} else {
    $disp = 'none';
}
?>
    <div class="order-section order-section-property" id="order-section-property-organization" style="display:<?=$disp?>">
        <div class="order-section-header">
            <span class="order-section-header-text">Информация о компании</span>
        </div>
        <div class="properties-table properties-1 active" id="properties-table">
            <div class="form-row">
                <? foreach ($arResult['PROPERTIES'][$arParams['PERSON_TYPE_ID']] as $prop): ?>
                <? switch ($prop['FORM_NAME']): case 'PROPERTIES[COMPANY]': ?>
                <div class="item">
                    <label>Название компании</label>
                    <label class="error" data-value="PROPERTIES[COMPANY]"></label>
                    <input class="form-control" type="text" value="<?=$prop['VALUE']?>" id="property_COMPANY" name="PROPERTIES[COMPANY]">
                </div>
                <?break;?>
                <?case 'PROPERTIES[INN]': ?>
                <div class="item">
                    <label>ИНН</label>
                    <label class="error" data-value="PROPERTIES[INN]"></label>
                    <input class="form-control" type="text" value="<?=$prop['VALUE']?>" id="property_INN" name="PROPERTIES[INN]">
                </div>
                <? break; ?>
                <?case 'PROPERTIES[COMPANY_ADR]': ?>
                <div class="item">
                    <label>Юридический адрес</label>
                    <label class="error" data-value="PROPERTIES[COMPANY_ADR]"></label>
                    <input class="form-control" type="text" value="<?=$prop['VALUE']?>" id="property_COMPANY_ADR" name="PROPERTIES[COMPANY_ADR]">
                </div>
                <? break; ?>
                <?case 'PROPERTIES[WORK_PHONE]': ?>
                <div class="item">
                    <label>Телефон организации</label>
                    <label class="error" data-value="PROPERTIES[WORK_PHONE]"></label>
                    <input class="phone form-control" placeholder="+7 (___) ___-__-__" type="text" value="<?=$prop['VALUE']?>" id="property_WORK_PHONE" name="PROPERTIES[WORK_PHONE]">
                </div>
                <? break; ?>
                <?endswitch;?>
                <?endforeach;?>
            </div>
        </div>

    </div>


        <div class="order-section order-section-error section-email">

        </div>

    <div class="section_error_container">
    <? if($arResult['DELIVERY_PRICE'] == 'error'): ?>
    <div class="order-section order-section-error">
        <div class="order-section-body">
            <div class="order-section-body">
                <div class="form-row form-row-basket delivery_error">

                    Не удалось рассчитать стоимость доставки.
                    Вы можете продолжить оформление заказа, а чуть позже менеджер магазина свяжется с вами и уточнит информацию по доставке.

                </div>
            </div>
        </div>
    </div>
    <? endif; ?>
    </div>


    <div class="order-section order-comment">
        <div class="order-section-header">
            <span class="order-section-header-text">КОММЕНТАРИЙ К ЗАКАЗУ</span>
        </div>
        <div class="order-section-body">
            <div class="order-section-body">
                <div class="item">
                    <label class="error" data-value="PROPERTIES[COMMENT]"></label>
                    <input class="form-control-text" type="textarea" id="property_COMMENT" name="PROPERTIES[COMMENT]" value="">
                </div>

            </div>
        </div>
    </div>

    <div class="order-section order-section-basket">
        <div class="order-section-header">
            <span class="order-section-header-text">ТОВАРЫ В ЗАКАЗЕ</span>
        </div>
        <div class="order-section-body">
            <div class="order-section-body">
                <div class="form-row form-row-basket">

                    <div class="item--basket-header">

                        <div class="basket-name">
                            <label class="item-name red" >НАИМЕНОВАНИЕ</label>
                        </div>

                        <div class="basket-price-one">

                            <label class="new-price red" >ЦЕНА, Р.</label>
                        </div>

                        <div class="basket-count">
                            <label class="item-count red" >КОЛ-ВО</label>
                        </div>

                        <div class="final-price">
                            <label class="item-final-price red">
                                СТОИМОСТЬ, Р.
                            </label>
                        </div>


                    </div>

                    <? foreach ($arResult['BASKET'] as $arBasketItem): ?>

                        <div class="item--bakset">

                            <div class="basket-name">
                                <label class="item-name"><?= $arBasketItem['NAME'] ?></label>
                            </div>

                            <div class="basket-price-one">

                                <label class="new-price"><?= $arBasketItem['PRICE_DISPLAY'] ?></label>
                                <? if((int)$arBasketItem['PRICE'] !== (int)$arBasketItem['BASE_PRICE'] and  ((int)$arBasketItem['BASE_PRICE'] > (int)$arBasketItem['PRICE'])): ?>
                                    <label class="old-price"><?= $arBasketItem['BASE_PRICE_DISPLAY'] ?></label>
                                <? endif; ?>
                            </div>

                            <div class="basket-count">
                                <label class="item-count"><?= $arBasketItem['QUANTITY_DISPLAY'] ?></label>
                            </div>

                            <div class="final-price">
                                <label class="item-final-price">
                                    <?= $arBasketItem['SUM_DISPLAY'] ?>
                                </label>
                            </div>


                        </div>
                    <? endforeach; ?>

                </div>
            </div>
        </div>
    </div>

</div>

    <div class="area-total">
        <div class="total--content">
            <div class="total--header">
                <h4>
                    Ваша заказ:
                </h4>
            </div>
            <div class="total--product">
                <label>Товаров на: </label>
                <div class="price--display">
                    <? if((int)$arResult['PRODUCTS_DISCOUNT_DISPLAY'] > 0): ?>
                        <span class="price"> <?= ($arResult['PRODUCTS_PRICE_DISPLAY']) ?> Р. </span>
                        <label class="old--price" style="text-decoration: line-through;"> <?= ($arResult['PRODUCTS_BASE_PRICE_DISPLAY']) ?> Р. </label>
                    <? else: ?>
                        <span class="price">   <?= ($arResult['PRODUCTS_PRICE_DISPLAY']) ?> Р. </span>
                    <? endif; ?>
                </div>
            </div>
            <div class="total--delivery">
                <label>Доставка: </label>
                <? if($arResult['DELIVERY_PRICE'] == 'error'): ?>
                    <label class="price_delivery error_delivery"> Не расчитана  </label>
                <? else: ?>
                    <label class="price_delivery price"><?= ($arResult['DELIVERY_PRICE_DISPLAY']) ?> Р. </label>
                <? endif; ?>
            </div>
            <? if((int)$arResult['PRODUCTS_DISCOUNT_DISPLAY'] > 0): ?>
                <div class="total--sale">
                    <label>Экономия:</label>
                    <label class="price_delivery price"> <?= $arResult['PRODUCTS_DISCOUNT_DISPLAY']?> Р. </label>
                </div>
            <? endif; ?>
            <div class="total--price">
                <label style="font-weight: 700;">Итого</label>
                <label style="font-weight: 700;" class="price_delivery price"> <?= ($arResult['SUM_DISPLAY']) ?> Р. </label>
            </div>
            <div class="total--button">
                <input type="hidden" name="save" value="y">
                <button class="button_order" type="submit">Оформить заказ</button>
            </div>
        </div>
    </div>

</form>

<script>



</script>


<style>

</style>


<?
foreach ($arResult['DELIVERY'][$arResult['DELIVERY_ID']]['EXTRA_SERVICE'] as $key => $service)
{
    if($service->getValue() == 'Y') {
        $arrService[$key] = $service->getCode();
    }
}
$signer = new Main\Security\Sign\Signer;
$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.order.ajax');


?>
<script>
    BX.Sale.OrderAjaxComponent.init({
        ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/refresh.php')?>',
        siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
        signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
        params: <?=CUtil::PhpToJSObject($arParams)?>,
        service: <?=CUtil::PhpToJSObject($arrService)?>,
    });
</script>