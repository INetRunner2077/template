<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
?>
<?
$APPLICATION->IncludeComponent(
    "altermax:slider.altermax",
    "slider",
    array(
        "GROUPS"      => "",
        "IBLOCK_ID"   => "41",
        "IBLOCK_TYPE" => "advertising",
    )
); ?>


<div class="mini-cart slim-cart">
    <div data-toggle="dropdown" data-hover="dropdown"
         class="basket dropdown-toggle">
        <a href="/checkout">
            <div class="cart-icon">
                <i class="icon-basket-loaded icons"></i>
                <span class="cart-total">2</span></div>
            <div class="shoppingcart-inner hidden-xs"><span
                        class="cart-title text-uppercase">Корзина</span></div>
        </a>
    </div>
    <div>
        <div class="top-cart-content">
            <div class="block-subtitle hidden">Недавно добавленные товары</div>
            <ul id="cart-sidebar" class="mini-products-list">



                <li class="item odd">
                    <a href="#" title="кт209е" class="product-image">
                        <i class="fa fa-info"></i>
                    </a>
                    <div class="product-details">
                        <p class="product-name"><a
                                    href="/item/%D0%BA%D1%82209%D0%B5"
                                    target="_blank">кт209е</a></p>
                        <strong>1</strong> x <span class="price">24.18</span> =
                        <span class="price">24.18 <span
                                    class="lwr-case">р.</span></span>
                    </div>
                </li>

            </ul>
            <div class="top-subtotal"><span class="text-uppercase">Итого</span>,
                руб: <span class="price">35.88</span></div>
            <div class="actions">
                <button class="btn-checkout" type="button" onClick=""><i
                            class="fa fa-check"></i> <span>Оформить</span>
                </button>
                <button class="view-cart" type="button" onClick=""><i
                            class="fa fa-shopping-cart"></i>
                    <span>Изменить</span></button>
            </div>
        </div>
    </div>
</div><!-- .mini-cart -->
