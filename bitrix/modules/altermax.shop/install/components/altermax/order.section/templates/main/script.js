jQuery(document).ready(function() {

    $( ".del_order" ).click(function() {

    var data = {}
    data.action = 'DelOrder';
    data.orderId = $(this).data('orderid');

    $.ajax({
        type: "POST",
        url: "/ajax/basketAjax.php",
        data: data,
        dataType: "json",
        success: function (data) {
            if (data.STATUS == 'ERROR') {
              console.log('ERROR');
            }
            if (data.STATUS == 'OK') {
                $('.del_order[data-orderid= "' + data.ORDER_ID + '"]').parents('.first').remove();
            }
        }
    });

    });


    BX.bindDelegate(BX('order_area'), 'click', { 'class': 'ajax_reload' }, BX.proxy(function(event)
    {
        event.preventDefault();

        BX.ajax(
            {
                method: 'POST',
                dataType: 'html',
                url: event.target.href,
                data:
                    {
                        sessid: BX.bitrix_sessid(),
                        RETURN_URL: event.target.dataset.return,
                    },
                onsuccess: BX.proxy(function(result)
                {
                    $('.payment_area').css('display','block');
                    $('.payment_area').empty()
                    $('.payment_area').append(result);

                },this),
                onfailure: BX.proxy(function()
                {
                    return this;
                }, this)
            }, this
        );
    }, this));


});