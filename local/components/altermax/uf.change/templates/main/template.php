<?php
global $dopClass;
$dopClass .= "iks-ignore";

use Bitrix\Main\UI\Extension;
Extension::load('ui.bootstrap4');
$this->addExternalJs('/bitrix/templates/aspro_mshop/js/fias-api/src/js/core.js');
$this->addExternalJs('/bitrix/templates/aspro_mshop/js/fias-api/src/js/fias.js');
$this->addExternalJs('/bitrix/templates/aspro_mshop/js/fias-api/src/js/fias_zip.js');
$this->addExternalCss('/bitrix/templates/aspro_mshop/js/fias-api/src/css/style.css');
?>
<section class="main-container col2-right-layout">
    <div class="main container">
<link rel="stylesheet" href="/local/components/danil/uf.change/templates/main/datepicker/dist/css/datepicker.material.css">
<script src="/local/components/danil/uf.change/templates/main/datepicker/dist/datepicker.js"></script>
<script src="/local/components/danil/uf.change/templates/main/masked/dist/jquery.maskedinput.min.js"></script>
<form method="post" class="uf_form" action="<?=$arResult["FORM_TARGET"]?>">
    <? if($_REQUEST['order'] == 'Y'): ?>
        <input type="hidden" name="order" value="Y">
    <? endif; ?>

    <?=$arResult["BX_SESSION_CHECK"]?>

    <? if(!empty($arResult['ERRORS'])): ?>
    <div class="error">
        <strong class="error-span">Ошибка!</strong>
        <? foreach ($arResult['ERRORS'] as $error): ?>
            <p><?=$error?></p>
        <? endforeach; ?>
    </div>
    <? endif; ?>
    <div class="first-row">
        <div class="first-row-left">
            <div class="type_of_buyer">
                <h4 class="checkout-sep box-sep bord">Тип Покупателя</h4>
                <?
                $obEnum = new \CUserFieldEnum;
                $rsEnum = $obEnum->GetList(
                    array(),
                    array("USER_FIELD_NAME" => "UF_TYPE_OF_BUYER")
                );
                $enum = array();
                while ($arEnum = $rsEnum->Fetch()) {
                    if ($arEnum['DEF'] == 'Y') {
                        $def = $arEnum["ID"];
                    }
                    $enum[$arEnum["ID"]] = $arEnum["VALUE"];
                }
                $checked = $arResult["USER_PROPERTIES"]["DATA"]['UF_TYPE_OF_BUYER']['VALUE'];

                ?>
                <span class="fields enumeration field-wrap" data-has-input="no">
               <div class="enum_item table_bord">
                   <?
                   foreach ($enum as $value => $name): ?>
                       <span class="fields enumeration enumeration-checkbox field-item">
                         <label class="checklist--style">
                          <input value="<?= $value ?>" type="radio" name="UF_TYPE_OF_BUYER" <? ($value == $checked) ? print_r('checked') : ''; ?>  tabindex="0">
                             <span><?= $name ?></span>
                         </label>
                        </span>
                   <?endforeach; ?>
                   </div>
               </span>

            </div>

            <?
            $block_hide = $arResult["USER_PROPERTIES"]["DATA"]['UF_TYPE_OF_BUYER']['VALUE'] == 19 ? 'block' : 'none';
            ?>

            <div class="buyer_details" style="display:<?=$block_hide?>">
                <h4 class="checkout-sep box-sep bord">ДАННЫЕ ПОКУПАТЕЛЯ</h4>
                <div class="buyer_details_container table_bord">
                    <div class="input_container">
                        <label>ИНН</label>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:system.field.edit",
                            $arResult["USER_PROPERTIES"]["DATA"]['UF_INN']["USER_TYPE"]["USER_TYPE_ID"],
                            array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arResult["USER_PROPERTIES"]["DATA"]['UF_INN']), null, array("HIDE_ICONS"=>"Y"));?>
                    </div>
                    <div class="input_container">
                        <label>Форма собственности <span class="star">*</span></label>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:system.field.edit",
                            $arResult["USER_PROPERTIES"]["DATA"]['UF_OWNERSHIP']["USER_TYPE"]["USER_TYPE_ID"],
                            array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arResult["USER_PROPERTIES"]["DATA"]['UF_OWNERSHIP']), null, array("HIDE_ICONS"=>"Y"));?>
                    </div>
                    <div class="input_container">
                        <label>Название организации <span class="star">*</span></label>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:system.field.edit",
                            $arResult["USER_PROPERTIES"]["DATA"]['UF_NAME_ORGANIZATION']["USER_TYPE"]["USER_TYPE_ID"],
                            array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arResult["USER_PROPERTIES"]["DATA"]['UF_NAME_ORGANIZATION']), null, array("HIDE_ICONS"=>"Y"));?>
                    </div>
                    <div class="input_container">
                        <label>  Юридический адрес </label>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:system.field.edit",
                            $arResult["USER_PROPERTIES"]["DATA"]['UF_LEGAL_ADRESS']["USER_TYPE"]["USER_TYPE_ID"],
                            array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arResult["USER_PROPERTIES"]["DATA"]['UF_LEGAL_ADRESS']), null, array("HIDE_ICONS"=>"Y"));?>
                    </div>
                </div>
            </div>


            <? $type =  $arResult["USER_PROPERTIES"]["DATA"]['UF_TYPE_OF_BUYER']['VALUE']; ?>

            <div class="contact_information">
                <h4 class="checkout-sep box-sep bord">КОНТАКТНАЯ ИНФОРМАЦИЯ</h4>
                <div class="contact_information_container table_bord">
                    <div class="input_container">
                        <label>Фамилия <span class="star">*</span></label>
                        <input type="text" required name="LAST_NAME" maxlength="50" value="<?=$arResult["arUser"]["LAST_NAME"]?>" />
                    </div>
                    <div class="input_container">
                        <label>Имя <span class="star">*</span></label>
                        <input type="text" required name="NAME" maxlength="50" value="<?=$arResult["arUser"]["NAME"]?>" />
                    </div>
                    <div class="input_container">
                        <label>Отчество</label>
                        <input type="text" name="SECOND_NAME" maxlength="50" value="<?=$arResult["arUser"]["SECOND_NAME"]?>" />
                    </div>
                    <div class="input_container">
                        <label>EMAIL <span class="star">*</span></label>
                        <input required type="text" name="EMAIL" maxlength="50" placeholder="name@company.ru" value="<? echo $arResult["arUser"]["EMAIL"]?>" />
                    </div>
                    <div class="input_container">
                        <label>Телефон</label>
                        <input  type="text" name="PERSONAL_PHONE" placeholder="+7 (___) ___-__-__" class="phone" maxlength="255" value="<? echo $arResult["arUser"]["PERSONAL_PHONE"]?>" />
                    </div>
                    <?  $type == 18 ? $disp = 'block' : $disp = 'none'; ?>
                    <div class="input_container" style="display:<?=$disp?>">
                        <label>  ОГРНИП </label>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:system.field.edit",
                            $arResult["USER_PROPERTIES"]["DATA"]['UF_OGRNIP']["USER_TYPE"]["USER_TYPE_ID"],
                            array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arResult["USER_PROPERTIES"]["DATA"]['UF_OGRNIP']), null, array("HIDE_ICONS"=>"Y"));?>
                    </div>
                    <?  $type == 17 ? $disp = 'block' : $disp = 'none'; ?>
                    <div class="input_container" style="display:<?=$disp?>">
                        <label>  Должность </label>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:system.field.edit",
                            $arResult["USER_PROPERTIES"]["DATA"]['UF_POST']["USER_TYPE"]["USER_TYPE_ID"],
                            array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arResult["USER_PROPERTIES"]["DATA"]['UF_POST']), null, array("HIDE_ICONS"=>"Y"));?>
                    </div>
                </div>
            </div>
        </div>
        <div class="first-row-right">

          <!--  <div class="information_cointatiner">
                Что-то напишем
            </div> !-->
        </div>
    </div>




    <div class="second-row">
        <div class="second-row-left">
            <div class="contact_information_birdthday">
                <h4 class="checkout-sep box-sep bord">СЮРПРИЗ ОТ КОМПАНИИ НА ВАШ ДЕНЬ РОЖДЕНИЯ</h4>
                <div class="contact_information_container table_bord">
                    <div class="input_container">
                        <label>Дата рождения</label>
                        <input r class="input form-control datepicker-js0" type="date" value="<?=$arResult["arUser"]["PERSONAL_BIRTHDATE"]?>" name="PERSONAL_BIRTHDATE" placeholder="ДД-ММ-ГГГГ" >
                        <small class="color-orange" style="color:#990000; font-weight:600;">Укажите дату Вашего рождения</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="second-row-right">
            <div class="information_cointatiner">
                Мы с огромным удовольствием поздравим Вас с Днем Рождения. Это совершенно НЕ ОБЯЗЫВАЕТ Вас ни к чему – нам просто приятно сделать «Грустный праздник» более светлым и веселым, а также подарить Вам небольшой сюрприз от лица Компании…
            </div>
        </div>
    </div>


    <div class="save_container">
        <input type="submit" class="button subm-btm button-green" name="save"  value="Сохранить аккаунт">&nbsp;
    </div>
</form>

    </div>
</section>

<? if($type == 19): ?>

<script>
$('.input_container input[name="UF_OWNERSHIP"]').prop('required', true);
$('.input_container input[name="UF_NAME_ORGANIZATION"]').prop('required', true);
$.validator.addClassRules("UF_OWNERSHIP", {
required: true });
$.validator.addClassRules("UF_NAME_ORGANIZATION", {
required: true });
</script>

<? endif; ?>

<script>

$('.uf_form').validate({
        rules: {
            "EMAIL": {email: true},
            "LAST_NAME": {required: true},
            "NAME": {required: true},
            "PERSONAL_BIRTHDATE": {date: false},
        },
        highlight: function( element ){
            $(element).removeClass('error');
        },
        errorPlacement: function( error, element ){
            error.insertBefore(element);
        },
        messages:{
            'LAST_NAME': {
                required : 'Введите фамилию'
            },
            'EMAIL': {
                email: 'Введите корректный email',
                required : 'Заполните поле email',
            },
            'NAME': {
                required: 'Введите Имя'
            },
            'UF_OWNERSHIP': {
               required: 'Выеберете форму собственности' 
            },
            'UF_NAME_ORGANIZATION': {
               required: 'Введите название организации' 
            },
        },

    });

    $('.type_of_buyer input').click(function() {
        if($(this).val() == 19) {
            $('.buyer_details').css('display', 'block');

            $('.input_container input[name="UF_OWNERSHIP"]').prop('required', true);
            $('.input_container input[name="UF_NAME_ORGANIZATION"]').prop('required', true);

            $.validator.addClassRules("UF_OWNERSHIP", {
	         required: true });

             $.validator.addClassRules("UF_NAME_ORGANIZATION", {
            required: true });

        } 
         else if($(this).val() == 18) {
            $('.input_container input[name="UF_POST"]').val('').parents('.input_container').css('display', 'none');
            $('.input_container input[name="UF_OGRNIP"]').val('').parents('.input_container').css('display', 'block');
            $('.buyer_details').css('display', 'none');
            $('.input_container input[name="UF_OWNERSHIP"]').prop('required', false);
            $('.input_container input[name="UF_NAME_ORGANIZATION"]').prop('required', false);

            $.validator.addClassRules("UF_OWNERSHIP", {
	         required: false });

             $.validator.addClassRules("UF_NAME_ORGANIZATION", {
            required: false });


         } 
         else if ($(this).val() == 17) {
            $('.input_container input[name="UF_POST"]').val('').parents('.input_container').css('display', 'block');
            $('.input_container input[name="UF_OGRNIP"]').val('').parents('.input_container').css('display', 'none');
            $('.buyer_details').css('display', 'none');
            $('.input_container input[name="UF_OWNERSHIP"]').prop('required', false);
            $('.input_container input[name="UF_NAME_ORGANIZATION"]').prop('required', false);

            $.validator.addClassRules("UF_OWNERSHIP", {
	         required: false });

             $.validator.addClassRules("UF_NAME_ORGANIZATION", {
            required: false });
         }       
    });

  /*  var datepicker = new Datepicker('.datepicker-js', {
        format: 'dd-mm-yyyy',
        language: 'ru-RU',
    }); */

    $('.datepicker-js').mask("99-99-9999");

    //маска ИНН
    $('.input_container input[name="UF_INN"]').mask('9999999999');
    //маска ОГРНИП
    $('.input_container input[name="UF_OGRNIP"]').mask("999999999999999");


    (function () {

    var $address = $('.input_container input[name="UF_LEGAL_ADRESS"]');

    $address.fias({
        oneString: true,
    });
})();

</script>
