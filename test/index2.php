<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация");
?>


    <div class="counter_wrapp">
        <div class="counter_block big_basket" data-offers="N" data-item="100406" style="/* display: none; */">
            <span class="minus" id="bx_117848907_100406_quant_down">-</span>
            <input type="text" class="text" id="bx_117848907_100406_quantity" name="quantity" value="1">
            <span class="plus" id="bx_117848907_100406_quant_up">+</span>
        </div>
        <div id="bx_117848907_100406_basket_actions" class="button_block  wide">
            <!--noindex-->
            <span class="big_btn to-cart button" data-value="65" data-currency="RUB" data-item="100406" data-float_ratio="1" data-ratio="1" data-bakset_div="bx_basket_div_100406" data-props="" data-part_props="Y" data-add_props="Y" data-empty_props="Y" data-offers="" data-iblockid="47" data-quantity="1" style="display: none;"><i></i><span>В корзину</span></span><a rel="nofollow" href="/basket/" class="big_btn in-cart button" data-item="100406" style=""><i></i><span>В корзине</span></a>								<!--/noindex-->
        </div>
    </div>

<script>

    $(document).on("click", ".counter_block:not(.basket) .plus", function(){
        if(!$(this).parents('.basket_wrapp').length){
            if($(this).parent().data("offers")!="Y"){
                var isDetailSKU2 = $(this).parents('.counter_block_wr').length,
                    input = $(this).parents(".counter_block").find("input[type=text]"),
                    tmp_ratio = !isDetailSKU2 ? $(this).parents(".counter_wrapp").find(".to-cart").data('ratio') : $(this).parents('.counter_block_wr').find(".button_block .to-cart").data('ratio'),
                    isDblQuantity = !isDetailSKU2 ? $(this).parents(".counter_wrapp").find(".to-cart").data('float_ratio') : $(this).parents('.counter_block_wr').find(".button_block .to-cart").data('float_ratio'),
                    ratio=( isDblQuantity ? parseFloat(tmp_ratio) : parseInt(tmp_ratio, 10)),
                    max_value='';
                currentValue = input.val();

                if(isDblQuantity)
                    ratio = Math.round(ratio*1000000)/1000000;

                curValue = (isDblQuantity ? parseFloat(currentValue) : parseInt(currentValue, 10));

                curValue += ratio;
                if (isDblQuantity){
                    curValue = Math.round(curValue*1000000)/1000000;
                }
                if(parseFloat($(this).data('max'))>0){
                    if(input.val() <= $(this).data('max')){
                        if(curValue<=$(this).data('max'))
                            input.val(curValue);

                        input.change();
                    }
                }else{
                    input.val(curValue);
                    input.change();
                }
            }
        }
    });

    $(document).on("click", ".counter_block:not(.basket) .minus", function(){
        if(!$(this).parents('.basket_wrapp').length){
            if($(this).parent().data("offers")!="Y"){
                var isDetailSKU2 = $(this).parents('.counter_block_wr').length;
                input = $(this).parents(".counter_block").find("input[type=text]")
                tmp_ratio = !isDetailSKU2 ? $(this).parents(".counter_wrapp").find(".to-cart").data('ratio') : $(this).parents('.counter_block_wr').find(".button_block .to-cart").data('ratio'),
                    isDblQuantity = !isDetailSKU2 ? $(this).parents(".counter_wrapp").find(".to-cart").data('float_ratio') : $(this).parents('.counter_block_wr').find(".button_block .to-cart").data('float_ratio'),
                    ratio=( isDblQuantity ? parseFloat(tmp_ratio) : parseInt(tmp_ratio, 10)),
                    max_value='';
                currentValue = input.val();

                if(isDblQuantity)
                    ratio = Math.round(ratio*1000000)/1000000;

                curValue = (isDblQuantity ? parseFloat(currentValue) : parseInt(currentValue, 10));

                curValue -= ratio;
                if (isDblQuantity){
                    curValue = Math.round(curValue*1000000)/1000000;
                }

                if(parseFloat($(this).parents(".counter_block").find(".plus").data('max'))>0){
                    if(currentValue > ratio){
                        if(curValue<ratio){
                            input.val(ratio);
                        }else{
                            input.val(curValue);
                        }
                        input.change();
                    }
                }else{
                    if(curValue > ratio){
                        input.val(curValue);
                    }else{
                        if(ratio){
                            input.val(ratio);
                        }else if(currentValue > 1){
                            input.val(curValue);
                        }
                    }
                    input.change();
                }
            }
        }
    });

</script>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>