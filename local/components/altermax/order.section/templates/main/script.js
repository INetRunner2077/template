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

});