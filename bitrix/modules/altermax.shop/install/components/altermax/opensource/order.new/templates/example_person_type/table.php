<h2><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_BASKET_TITLE') ?></h2>
<table>
    <tr>
        <th><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_BASKET_NAME_COLUMN') ?></th>
        <th><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_BASKET_COUNT_COLUMN') ?></th>
        <th><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_BASKET_UNIT_PRICE_COLUMN') ?></th>
        <th><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_BASKET_DISCOUNT_COLUMN') ?></th>
        <th><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_BASKET_TOTAL_COLUMN') ?></th>
    </tr>
    <? foreach ($arResult['BASKET'] as $arBasketItem): ?>
        <tr>
            <td>
                <?= $arBasketItem['NAME'] ?>
                <? if (!empty($arBasketItem['PROPERTIES'])): ?>
                    <div class="basket-properties">
                        <? foreach ($arBasketItem['PROPERTIES'] as $arProp): ?>
                            <?= $arProp['NAME'] ?>
                            <?= $arProp['VALUE'] ?>
                            <br>
                        <? endforeach; ?>
                    </div>
                <? endif; ?>
            </td>
            <td><?= $arBasketItem['QUANTITY_DISPLAY'] ?></td>
            <td><?= $arBasketItem['BASE_PRICE_DISPLAY'] ?></td>
            <td><?= $arBasketItem['PRICE_DISPLAY'] ?></td>
            <td><?= $arBasketItem['SUM_DISPLAY'] ?></td>
        </tr>
    <? endforeach; ?>
</table>

<h2><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_ORDER_TOTAL_TITLE') ?></h2>
<h3><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_PRODUCTS_PRICES_TITLE') ?>:</h3>
<table>
    <tr>
        <td><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_PRODUCTS_BASE_PRICE') ?></td>
        <td><?= $arResult['PRODUCTS_BASE_PRICE_DISPLAY'] ?></td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_PRODUCTS_PRICE') ?></td>
        <td><?= $arResult['PRODUCTS_PRICE_DISPLAY'] ?></td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_PRODUCTS_DISCOUNT') ?></td>
        <td><?= $arResult['PRODUCTS_DISCOUNT_DISPLAY'] ?></td>
    </tr>
</table>

<h3><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_DELIVERY_PRICES_TITLE') ?>:</h3>
<table>
    <tr>
        <td><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_DELIVERY_BASE_PRICE') ?></td>
        <td><?= $arResult['DELIVERY_BASE_PRICE_DISPLAY'] ?></td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_DELIVERY_PRICE') ?></td>
        <td class="price_delivery"><?= $arResult['DELIVERY_PRICE_DISPLAY'] ?></td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_DELIVERY_DISCOUNT') ?></td>
        <td><?= $arResult['DELIVERY_DISCOUNT_DISPLAY'] ?></td>
    </tr>
</table>

<h3><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_SUM_TITLE') ?>:</h3>
<table>
    <tr>
        <td><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_TOTAL_BASE_PRICE') ?></td>
        <td><?= $arResult['SUM_BASE_DISPLAY'] ?></td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_TOTAL_DISCOUNT') ?></td>
        <td><?= $arResult['DISCOUNT_VALUE_DISPLAY'] ?></td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('OPEN_SOURCE_ORDER_TEMPLATE_TOTAL_PRICE') ?></td>
        <td class="summ"><?= $arResult['SUM_DISPLAY'] ?></td>
    </tr>
</table>