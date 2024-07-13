BX.namespace('BX.Sale.ItemComponent');
(function() {
    BX.Sale.ItemComponent = {

        BasketUrl : '',
        siteId : '',

        init: function(parameters)
        {
            this.BasketUrl = parameters.BasketUrl || '';
            this.siteId = parameters.siteID || '';

            var ctx = this;
            BX(function(){
                ctx.finder();
            });

            BX.bind(BX('in_basket'), 'click', function() {ctx.inCart()});

        },
        finder: function (paramArray){

            finder = {};

            finder.name = $('.product-name h1').text();
            $.ajax({
                type: "POST",
                url: "/ajax/offer.php",
                data: finder,
                dataType: "json",
                success: function (data) {

                    console.log(data);
                    var offerBlock = $('#productTabContent #products-list');
                    for (var key in data) {
                        var item =  $('<li class="item no-image">\n' +
                            '                                <div class="product-shop">\n' +
                            '                                    <table class="table table-bordered cart_summary slim-order jq-sortertable_order_short">\n' +
                            '                                        <thead class="valign-middle">\n' +
                            '                                        <tr>\n' +
                            '                                            <th style="width:20%;"\n' +
                            '                                                class="header">Наименование\n' +
                            '                                            </th>\n' +
                            '                                            <th class="txt-trns-normal header">\n' +
                            '                                                Отгрузка, дней\n' +
                            '                                            </th>\n' +
                            '                                            <th class="txt-trns-normal header">\n' +
                            '                                                Наличие, шт.\n' +
                            '                                            </th>\n' +
                            '                                            <th class="text-right txt-trns-normal header headerSortDown">\n' +
                            '                                                Цена, руб\n' +
                            '                                            </th>\n' +
                            '                                            <th class="ord-summ txt-trns-normal">\n' +
                            '                                                Нужное к-во, шт.\n' +
                            '                                            </th>\n' +
                            '                                            <th class="action">\n' +
                            '                                                <i class="fa fa-shopping-basket"></i>\n' +
                            '                                            </th>\n' +
                            '                                        </tr>\n' +
                            '                                        </thead>\n' +
                            '                                        <tbody>\n' +
                            '\n' +
                            '                                        <tr>\n' +
                            '                                            <td class="cart_description">\n' +
                            '                                                <p class="product-name"> "" </p>\n' +
                            '                                            </td>\n' +
                            '                                            <td class="store-td">\n' +
                            '                                                4-6\n' +
                            '                                            </td>\n' +
                            '                                            <td class="availability">\n' +
                            '                                                5161\n' +
                            '                                            </td>\n' +
                            '                                            <td class="text-right"><strong><span\n' +
                            '                                                            class="ajax-change-price"\n' +
                            '                                                            data-oneprice="4,2"\n' +
                            '                                                            data-inp="#sum-inp-ch-frm-add_to-order-165349477"\n' +
                            '                                                            data-cur="р.">\n' +
                            '                                                                        4.20</span></strong>\n' +
                            '                                            </td>\n' +
                            '                                            <td class="ord-summ">\n' +
                            '                                                <input form="frm-add_to-order-165349477"\n' +
                            '                                                       id="sum-inp-ch-frm-add_to-order-165349477"\n' +
                            '                                                       class="form-control input-sm numb-only"\n' +
                            '                                                       type="number" name="summ"\n' +
                            '                                                       value="1">\n' +
                            '                                            </td>\n' +
                            '                                            <td class="action">\n' +
                            '                                                <form class="product-add hide-this to-order-ajax-form"\n' +
                            '                                                      id="frm-add_to-order-165349477"\n' +
                            '                                                      action="/item/DB4"\n' +
                            '                                                      method="post">\n' +
                            '                                                    <input type="hidden"\n' +
                            '                                                           name="fnc-form"\n' +
                            '                                                           value="order_cart">\n' +
                            '                                                    <input type="hidden"\n' +
                            '                                                           name="fnc-elem"\n' +
                            '                                                           value="product_cartadd">\n' +
                            '                                                    <input type="hidden"\n' +
                            '                                                           name="id"\n' +
                            '                                                           value="165349477">\n' +
                            '                                                    <input type="hidden"\n' +
                            '                                                           name="name"\n' +
                            '                                                           value="DB4">\n' +
                            '                                                    <input type="hidden"\n' +
                            '                                                           name="provider_id"\n' +
                            '                                                           value="2">\n' +
                            '                                                    <input type="hidden"\n' +
                            '                                                           name="price"\n' +
                            '                                                           value="4.20">\n' +
                            '                                                </form>\n' +
                            '                                                <button form="frm-add_to-order-165349477"\n' +
                            '                                                        class="button cart-button button-green"\n' +
                            '                                                        title="В корзину"\n' +
                            '                                                        type="submit"><i\n' +
                            '                                                            class="fa fa-shopping-basket"></i><span>Купить</span>\n' +
                            '                                                </button>\n' +
                            '                                                <form class="product-add hide-this to-order-ajax-form"\n' +
                            '                                                      id="frm-by-one-click-165349477"\n' +
                            '                                                      action="/item/DB4"\n' +
                            '                                                      method="post">\n' +
                            '                                                    <input type="hidden"\n' +
                            '                                                           name="fnc-form"\n' +
                            '                                                           value="order_cart">\n' +
                            '                                                    <input type="hidden"\n' +
                            '                                                           name="fnc-elem"\n' +
                            '                                                           value="product_toclick">\n' +
                            '                                                    <input type="hidden"\n' +
                            '                                                           name="id"\n' +
                            '                                                           value="165349477">\n' +
                            '                                                    <input type="hidden"\n' +
                            '                                                           name="name"\n' +
                            '                                                           value="DB4">\n' +
                            '                                                    <input type="hidden"\n' +
                            '                                                           name="provider_id"\n' +
                            '                                                           value="2">\n' +
                            '                                                    <input type="hidden"\n' +
                            '                                                           name="price"\n' +
                            '                                                           value="4.20">\n' +
                            '                                                </form>\n' +
                            '                                                <button form="frm-by-one-click-165349477"\n' +
                            '                                                        class="button cart-button by-one-click"\n' +
                            '                                                        type="submit">\n' +
                            '                                                    <i class="fa fa-shopping-cart"></i><span>Заявка</span>\n' +
                            '                                                </button>\n' +
                            '                                            </td>\n' +
                            '                                        </tr>\n' +
                            '                                        </tbody>\n' +
                            '                                        <tfoot>\n' +
                            '                                        <tr>\n' +
                            '                                            <td colspan="7"></td>\n' +
                            '                                        </tr>\n' +
                            '                                        </tfoot>\n' +
                            '                                    </table>\n' +
                            '                                </div><!-- .product-shop -->\n' +
                            '                            </li>')
                        item.find('.cart_description .product-name').text(data[key].TITLE);
                        offerBlock.append(item);
                    }

                }
            });

        },

        inCart: function () {

            var data = {};
            data.quantity = $('.counter_block input[name = "quantity"]').val();
            data.add_item = "Y";
            data.item = $('.counter_wrapp .counter_block').attr('data-item');

           this.BasketUrl;
            $.ajax({
                type: "POST",
                url: "/ajax/item.php",
                data: data,
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    BX.Sale.ItemComponent.updateButton(data);
                }
            });


        },

        updateButton : function (data) {
            if(data.STATUS = "OK") {
                $('.button_wrap .in-cart-button').remove();

                var button =   $('<div class="product-item-detail-info-container in-basket">\n' +
                    '<a href="#" class="button-green" id="to_basket" title="В корзине">\n' +
                    '<i class="fa fa-shopping-basket"></i>\n' +
                    '<span> В корзине </span>\n' +
                    '</a>\n' +
                    '</div>');
                button.find('#to_basket').attr('href', this.BasketUrl);
                $('.button_wrap').append(button);
            }
        },

    };
})();

BX.ready(function () {

    $(document).on("click", ".counter_block:not(.basket) .plus", function () {
        if (!$(this).parents('.basket_wrapp').length) {
            if ($(this).parent().data("offers") != "Y") {
                var isDetailSKU2 = $(this).parents('.counter_block_wr').length,
                    input = $(this).parents(".counter_block").find("input[type=text]"),
                    tmp_ratio = !isDetailSKU2 ? $(this).parents(".counter_wrapp").find(".to-cart").data('ratio') : $(this).parents('.counter_block_wr').find(".button_block .to-cart").data('ratio'),
                    isDblQuantity = !isDetailSKU2 ? $(this).parents(".counter_wrapp").find(".to-cart").data('float_ratio') : $(this).parents('.counter_block_wr').find(".button_block .to-cart").data('float_ratio'),
                    ratio = (isDblQuantity ? parseFloat(tmp_ratio) : parseInt(tmp_ratio, 10)),
                    max_value = '';
                currentValue = input.val();

                if (isDblQuantity)
                    ratio = Math.round(ratio * 1000000) / 1000000;

                curValue = (isDblQuantity ? parseFloat(currentValue) : parseInt(currentValue, 10));

                curValue += ratio;
                if (isDblQuantity) {
                    curValue = Math.round(curValue * 1000000) / 1000000;
                }
                if (parseFloat($(this).data('max')) > 0) {
                    if (input.val() <= $(this).data('max')) {
                        if (curValue <= $(this).data('max'))
                            input.val(curValue);

                        input.change();
                    }
                } else {
                    input.val(curValue);
                    input.change();
                }
            }
        }
    });

    $(document).on("click", ".counter_block:not(.basket) .minus", function () {
        if (!$(this).parents('.basket_wrapp').length) {
            if ($(this).parent().data("offers") != "Y") {
                var isDetailSKU2 = $(this).parents('.counter_block_wr').length;
                input = $(this).parents(".counter_block").find("input[type=text]")
                tmp_ratio = !isDetailSKU2 ? $(this).parents(".counter_wrapp").find(".to-cart").data('ratio') : $(this).parents('.counter_block_wr').find(".button_block .to-cart").data('ratio'),
                    isDblQuantity = !isDetailSKU2 ? $(this).parents(".counter_wrapp").find(".to-cart").data('float_ratio') : $(this).parents('.counter_block_wr').find(".button_block .to-cart").data('float_ratio'),
                    ratio = (isDblQuantity ? parseFloat(tmp_ratio) : parseInt(tmp_ratio, 10)),
                    max_value = '';
                currentValue = input.val();

                if (isDblQuantity)
                    ratio = Math.round(ratio * 1000000) / 1000000;

                curValue = (isDblQuantity ? parseFloat(currentValue) : parseInt(currentValue, 10));

                curValue -= ratio;
                if (isDblQuantity) {
                    curValue = Math.round(curValue * 1000000) / 1000000;
                }

                if (parseFloat($(this).parents(".counter_block").find(".plus").data('max')) > 0) {
                    if (currentValue > ratio) {
                        if (curValue < ratio) {
                            input.val(ratio);
                        } else {
                            input.val(curValue);
                        }
                        input.change();
                    }
                } else {
                    if (curValue > ratio) {
                        input.val(curValue);
                    } else {
                        if (ratio) {
                            input.val(ratio);
                        } else if (currentValue > 1) {
                            input.val(curValue);
                        }
                    }
                    input.change();
                }
            }
        }
    });

});
