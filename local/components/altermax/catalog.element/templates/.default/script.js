BX.namespace('BX.Sale.ItemComponent');
(function() {
    BX.Sale.ItemComponent = {

        BasketUrl : '',
        siteId : '',
        maxValue: '',
        iblockId: '',
        itemId: '',
        find: '',

        init: function(parameters)
        {
            this.BasketUrl = parameters.BasketUrl || '';
            this.siteId = parameters.siteID || '';
            this.maxValue = parameters.maxValue || '';
            this.iblockId = parameters.iblockId || '';
            this.itemId = parameters.itemId || '';
            this.find = parameters.find || '';

            $('#countMain').data('item', this.itemId);
            $('#countMain').data('count', 1);
            $('#buttonMain').data('item', this.itemId);

            var ctx = this;

            BX(function(){
            ctx.buttonItemAjax();
            });

            if(this.find == 'Y') {
              ctx.finder();
           }

            BX.bind(BX('in_basket'), 'click', function() {ctx.inCart($(this), true)});

            BX.bind(BX('quant_up'), 'click', function() {ctx.quantUp($(this),true)});

            BX.bind(BX('quant_down'), 'click', function() {ctx.quantDown($(this),true)});

            BX.bind(BX('main_counter'), 'change', function() {ctx.maxElement($(this), true)});

            BX.bindDelegate(BX('products-list'), 'change', {className: 'other_counter'}, function() {ctx.maxElement($(this),false)});

            BX.bindDelegate(BX('products-list'), 'click', {className: 'by-one-click'}, function() {ctx.OneClickBuy($(this));});

            BX.bindDelegate(BX('products-list'), 'click', {className: 'plus'}, function() {ctx.quantUp($(this),false)});

            BX.bindDelegate(BX('products-list'), 'click', {className: 'minus'}, function() {ctx.quantDown($(this),false)});

            BX.bindDelegate(BX('products-list'), 'click', {className: 'in_basket'}, function() {ctx.inCart($(this), false)});

            BX.bindDelegate(BX('page'), 'click', {className: 'quick_view_popup-close'}, function() {ctx.deleteOneClick($(this))});

        },

        deleteOneClick: function () {
            $('#quick_view_popup-overlay').remove();
            $('#quick_view_popup-wrap').remove();
        },

        OneClickBuy: function (e) {

            var count = $(e).data('count');
            var itemId = $(e).data('item-onebuy');
            var name = $(e).data('name');
            var price = $(e).data('price');

            var popup = $('<div id="quick_view_popup-wrap" style="display: block; top: 222px;">\n' +
                '        <div id="quick_view_popup-outer">\n' +
                '            <div id="quick_view_popup-content">\n' +
                '                <div style="width:auto;height:auto;overflow: auto;position:relative;">\n' +
                '                    <div class="product-view-area">\n' +
                '                        <div class="col-md-12 col-sm-12  col-xs-12"><p\n' +
                '                                    class="h3">Купить в один клик</p>\n' +
                '                            <form method="post" class="one-click-send_ajax">\n' +
                '                            <input type="hidden" name="id_oneClick" value="165905193"> \n' +
                '                              <label>Название</label>\n' +
                '                                <input\n' +
                '                                        type="text" class="form-control input"\n' +
                '                                        name="name" id="name_oneClick" value=""\n' +
                '                                        readonly="">\n' +
                '                                <label>Цена</label>\n' +
                '                                <input\n' +
                '                                        type="text" class="form-control input"\n' +
                '                                        name="price" id="price_oneClick" value="187.50" readonly="">\n' +
                '                                <label>Количество</label>\n' +
                '                                <input\n' +
                '                                        type="number" min="0" step="1"\n' +
                '                                        class="form-control input"\n' +
                '                                        name="quantity" id="count_oneClick" value="1">\n' +
                '                                <label>Полное имя*</label>\n' +
                '                                <input type="text"\n' +
                '                                                       class="form-control input"\n' +
                '                                                       name="your-name" value=""\n' +
                '                                                       required="">\n' +
                '                                <label>Email*</label>\n' +
                '                                <input\n' +
                '                                        type="email" class="form-control input"\n' +
                '                                        name="email" value=""\n' +
                '                                        required="">\n' +
                '                                <label>Телефон</label>\n' +
                '                                <input\n' +
                '                                        type="text"\n' +
                '                                        class="form-control input phonemask"\n' +
                '                                        name="phone" value="">\n' +
                '                                <br>\n' +
                '                                <button class="button" type="submit"><i\n' +
                '                                            class="fas fa-paper-plane"></i>&nbsp;\n' +
                '                                    <span>Отправить</span></button>\n' +
                '                            </form>\n' +
                '                        </div>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '            <a style="display: inline;" class="quick_view_popup-close" id="quick_view_popup-close" href="#"><i\n' +
                '                        class="icon pe-7s-close"></i></a></div>\n' +
                '    </div>');

            popup.find('#name_oneClick').val(name);
            popup.find('#price_oneClick').val(price);
            popup.find('#count_oneClick').val(count);
            popup.find('#id_oneClick').val(itemId);

            $('#page').append($('<div id="quick_view_popup-overlay"></div>'));
            $('#page').append(popup);


        },

        maxElement: function (e, main) {

            if(main == true) {
               var max = this.maxValue;
            } else {
               var max = e.parent().data('quantity');
            }

            if(e.val() > max) {
                e.val(max);
            }
        },

        quantUp: function (e, main) {
            if (!$(this).parents('.basket_wrapp').length) {
                if ($(e).parent().data("offers") != "Y") {

                    if(main == true) {
                        var max = this.maxValue;
                    } else {
                        var max = $(e).parent().data('quantity');
                    }

                    data = {};
                    data.STATUS = 'NO';
                    this.updateButton(data)

                    var input_val = $(e).parent().find('input[name="quantity"]');
                    var tmp_val =  parseInt(input_val.val(), 10);
                    var new_val = tmp_val + 1;
                    if(new_val == 0) { new_val = 1; }
                    if(new_val > max) { new_val = max }
                    input_val.val(new_val);
                    if(main == true) {
                       var itemId = $(e).parent().data('item');
                       var input = $('button[data-item=' + itemId + ']');
                       input.data('count', new_val);
                    } else {
                        var itemId = $(e).parent().data('item');
                        var input = $('button[data-item=' + itemId + ']');
                        input.data('count', new_val);
                    }
                }
            }
        },

        quantDown: function (e, main) {
            if (!$(this).parents('.basket_wrapp').length) {
                if ($(e).parent().data("offers") != "Y") {

                    if(main == true) {
                        var max = this.maxValue;
                    } else {
                        var max = $(e).parent().data('quantity');
                    }

                    data = {};
                    data.STATUS = 'NO';
                    this.updateButton(data)

                    var input_val = $(e).parent().find('input[name="quantity"]');
                    var tmp_val =  parseInt(input_val.val(), 10);
                    var new_val = tmp_val - 1;
                    if(new_val == 0) { new_val = 1; }
                    if(new_val > max) { new_val = max }
                    input_val.val(new_val);
                    if(main == true) {
                        var itemId = $(e).parent().data('item');
                        var input = $('button[data-item=' + itemId + ']');
                        input.data('count', new_val);
                    } else {
                        var itemId = $(e).parent().data('item');
                        var input = $('button[data-item=' + itemId + ']');
                        input.data('count', new_val);
                    }
                }
            }
        },

        finder: function (paramArray){

            finder = {};

            finder.name = $('.product-name h1').text();
            finder.iblock = this.iblockId;
            finder.itemId = this.itemId;

            $.ajax({
                type: "POST",
                url: "/ajax/offer.php",
                data: finder,
                dataType: "json",
                success: function (data) {

                    console.log(data);
                    var offerBlock = $('#OtherProduct #products-list');
                    for (var key in data) {
                        var item =  $('<li class="item no-image">\n' +
                            '                                <div class="product-shop">\n' +
                            '                                    <table class="table table-bordered cart_summary">\n' +
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
                            '                                                \n' +
                            '                                            </td>\n' +
                            '                                            <td class="text-right"><strong><span\n' +
                            '                                                            class="ajax-change-price"\n' +
                            '                                                            data-inp="#sum-inp-ch-frm-add_to-order-165349477"\n' +
                            '                                                            data-cur="р.">\n' +
                            '                                                                        4.20</span></strong>\n' +
                            '                                            </td>\n' +
                            '                                            <td class="ord-summ">\n' +
                            '                                           <div class="counter_wrapp">\n' +
                            '<div class="counter_block big_basket" data-item="" data-quantity="">\n' +
                            '<span class="minus" id="quant_down">-</span>\n' +
                            '<input type="text" class="text other_counter" id="other_counter" name="quantity" value="1">\n' +
                            '<span class="plus" id="quant_up">+</span>\n' +
                            '</div>\n' +
                            '</div> \n' +
                            '                                            </td>\n' +
                            '                                            <td class="action">\n' +

                            '                                                <button data-item="" data-count="" \n' +
                            '                                                        class="button cart-request button-green in_basket"\n' +
                            '                                                        title="В корзину"\n' +
                            '                                                        type="submit"><i\n' +
                            '                                                            class="fa fa-shopping-basket"></i><span>Купить</span>\n' +
                            '                                                </button>\n' +
                            '                                                <button data-price="" data-name="" data-item-onebuy="" data-count="" \n' +
                            '                                                        class="button by-one-click"\n' +
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
                        item.find('.availability').text(data[key].QUANTITY);
                        item.find('.ajax-change-price').text(data[key].PRICE);
                        item.find('.counter_block').data('item', key);
                        item.find('.counter_block').data('quantity', data[key].QUANTITY);
                        item.find('.in_basket').data('item', key);
                        item.find('.by-one-click').data('item-onebuy', key);
                        item.find('.in_basket').data('count', 1);
                        item.find('.by-one-click').data('count', 1);
                        item.find('.by-one-click').data('name', data[key].TITLE);
                        item.find('.by-one-click').data('price', data[key].PRICE);
                        item.find('.in_basket').attr('data-item', key);



                        if(data[key].BASKET == true) {
                            var button =   $('<div class="product-item-detail-info-container in-basket">\n' +
                                '<a href="#" id="to_basket" title="В корзине">\n' +
                                '<i class="fa fa-shopping-basket"></i>\n' +
                                '<span> В корзине </span>\n' +
                                '</a>\n' +
                                '</div>');
                            button.find('#to_basket').attr('href', this.BasketUrl);
                            item.find('button[data-item=' + key + ']').replaceWith(button);

                        }

                        $('#related-product-area').css('display', 'block');
                        $('#OtherProduct').css('display', 'block');
                        offerBlock.append(item);
                    }

                }
            });

        },

        inCart: function (e, main) {

            if(main == true) {
                var data = {};
                data.quantity = $(e).data('count');
                data.add_item = "Y";
                data.item = e.data('item');
                this.BasketUrl;
                $.ajax({
                    type: "POST",
                    url: "/ajax/item.php",
                    data: data,
                    dataType: "json",
                    success: function (data) {
                        $('#minicart-ajax-rfsh').trigger('refreshcart');
                        BX.Sale.ItemComponent.updateButton(data);
                    }
                });
            }
            if( main == false) {
                var data = {};
                data.quantity = $(e).data('count');
                data.add_item = "Y";
                data.item = e.data('item');
                this.BasketUrl;
                $.ajax({
                    type: "POST",
                    url: "/ajax/item.php",
                    data: data,
                    dataType: "json",
                    success: function (data) {
                        data.STATUS = 'MINI_CART';
                        $('#minicart-ajax-rfsh').trigger('refreshcart');
                        BX.Sale.ItemComponent.updateButton(data);
                    }
                });
            }

        },

        buttonItemAjax: function () {

            var data = {};
            data.button_item = "Y";
            data.item = this.itemId;
            this.BasketUrl;
            $.ajax({
                type: "POST",
                url: "/ajax/item.php",
                data: data,
                dataType: "json",
                success: function (data) {
                    BX.Sale.ItemComponent.updateButton(data);
                }
            });
        },

        updateButton : function (data) {
            if(data.STATUS == "OK") {
                $('.product-cart-buy .counter_wrapp').css('display', 'block');
                $('.product-cart-buy .button_wrap').css('display', 'none');
                $('.product-cart-buy .in-basket').css('display', 'block');
            }
            if(data.STATUS == "NO") {
                $('.product-cart-buy .counter_wrapp').css('display', 'block');
                $('.product-cart-buy .button_wrap').css('display', 'block');
                $('.product-cart-buy .in-basket').css('display', 'none');
            }
            if(data.STATUS == "MINI_CART") {
                var button =   $('<div class="product-item-detail-info-container in-basket">\n' +
                    '<a href="#" id="to_basket" title="В корзине">\n' +
                    '<i class="fa fa-shopping-basket"></i>\n' +
                    '<span> В корзине </span>\n' +
                    '</a>\n' +
                    '</div>');
                button.find('#to_basket').attr('href', this.BasketUrl);
                $('button[data-item=' + data.ITEM_ID + ']').replaceWith(button);
            }
        },

    };
})();