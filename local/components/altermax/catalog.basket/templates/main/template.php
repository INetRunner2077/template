<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>


<section class="main-container col1-layout">
    <div class="main container">
        <div class="col-main">
            <div class="cart">

                <div class="page-content page-order">
                    <div class="page-title">
                        <h2 class="text-uppercase">Корзина</h2>
                    </div>


                    <div id="checkuot-bx-wrap" class="order-detail-content">
                        <div class="table-responsive">
                            <table class="table table-bordered cart_summary no-image-cart">
                                <thead>
                                <tr>
                                    <th class="text-left">Наименование</th>
                                    <th class="txt-trns-normal">Отгрузка, дней
                                    </th>
                                    <th class="txt-trns-normal">Доставка, дней
                                    </th>
                                    <th class="text-right txt-trns-normal">Цена,
                                        руб
                                    </th>
                                    <th class="ord-summ txt-trns-normal">Нужное
                                        к-во, шт.
                                    </th>
                                    <th class="text-right txt-trns-normal">
                                        Сумма, руб
                                    </th>
                                    <th class="action"><i
                                                class="fas fa-trash"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?
                                foreach ($arResult['BASKET'] as $id => $item):
                                    ?>
                                    <tr>
                                        <td class="cart_description">
                                            <p class="product-name">
                                                <a href="<?= $item['DETAIL_PAGE_URL'] ?>"
                                                   target="_blank"><?= $item['NAME'] ?></a>
                                            </p>
                                        </td>
                                        <td class="delivery-inf">
                                            3-7
                                        </td>
                                        <td class="delivery-inf">
                                            9
                                        </td>
                                        <td class="price text-right"><span>
                                                <?= $item['BASE_PRICE'] ?>
                                            </span>
                                        </td>
                                        <td class="qty">
                                            <input id="sum-inp-ch-<?= $id ?>"
                                                   form="cart-info-upd"
                                                   class="form-control input-sm input-sm numb-only ajax-numgds refresh-form-on-focus"
                                                   type="text"
                                                   name="ch_item[<?= $id ?>][qty]"
                                                   data-maxcount="<?= $item['MAX_QUANTITY'] ?>"
                                                   value="<?= $item['QUANTITY'] ?>">
                                        </td>
                                        <td class="price text-right">
                                            <span class="ajax-change-price"
                                                  data-oneprice="<?= $item['BASE_PRICE'] ?>"
                                                  data-inp="#sum-inp-ch-<?= $id ?>"
                                                  data-cur="р."
                                                  data-item="<?= $id ?>">
                                                  <?= $item['COST_ITEM'] ?>
                                            </span>
                                        </td>
                                        <td class="action">
                                            <a href="#" class="js-clear-this"
                                               data-item="<?= $id ?>"
                                               data-input="#sum-inp-ch-<?= $id ?>"
                                               data-parent="tr"><i
                                                        class="icon-close"></i></a>
                                        </td>
                                    </tr>
                                <?
                                endforeach; ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2" class="text-right"><strong>Итого,руб.:</strong>
                                    </td>
                                    <td class="text-right"><strong><span
                                                    class="hide-on-change"><?= $arResult['TOTAL_COST'] ?></span></strong>
                                    </td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="cart_navigation">
                            <a class="continue-btn" href="/"><i
                                        class="fa fa-arrow-left"> </i>&nbsp;
                                Продолжить покупки</a>
                            <form id="cart-info-upd" method="post"
                                  action="/checkout"
                                  class="hide-this to-order-ajax-form">
                                <input type="hidden" name="fnc-form"
                                       value="order_cart">
                                <input type="hidden" name="fnc-elem"
                                       value="update_cart">
                            </form>
                            <button type="submit" form="cart-info-upd"
                                    class="checkout-btn update-the-data-cart"
                                    href="#"><i class="fa fa-check"></i>
                                Обновить Данные
                            </button>
                            <a class="checkout-btn" href="<?=$arParams['BASKET_PAGE']?>"><i
                                        class="fa fa-check"></i> Оформить заказ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>