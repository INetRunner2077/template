<?php

global $dopClass;
$dopClass .= "iks-ignore";
/*
$this->addExternalJs('/bitrix/templates/aspro_mshop/js/fias-api/src/js/core.js');
$this->addExternalJs('/bitrix/templates/aspro_mshop/js/fias-api/src/js/fias.js');
$this->addExternalJs('/bitrix/templates/aspro_mshop/js/fias-api/src/js/fias_zip.js');
$this->addExternalCss('/bitrix/templates/aspro_mshop/js/fias-api/src/css/style.css');
*/
$this->addExternalJs(SITE_TEMPLATE_PATH.'/js/jquery.validate.js');
?>
<section class="main-container col2-right-layout">
    <div class="main container">
        <form method="post" class="uf_form"
              action="<?= $arResult["FORM_TARGET"] ?>">
            <?
            if ($_REQUEST['order'] == 'Y'): ?>
                <input type="hidden" name="order" value="Y">
            <?
            endif; ?>

            <?= $arResult["BX_SESSION_CHECK"] ?>

            <?
            if (!empty($arResult['ERRORS'])): ?>
                <div class="error">
                    <strong class="error-span">Ошибка!</strong>
                    <?
                    foreach ($arResult['ERRORS'] as $error): ?>
                        <p><?= $error ?></p>
                    <?
                    endforeach; ?>
                </div>
            <?
            endif; ?>

            <?
            if (empty($_REQUEST['acc'])): ?>
                <div class="success">
                    <strong class="alert-success">Аккаунт успешно подтвержден !</strong>
                </div>
            <?
            endif; ?>

            <div class="first-row">
                <div class="first-row-left">
                    <div class="type_of_buyer">
                        <h4 class="checkout-sep box-sep bord">Тип
                            Покупателя</h4>
                        <?
                        $obEnum = new \CUserFieldEnum;
                        $rsEnum = $obEnum->GetList(
                            array(),
                            array("USER_FIELD_NAME" => "UF_TYPE_OF_BUYER_ALTERMAX")
                        );
                        $enum = array();
                        while ($arEnum = $rsEnum->Fetch()) {
                            if ($arEnum['ID']
                                == $arResult["USER_PROPERTIES"]["DATA"]['UF_TYPE_OF_BUYER_ALTERMAX']['VALUE']
                            ) {
                                $arResult["USER_PROPERTIES"]["DATA"]['UF_TYPE_OF_BUYER_ALTERMAX']['VALUE_XML']
                                    = $arEnum["XML_ID"];
                            }
                            $enum[$arEnum["ID"]]
                                = array(
                                'value' => $arEnum["VALUE"],
                                'xml_id' => $arEnum['XML_ID'],
                            );
                        }
                        $checked
                            = $arResult["USER_PROPERTIES"]["DATA"]['UF_TYPE_OF_BUYER_ALTERMAX']['VALUE'];

                        ?>
                        <span class="fields enumeration field-wrap"
                              data-has-input="no">
               <div class="enum_item table_bord">
                   <?
                   foreach ($enum as $value => $name): ?>
                       <span class="fields enumeration enumeration-checkbox field-item">
                         <label class="checklist--style">
                          <input value="<?= $value ?>"
                                 data-xml="<?= $name['xml_id'] ?>" type="radio"
                                 name="UF_TYPE_OF_BUYER_ALTERMAX" <?
                          ($value == $checked) ? print_r('checked') : ''; ?>  tabindex="0">
                             <span><?= $name['value'] ?></span>
                         </label>
                        </span>
                   <?
                   endforeach; ?>
                   </div>
               </span>

                    </div>

                    <?
                    $block_hide
                        = $arResult["USER_PROPERTIES"]["DATA"]['UF_TYPE_OF_BUYER_ALTERMAX']['VALUE_XML']
                    == 'ORGANIZATION' ? 'block' : 'none';
                    ?>

                    <div class="buyer_details"
                         style="display:<?= $block_hide ?>">
                        <h4 class="checkout-sep box-sep bord">ДАННЫЕ
                            ПОКУПАТЕЛЯ</h4>
                        <div class="buyer_details_container table_bord">
                            <div class="input_container">
                                <label>ИНН</label>
                                <?
                                $APPLICATION->IncludeComponent(
                                    "bitrix:system.field.edit",
                                    $arResult["USER_PROPERTIES"]["DATA"]['UF_INN_ALTERMAX']["USER_TYPE"]["USER_TYPE_ID"],
                                    array(
                                        "bVarsFromForm" => $arResult["bVarsFromForm"],
                                        "arUserField"   => $arResult["USER_PROPERTIES"]["DATA"]['UF_INN_ALTERMAX'],
                                    ),
                                    null,
                                    array("HIDE_ICONS" => "Y")
                                ); ?>
                            </div>
                            <div class="input_container">
                                <label>Форма собственности <span
                                            class="star">*</span></label>
                                <?
                                $APPLICATION->IncludeComponent(
                                    "bitrix:system.field.edit",
                                    $arResult["USER_PROPERTIES"]["DATA"]['UF_OWNERSHIP_ALTERMAX']["USER_TYPE"]["USER_TYPE_ID"],
                                    array(
                                        "bVarsFromForm" => $arResult["bVarsFromForm"],
                                        "arUserField"   => $arResult["USER_PROPERTIES"]["DATA"]['UF_OWNERSHIP_ALTERMAX'],
                                    ),
                                    null,
                                    array("HIDE_ICONS" => "Y")
                                ); ?>
                            </div>
                            <div class="input_container">
                                <label>Название организации <span
                                            class="star">*</span></label>
                                <?
                                $APPLICATION->IncludeComponent(
                                    "bitrix:system.field.edit",
                                    $arResult["USER_PROPERTIES"]["DATA"]['UF_NAME_ORGANIZATION_ALTERMAX']["USER_TYPE"]["USER_TYPE_ID"],
                                    array(
                                        "bVarsFromForm" => $arResult["bVarsFromForm"],
                                        "arUserField"   => $arResult["USER_PROPERTIES"]["DATA"]['UF_NAME_ORGANIZATION_ALTERMAX'],
                                    ),
                                    null,
                                    array("HIDE_ICONS" => "Y")
                                ); ?>
                            </div>
                            <div class="input_container">
                                <label> Юридический адрес </label>
                                <?
                                $APPLICATION->IncludeComponent(
                                    "bitrix:system.field.edit",
                                    $arResult["USER_PROPERTIES"]["DATA"]['UF_LEGAL_ADRESS_ALTERMAX']["USER_TYPE"]["USER_TYPE_ID"],
                                    array(
                                        "bVarsFromForm" => $arResult["bVarsFromForm"],
                                        "arUserField"   => $arResult["USER_PROPERTIES"]["DATA"]['UF_LEGAL_ADRESS_ALTERMAX'],
                                    ),
                                    null,
                                    array("HIDE_ICONS" => "Y")
                                ); ?>
                            </div>
                        </div>
                    </div>


                    <?
                    $type
                        = $arResult["USER_PROPERTIES"]["DATA"]['UF_TYPE_OF_BUYER_ALTERMAX']['VALUE_XML']; ?>

                    <div class="contact_information">
                        <h4 class="checkout-sep box-sep bord">КОНТАКТНАЯ
                            ИНФОРМАЦИЯ</h4>
                        <div class="contact_information_container table_bord">
                            <div class="input_container">
                                <label>Фамилия <span
                                            class="star">*</span></label>
                                <input type="text" required name="LAST_NAME"
                                       maxlength="50"
                                       value="<?= $arResult["arUser"]["LAST_NAME"] ?>"/>
                            </div>
                            <div class="input_container">
                                <label>Имя <span class="star">*</span></label>
                                <input type="text" required name="NAME"
                                       maxlength="50"
                                       value="<?= $arResult["arUser"]["NAME"] ?>"/>
                            </div>
                            <div class="input_container">
                                <label>Отчество</label>
                                <input type="text" name="SECOND_NAME"
                                       maxlength="50"
                                       value="<?= $arResult["arUser"]["SECOND_NAME"] ?>"/>
                            </div>
                            <div class="input_container">
                                <label>EMAIL <span class="star">*</span></label>
                                <input required type="text" name="EMAIL"
                                       maxlength="50"
                                       placeholder="name@company.ru" value="<?
                                echo $arResult["arUser"]["EMAIL"] ?>"/>
                            </div>
                            <div class="input_container">
                                <label>Телефон</label>
                                <input type="text" name="PERSONAL_PHONE"
                                       placeholder="+7 (___) ___-__-__"
                                       class="phone-inp-mask" maxlength="255" value="<?
                                echo $arResult["arUser"]["PERSONAL_PHONE"] ?>"/>
                            </div>
                            <?
                            $type == 'INDIVIDUAL' ? $disp = 'block'
                                : $disp = 'none'; ?>
                            <div class="input_container"
                                 style="display:<?= $disp ?>">
                                <label> ОГРНИП </label>
                                <?
                                $APPLICATION->IncludeComponent(
                                    "bitrix:system.field.edit",
                                    $arResult["USER_PROPERTIES"]["DATA"]['UF_OGRNIP_ALTERMAX']["USER_TYPE"]["USER_TYPE_ID"],
                                    array(
                                        "bVarsFromForm" => $arResult["bVarsFromForm"],
                                        "arUserField"   => $arResult["USER_PROPERTIES"]["DATA"]['UF_OGRNIP_ALTERMAX'],
                                    ),
                                    null,
                                    array("HIDE_ICONS" => "Y")
                                ); ?>
                            </div>
                            <?
                            $type == 'PRIVATE' ? $disp = 'block'
                                : $disp = 'none'; ?>
                            <div class="input_container"
                                 style="display:<?= $disp ?>">
                                <label> Должность </label>
                                <?
                                $APPLICATION->IncludeComponent(
                                    "bitrix:system.field.edit",
                                    $arResult["USER_PROPERTIES"]["DATA"]['UF_POST_ALTERMAX']["USER_TYPE"]["USER_TYPE_ID"],
                                    array(
                                        "bVarsFromForm" => $arResult["bVarsFromForm"],
                                        "arUserField"   => $arResult["USER_PROPERTIES"]["DATA"]['UF_POST_ALTERMAX'],
                                    ),
                                    null,
                                    array("HIDE_ICONS" => "Y")
                                ); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="first-row-right">
                </div>
            </div>


            <div class="second-row">
                <div class="second-row-left">
                    <div class="contact_information_birdthday">
                        <h4 class="checkout-sep box-sep bord">СЮРПРИЗ ОТ
                            КОМПАНИИ НА ВАШ ДЕНЬ РОЖДЕНИЯ</h4>
                        <div class="contact_information_container table_bord">
                            <div class="input_container">
                                <label>Дата рождения</label>
                                <input r
                                       class="input form-control datepicker-js0"
                                       type="date"
                                       value="<?= $arResult["arUser"]["PERSONAL_BIRTHDATE"] ?>"
                                       name="PERSONAL_BIRTHDATE"
                                       placeholder="ДД-ММ-ГГГГ">
                                <small class="color-orange"
                                       style="color:#990000; font-weight:600;">Укажите
                                    дату Вашего рождения</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="second-row-right">
                    <div class="information_cointatiner">
                        Мы с огромным удовольствием поздравим Вас с Днем
                        Рождения. Это совершенно НЕ ОБЯЗЫВАЕТ Вас ни к чему –
                        нам просто приятно сделать «Грустный праздник» более
                        светлым и веселым, а также подарить Вам небольшой
                        сюрприз от лица Компании…
                    </div>
                </div>
            </div>

            <div class="row terms-submit-line">
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-8">
                            <ul class="form-terms-checkbox">
                                <li>
                                    <label>
                                        <input form="user-edit-full"
                                               type="checkbox"
                                               name="terms[news]" value="1"
                                               checked="">
                                        Я хочу получать новости и анонсы от
                                        Компании</label>
                                </li>
                                <li>
                                    <label>
                                        <input form="user-edit-full"
                                               type="checkbox"
                                               name="terms[prod]" value="1"
                                               required="" checked="">
                                        Я согласен с <a href="/uslovie-prodadj"
                                                        target="_blank">Соглашением
                                            об условиях продажи
                                            Продукции</a></label>
                                </li>
                                <li>
                                    <label>
                                        <input form="user-edit-full"
                                               type="checkbox"
                                               name="terms[lice]" value="1"
                                               required="" checked="">
                                        Я согласен с условиями <a
                                                href="/soglashenie-polzovatelja"
                                                target="_blank">Соглашения
                                            пользователя</a></label>
                                </li>
                            </ul>
                        </div>
                        <? if(empty($_REQUEST['acc'])): ?>
                            <div class="col-sm-4">
                                <button class="button subm-btm button-green"
                                        type="submit" name="save" value="Сохранить аккаунт">
                          <span>
                          Завершить регистрацию
                          </span>
                                </button>
                            </div>
                        <? else: ?>
                        <input type="hidden" name="acc" value="Y">
                            <div class="col-sm-4">
                                <button class="button subm-btm button-green"
                                        type="submit" name="save" value="Сохранить аккаунт">
                          <span>
                          Сохранить аккаунт
                          </span>
                                </button>
                            </div>
                        <? endif; ?>
                    </div>
                </div>
            </div>

        </form>

    </div>
</section>

<?
if ($type == 'ORGANIZATION'): ?>

    <script>
        $('.input_container input[name="UF_OWNERSHIP_ALTERMAX"]').prop('required', true);
        $('.input_container input[name="UF_NAME_ORGANIZATION_ALTERMAX"]').prop('required', true);
        $.validator.addClassRules("UF_OWNERSHIP_ALTERMAX", {
            required: true
        });
        $.validator.addClassRules("UF_NAME_ORGANIZATION_ALTERMAX", {
            required: true
        });
    </script>

<?
endif; ?>

<script>

    $('.uf_form').validate({
        rules: {
            "EMAIL": {email: true},
            "LAST_NAME": {required: true},
            "NAME": {required: true},
            "PERSONAL_BIRTHDATE": {date: false},
        },
        highlight: function (element) {
            $(element).removeClass('error');
        },
        errorPlacement: function (error, element) {
            error.insertBefore(element);
        },
        messages: {
            'LAST_NAME': {
                required: 'Введите фамилию'
            },
            'EMAIL': {
                email: 'Введите корректный email',
                required: 'Заполните поле email',
            },
            'NAME': {
                required: 'Введите Имя'
            },
            'UF_OWNERSHIP_ALTERMAX': {
                required: 'Выеберете форму собственности'
            },
            'UF_NAME_ORGANIZATION_ALTERMAX': {
                required: 'Введите название организации'
            },
        },

    });

    $('.type_of_buyer input').click(function () {
        if ($(this).attr("data-xml") == 'ORGANIZATION') {
            $('.buyer_details').css('display', 'block');

            $('.input_container input[name="UF_OWNERSHIP_ALTERMAX"]').prop('required', true);
            $('.input_container input[name="UF_NAME_ORGANIZATION_ALTERMAX"]').prop('required', true);

            $.validator.addClassRules("UF_OWNERSHIP_ALTERMAX", {
                required: true
            });

            $.validator.addClassRules("UF_NAME_ORGANIZATION_ALTERMAX", {
                required: true
            });

        } else if ($(this).attr("data-xml") == 'INDIVIDUAL') {
            $('.input_container input[name="UF_POST_ALTERMAX"]').val('').parents('.input_container').css('display', 'none');
            $('.input_container input[name="UF_OGRNIP_ALTERMAX"]').val('').parents('.input_container').css('display', 'block');
            $('.buyer_details').css('display', 'none');
            $('.input_container input[name="UF_OWNERSHIP_ALTERMAX"]').prop('required', false);
            $('.input_container input[name="UF_NAME_ORGANIZATION_ALTERMAX"]').prop('required', false);

            $.validator.addClassRules("UF_OWNERSHIP_ALTERMAX", {
                required: false
            });

            $.validator.addClassRules("UF_NAME_ORGANIZATION_ALTERMAX", {
                required: false
            });


        } else if ($(this).attr("data-xml") == 'PRIVATE') {
            $('.input_container input[name="UF_POST_ALTERMAX"]').val('').parents('.input_container').css('display', 'block');
            $('.input_container input[name="UF_OGRNIP_ALTERMAX"]').val('').parents('.input_container').css('display', 'none');
            $('.buyer_details').css('display', 'none');
            $('.input_container input[name="UF_OWNERSHIP_ALTERMAX"]').prop('required', false);
            $('.input_container input[name="UF_NAME_ORGANIZATION_ALTERMAX"]').prop('required', false);

            $.validator.addClassRules("UF_OWNERSHIP_ALTERMAX", {
                required: false
            });

            $.validator.addClassRules("UF_NAME_ORGANIZATION_ALTERMAX", {
                required: false
            });
        }
    });


    $('.datepicker-js').mask("99-99-9999");

    //маска ИНН
    $('.input_container input[name="UF_INN_ALTERMAX"]').mask('9999999999');
    //маска ОГРНИП
    $('.input_container input[name="UF_OGRNIP_ALTERMAX"]').mask("999999999999999");


 /*   (function () {

        var $address = $('.input_container input[name="UF_LEGAL_ADRESS_ALTERMAX"]');

        $address.fias({
            oneString: true,
        });
    })();

  */

</script>




