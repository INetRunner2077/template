/*  Главный скрипт обновления заказа */
BX.namespace('BX.Sale.OrderAjaxComponent');
(function() {
    BX.Sale.OrderAjaxComponent = {

        init: function(parameters)
        {
            this.signedParamsString = parameters.signedParamsString || {};
            this.ajaxUrl = parameters.ajaxUrl || '';
            this.siteId = parameters.siteID || '';
            this.params = parameters.params || '';
            if(this.params.DEFAULT_DELIVERY_ID != '') {
                $("#os-order-delivery-block2 :input[value=" + this.params.DEFAULT_DELIVERY_ID + "]").prop("checked", true);
            }
            if(this.params.DEFAULT_PAY_SYSTEM_ID != '') {
                $(".form-row-pay :input[value=" + this.params.DEFAULT_PAY_SYSTEM_ID + "]").prop("checked", true);
            }
            this.service = parameters.service || {};
            for (var key in this.service) {

                $(".order-section-delivery .service__info :input[name=" + this.service[key] + "]").prop("checked", true);
            };
            $('.person-type-item input:checked').parent().parent().css('border-color', '#8A221C');
            $('.pay__item input:checked').parent().css('border-color', '#8A221C');
            $('.delivery__item input:checked').parent().css('border-color', '#8A221C');
            if (this.params.DEFAULT_DELIVERY_ID == 2 || this.params.DEFAULT_DELIVERY_ID == 41) {
                $('#order-section-property-default .item input').prop('required', false);
            } else {
            $('#order-section-property-default .item input').prop('required', true);
            }
            if (this.params.DEFAULT_DELIVERY_ID == 41) {
                $('#order-section-property-pdp .item input').prop('required', true);
            }

        },
        loadStart: function (){

            /* Загрузка */
            let whiteWindow = $('#popup-window-overlay-loading_screen');
            let heart = $('#loading_screen');
            if (whiteWindow.css('display') === 'block') {
                whiteWindow.css("display", "none");
                heart.css("display", "none");
            } else {
                whiteWindow.css("display", "block");
                heart.css("display", "block");
            }
            /* Загрузка */
        },
        refresh: function (paramArray, select, load = true){


                this.loadStart();

            BX.ajax({
                url: this.ajaxUrl,
                method: 'POST',
                dataType: 'json',
                data: {
                    SITE_ID: this.siteId,
                    signedParamsString: this.signedParamsString,
                    param: paramArray,
                    location: $('.selectize-input .item').attr('data-value') || paramArray.LOCATION || '',
                },
                onsuccess: function(response) {

                    $('.area-total').remove();

                   total =   $('<div class="area-total">' +
                          '<div class="total--content">' +
                        '<div class="total--header">  <h4> Ваш заказ: </h4> </div>' +

                        '<div class="total--product">' +
                        '<label>Товаров на: </label>' +
                        '<div class="price--display">'+
                        '<span class="price">  '+ response.PRODUCTS_PRICE_DISPLAY +' Р. </span>' +
                        '</div>'  +
                        '</div>' +

                        '<div class="total--delivery">' +
                            '<label>Доставка: </label>' +
                            '<label class="price_delivery price"> '+ response.DELIVERY_PRICE_DISPLAY +' Р. </label>' +
                        '</div>' +

                       '<div style="display: none;" class="total--sale">' +
                       '<label>Экономия:</label>' +
                       '<label class="price_delivery price"> '+ response.PRODUCTS_DISCOUNT_DISPLAY +' Р. </label>' +
                       '</div>' +


                        '<div class="total--price">' +
                            '<label style="font-weight: 700;">Итого</label>' +
                            '<label style="font-weight: 700;" class="price_delivery price"> '+ response.SUM_DISPLAY +' Р. </label>' +
                        '</div>'+

                        '<div class="total--button">' +
                           '<input type="hidden" name="save" value="y">' +
                                '<button class="button_order" type="submit">Оформить заказ</button>' +
                        '</div>'+
                          '</div>' +
                        '</div>');

                    $('.os-order-form').append(total);


                    if(response.DELIVERY_PRICE == 'error') {
                        $('.os-order-form .total--delivery .price_delivery').addClass('error_delivery').text('Не расчитана');
                        $('.os-order-form .total--delivery .price_delivery').removeClass('price');
                    }

                    if(response.PRODUCTS_DISCOUNT_DISPLAY > 0 ) {
                      oldPrice =   $('<label class="old--price" style="text-decoration: line-through;"> '+ response.PRODUCTS_BASE_PRICE_DISPLAY +' Р. </label>')
                        $('.os-order-form .total--product .price--display').append(oldPrice);
                    }

                    if(response.PRODUCTS_DISCOUNT_DISPLAY > 0 ) {
                        $('.os-order-form .area-total .total--sale').css("display", "flex");
                    }




                    $('.order-section-delivery .delivery__item').remove();


                    

                    $.each(response.DELIVERY,function(index,value){

                        if(response.DELIVERY_ID == value.ID) {
                            CurrentDelivery = value;
                        }

                        deliveryItem = $('<div class="delivery__item">' +
                            '<img class="delivery-img" src="'+ value.IMG +'">' +
                            '<div class="dilivery--info">' +
                            '<label class="label"> '+ value.NAME +' </label>' +

                            '</div>'+
                            '<input id="delivery_id" type="radio" name="delivery_id" value="'+ value.ID+'">' +
                            '</div>'
                        );

                        if(value.PRICE == 'error') {

                            priceDel = '<label class="label-error-delivery">Не рассчитана</label>';

                            deliveryItem.find('.dilivery--info').append(priceDel)
                        } else {
                            priceDel = '<label class="price-delivery">'+ value.PRICE +' Р.</label>';

                            deliveryItem.find('.dilivery--info').append(priceDel)
                        }

                        $('.delivery__item__container').append(deliveryItem);

                        if(value.CHECKED == true) {
                            deliveryItem.find('#delivery_id').attr( 'checked', true );
                        }

                        if(value.ID == response.DELIVERY_ID) {
                            deliveryItem.find('#delivery_id').prop("checked", true);
                            deliveryItem.css('border-color', '#8A221C');
                        }


                    });

                    $('.info_container').remove();




                    if(CurrentDelivery?.PERIOD_TEXT != undefined) {
                        info = $('<div class="info_container">' +
                            '    <div class="img_container_delivery">' +
                            '        <img src="' + CurrentDelivery.IMG + '">' +
                            '    </div>' +
                            '    <div class="description">' + CurrentDelivery.DESCRIPTION + ' </div>' +
                            '    <div class="service__info">' +
                            '    </div>' +
                            '    <div class="period__text">' + CurrentDelivery.PERIOD_TEXT + '</div>' +
                            '</div>')
                    } else {
                        info = $('<div class="info_container">' +
                            '    <div class="img_container_delivery">' +
                            '        <img src="' + CurrentDelivery.IMG + '">' +
                            '    </div>' +
                            '    <div class="description">' + CurrentDelivery.DESCRIPTION + ' </div>' +
                            '    <div class="service__info">' +
                            '    </div>' +
                            '</div>')
                    }

                    if(CurrentDelivery?.EXTRA_SERVICE != undefined) {

                        $.each(CurrentDelivery.EXTRA_SERVICE,function(index,value){

                            service = $('<input type="checkbox" name="'+ value.CODE +'" value="'+ value.KEY +'" name="service__list">\n' +
                                '<label class="label"> "'+ value.NAME +'" </label>');

                            if(value.ACTIVE == 'Y') {
                                service.prop("checked", true);
                            } else {
                                service.prop("checked", false);
                            }
                            info.find('.service__info').append(service);

                        });



                    }


                    $('.delivery_info').append(info);



                    $('.order-section-pay .pay__item').remove();

                    $.each(response.PAY_SYSTEM_LIST,function(index,value){

                    payItem = $('<div class="pay__item">' +
                        '<input type="radio" name="pay_system_id" value="'+ value.ID +'">' +
                        '<label class="label"> '+ value.NAME +' </label>' +
                        '</div>');

                        $('.order_form .form-row-pay').append(payItem);

                        if(value.ID == response.PAY_SYSTEM_ID) {
                            payItem.find('input').prop("checked", true);
                            payItem.css('border-color', '#8A221C');
                        }

                    });

                    $('.order-section-error').remove();
                    
                    if(response.DELIVERY_PRICE == 'error') {
                       errorDel =  $(' <div class="order-section order-section-error"> ' +
                            '  <div class="order-section-body">' +
                            '            <div class="order-section-body">' +
                            '                <div class="form-row form-row-basket delivery_error">' +
                            '                    Не удалось рассчитать стоимость доставки.' +
                            '                    Вы можете продолжить оформление заказа, а чуть позже менеджер магазина свяжется с вами и уточнит информацию по доставке.' +
                            '                </div>' +
                            '            </div>' +
                            '        </div>'+
                            '      </div>'
                       );
                        $('.section_error_container').append(errorDel);

                    }
                        /* Загрузка */
                        let whiteWindow = $('#popup-window-overlay-loading_screen');
                        let heart = $('#loading_screen');
                        if (whiteWindow.css('display') === 'block') {
                            whiteWindow.css("display", "none");
                            heart.css("display", "none");
                        } else {
                            whiteWindow.css("display", "block");
                            heart.css("display", "block");
                        }
                        /* Загрузка */



                },
                onfailure: function(response) {
                }
            });


        },
    };
})();
/*  Главный скрипт обновления заказа */

/*  Скрипты страницы заказа */

$('.order-section-delivery').on('click', '.delivery__item', function (event) {

    $(this).find('#delivery_id').prop("checked", true);
    $('.delivery__item').css('border-color', '#cdd4d6');

    const myArray = {
        'DEFAULT_DELIVERY_ID': $(this).find('#delivery_id').val(),
        'DEFAULT_PAY_SYSTEM_ID': $('.form-row-pay input:checked').val(),
       // 'DEFAULT_PERSON_TYPE_ID': $('.person-type-selector input').val(),
    }

    BX.proxy(BX.Sale.OrderAjaxComponent.refresh(myArray, this))

    if($(this).find('#delivery_id').val() == 2) {

        $('#order-section-property-default').css("display", "none");
         $('#order-section-property-default .item input').prop('required', false);
        $('#order-section-property-pdp .item input').prop('required', false);
        $('#order-section-property-pdp').css("display", "none");

    } else if($(this).find('#delivery_id').val() == 41) {

        $('#order-section-property-default').css("display", "none");
        $('#order-section-property-default .item input').prop('required', false);
        $('#order-section-property-pdp').css("display", "block");
        $('#order-section-property-pdp .item input').prop('required', true);
    }
    else  {

        $('#order-section-property-default').css("display", "block");
        $('#order-section-property-default .item input').prop('required', true);
        $('#order-section-property-pdp .item input').prop('required', false);
        $('#order-section-property-pdp').css("display", "none");

    }

});

$('.order-section-delivery').on('click', '.service__info', function (event) {

    const myArray = {
        'DEFAULT_DELIVERY_ID': $('#os-order-delivery-block2 input:checked').val(),
        'DEFAULT_PAY_SYSTEM_ID': $('.form-row-pay input:checked').val(),
      //  'DEFAULT_PERSON_TYPE_ID': $('.person-type-selector input').val(),
        'SERVICE': [],
    }


    $.each($('.service__info input:unchecked'), function (i, serviceData) {

        myArray['SERVICE'][i] = serviceData.value;


    });
    BX.proxy(BX.Sale.OrderAjaxComponent.refresh(myArray, this))

});






$('.order-section-pay').on('click', '.pay__item', function (event) {

    $(this).find("input[name='pay_system_id']").prop("checked", true);
    $('.pay__item').css('border-color', '#cdd4d6');

    const myArray = {
        'DEFAULT_DELIVERY_ID': $('#os-order-delivery-block2 input:checked').val(),
        'DEFAULT_PAY_SYSTEM_ID': $(this).find("input[name='pay_system_id']").val(),
       // 'DEFAULT_PERSON_TYPE_ID': $('.person-type-selector input').val(),
        'SERVICE': [],
    }

    $.each($('.service__info input:unchecked'), function (i, serviceData) {

        myArray['SERVICE'][i] = serviceData.value;


    });

    BX.proxy(BX.Sale.OrderAjaxComponent.refresh(myArray, this))

    
    if($(this).find("input[name='pay_system_id']").val() == 8 ) {
        $('#order-section-property-organization').css("display", "block");
        $('#order-section-property-organization .item input').prop('required', true);
    } else {
        $('#order-section-property-organization').css("display", "none");
        $('#order-section-property-organization .item input').prop('required', false);
    }

});



$(document).ready(function () {

    (function () {
        var $container = $(document.getElementById('order-section-property-default'));

        var $region = $container.find('[name="PROPERTIES[REGION]"]'),
            $city = $container.find('[name="PROPERTIES[CITY]"]'),
            $street = $container.find('[name="PROPERTIES[STREET]"]'),
            $building = $container.find('[name="PROPERTIES[HOUSE]"]'),
            $address = $container.find('#address'),
            $zip = $container.find('#property_ZIP');


        $()
            .add($region)
            .add($city)
            .add($street)
            .add($building)
            .fias({
                parentInput: $container.find('.properties-table'),
                verify: true,
                change: function (obj) {


                    if (obj) {
                        setLabel($(this), obj.type);

                        if (obj.parents) {
                            $.fias.setValues(obj.parents, '.properties-table');
                        }
                    }

                    var $zipWrap = $zip.parent();
                    if (obj && obj.zip) {
                        $zip.val(obj.zip);
                        $zipWrap.show();
                    }//Обновляем поле zip



                    addressUpdate();
                },
                checkBefore: function () {
                    var $input = $(this);

                    if (!$.trim($input.val())) {
                        addressUpdate();
                        return false;
                    }
                }
            });

        $region.fias('type', $.fias.type.region);
        $city.fias('type', $.fias.type.city);
        $street.fias('type', $.fias.type.street);
        $building.fias('type', $.fias.type.building);

        // Включаем получение родительских объектов для населённых пунктов
        $city.fias('withParents', true);
        $street.fias('withParents', true);


        // Отключаем проверку введённых данных для строений
        $building.fias('verify', false);



        function setLabel($input, text) {
            text = text.charAt(0).toUpperCase() + text.substr(1).toLowerCase();
            $input.parent().find('label').text(text);
        }


        function addressUpdate() {
            var address = $.fias.getAddress($container.find('.properties-table'));

            $address.text(address);
        }

    })();


    $('#properties-table .item input[name="PROPERTIES[CITY]"]').on( "change", function() {

        if($('.location .selectize-input .item').text().replace(/[\s.]/g, '') == $('#properties-table .item input[name="PROPERTIES[CITY]"]').val()) {
            return;
        }

        var query = {
            c: 'opensource:order.new',
            action: 'locationBitrixKlav',
            mode: 'ajax'
        };

        var request = $.ajax({
            url: '/bitrix/services/main/ajax.php?' + $.param(query, true),
            type: 'POST',
            data: {city: $(this).val()}
        });

        request.done(function (result) {

            $('.location-search')[0].selectize.setValue(result.data.CODE,true)

        });
        request.fail(function (data) {
            $('.location-search')[0].selectize.addOption({code:data.responseJSON.data, label:data.responseJSON.data})
            $('.location-search')[0].selectize.setValue(data.responseJSON.data,true);
        });




} );


    var $locationSearch = $('.location-search');

    $locationSearch.selectize({
        valueField: 'code',
        labelField: 'label',
        searchField: 'label',
        placeholder  : "Начните вводить город",
        render: {
            option: function (item, escape) {
                return '<div class="title">' + escape(item.label) + '</div>';
            }
        },
        onFocus: function () {
            var control = $('.location-search')[0].selectize; control.clear();
        },
        onItemAdd: function (value, $item) {

            const myArray = {
                'DEFAULT_DELIVERY_ID': $('#os-order-delivery-block2 input:checked').val(),
                'DEFAULT_PAY_SYSTEM_ID': $('.form-row-pay input:checked').val(),
              //  'DEFAULT_PERSON_TYPE_ID': $('.person-type-selector input').val(),
                'SERVICE': [],
                'LOCATION': value,
            }

            $.each($('.service__info input:unchecked'), function (i, serviceData) {

                myArray['SERVICE'][i] = serviceData.value;


            });

            BX.proxy(BX.Sale.OrderAjaxComponent.refresh(myArray, this))
        },

    });


    $( "#os-order-form" ).submit(function( event ){
        event.preventDefault();
        var formData = $('#os-order-form').serializeArray();

        var query = {
            c: 'opensource:order.new',
            action: 'saveOrder',
            mode: 'ajax'
        };


        $.each($('.service__info input:unchecked'), function (i, serviceData) {
            formData.push({ name: `service[${i}]`, value: serviceData.value });
        });


        var request = $.ajax({
            url: '/bitrix/services/main/ajax.php?' + $.param(query, true),
            type: 'POST',
            data: formData
        });

        /* Загрузка */
        let whiteWindow = $('#popup-window-overlay-loading_screen');
        let heart = $('#loading_screen');
        if (whiteWindow.css('display') === 'block') {
            whiteWindow.css("display", "none");
            heart.css("display", "none");
        } else {
            whiteWindow.css("display", "block");
            heart.css("display", "block");
        }
        /* Загрузка */

        request.done(function (result) {

            if(result.status == 'error') {

                for (var key in result.data) {

                    $errorLabel = $('[data-value="' + result.data[key].code + '"]').text(result.data[key].message);
                    $errorLabel.css('display', 'block');
                }

                for (var key in result.errors) {
                    if(result.errors[key].message == 'EMAIL_EXIST') {

                        var error =  $('<div class="order-section-body">' +
                            '<div class="order-section-body">' +
                            '<div class="form-row form-row-basket">  </div>' +
                            '</div>' +
                            '</div>');
                        $('.section-email').css('display', 'block');
                        $('.section-email').append(error);

                    }
                }



                /* Загрузка */
                let whiteWindow = $('#popup-window-overlay-loading_screen');
                let heart = $('#loading_screen');
                if (whiteWindow.css('display') === 'block') {
                    whiteWindow.css("display", "none");
                    heart.css("display", "none");
                } else {
                    whiteWindow.css("display", "block");
                    heart.css("display", "block");
                }
                /* Загрузка */


            } else {

                location.href = window.location.protocol+"//"+window.location.hostname+"/order/?ORDER_ID="+result.data.order_id;

            }

        });

    });


});
