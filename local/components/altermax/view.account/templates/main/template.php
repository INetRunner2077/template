<?php

global $dopClass;
$dopClass .= "iks-ignore";
/*
$this->addExternalJs('/bitrix/templates/aspro_mshop/js/fias-api/src/js/core.js');
$this->addExternalJs('/bitrix/templates/aspro_mshop/js/fias-api/src/js/fias.js');
$this->addExternalJs('/bitrix/templates/aspro_mshop/js/fias-api/src/js/fias_zip.js');
$this->addExternalCss('/bitrix/templates/aspro_mshop/js/fias-api/src/css/style.css');
*/
?>
<section class="main-container col2-right-layout">
    <div class="main container">
        <div class="row">
            <div class="col col-sm-12 col-xs-12">
                <div class="my-account">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <h2>Личный кабинет</h2>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 text-right">
                                <h2 style="font-weight: normal;"></h2>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button class="button subm-btm button-gray full-line-size"
                                                type="button"
                                                onclick="location.href='/account?fnc=orders'">
                                            <span>Заказы</span>
                                        </button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button class="button subm-btm button-orange full-line-size"
                                                type="button"
                                                onclick="location.href='/account?fnc=account'">
                                            <span>Аккаунт</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="welcome-msg block-psh">
                            <p>В личном кабинете в любое время Вы можете
                                просмотреть статусы и контент Ваших заказов, а
                                также внести корректировки в регистрационные
                                данные.</p>
                        </div>
                        <div class="box-account info-account-bx">
                            <div class="page-title">
                                <h2>Данные Аккаунта</h2>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <h4>Контактная Информация</h4>
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <strong>Фамилия</strong>
                                            </td>
                                            <td>
                                                <? if(!empty($arResult['arUser']['LAST_NAME'])): ?>
                                                <?=$arResult['arUser']['LAST_NAME']?>
                                                <? else: ?>
                                                <span class="empty-el"></span>
                                                <? endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Имя</strong>
                                            </td>
                                            <td>
                                                <? if(!empty($arResult['arUser']['NAME'])): ?>
                                                    <?=$arResult['arUser']['NAME']?>
                                                <? else: ?>
                                                    <span class="empty-el"></span>
                                                <? endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Отчество</strong>
                                            </td>
                                            <td>
                                                <? if(!empty($arResult['arUser']['SECOND_NAME'])): ?>
                                                    <?=$arResult['arUser']['SECOND_NAME']?>
                                                <? else: ?>
                                                    <span class="empty-el"></span>
                                                <? endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Email</strong>
                                            </td>
                                            <td>
                                                <? if(!empty($arResult['arUser']['EMAIL'])): ?>
                                                    <?=$arResult['arUser']['EMAIL']?>
                                                <? else: ?>
                                                    <span class="empty-el"></span>
                                                <? endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Телефон</strong>
                                            </td>
                                            <td>
                                                <? if(!empty($arResult['arUser']['PERSONAL_PHONE'])): ?>
                                                    <?=$arResult['arUser']['PERSONAL_PHONE']?>
                                                <? else: ?>
                                                    <span class="empty-el"></span>
                                                <? endif; ?>
                                            </td>
                                        </tr>
                                        <?if($arResult['arUser']['UF_TYPE_OF_BUYER_ALTERMAX'] == 'PRIVATE'): ?>
                                        <tr>
                                            <td>
                                                <strong>Должность</strong>
                                            </td>
                                            <td>
                                                <? if(!empty($arResult['arUser']['UF_POST_ALTERMAX'])): ?>
                                                    <?=$arResult['arUser']['UF_POST_ALTERMAX']?>
                                                <? else: ?>
                                                    <span class="empty-el"></span>
                                                <? endif; ?>
                                            </td>
                                        </tr>
                                        <? endif; ?>
                                        <? if($arResult['arUser']['UF_TYPE_OF_BUYER_ALTERMAX'] == 'PRIVATE'): ?>
                                        <tr>
                                            <td>
                                                <strong>ОГРНИП</strong>
                                            </td>
                                            <td>
                                                <? if(!empty($arResult['arUser']['UF_OGRNIP_ALTERMAX'])): ?>
                                                    <?=$arResult['arUser']['UF_OGRNIP_ALTERMAX']?>
                                                <? else: ?>
                                                    <span class="empty-el"></span>
                                                <? endif; ?>
                                            </td>
                                        </tr>
                                        <? endif; ?>
                                        <? if($arResult['arUser']['UF_TYPE_OF_BUYER_ALTERMAX'] == 'ORGANIZATION'): ?>
                                        <tr>
                                            <td>
                                                <strong>Форма собственности</strong>
                                            </td>
                                            <td>
                                                <? if(!empty($arResult['arUser']['UF_OWNERSHIP_ALTERMAX'])): ?>
                                                    <?=$arResult['arUser']['UF_OWNERSHIP_ALTERMAX']?>
                                                <? else: ?>
                                                    <span class="empty-el"></span>
                                                <? endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Название организации</strong>
                                            </td>
                                            <td>
                                                <? if(!empty($arResult['arUser']['UF_NAME_ORGANIZATION_ALTERMAX'])): ?>
                                                    <?=$arResult['arUser']['UF_NAME_ORGANIZATION_ALTERMAX']?>
                                                <? else: ?>
                                                    <span class="empty-el"></span>
                                                <? endif; ?>
                                            </td>
                                        </tr>
                                        <? endif; ?>
                                        <? if(($arResult['arUser']['UF_TYPE_OF_BUYER_ALTERMAX'] == 'ORGANIZATION') or ($arResult['arUser']['UF_TYPE_OF_BUYER_ALTERMAX'] == 'INDIVIDUAL')): ?>
                                        <tr>
                                            <td>
                                                <strong>ИНН</strong>
                                            </td>
                                            <td>
                                                <? if(!empty($arResult['arUser']['UF_INN_ALTERMAX'])): ?>
                                                    <?=$arResult['arUser']['UF_INN_ALTERMAX']?>
                                                <? else: ?>
                                                    <span class="empty-el"></span>
                                                <? endif; ?>
                                            </td>
                                        </tr>
                                        <? endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <button class="button subm-btm full-line-size"
                                            type="button"
                                            onclick="location.href='/userregister?fn=edit'">
                                        <span>Изменить</span>
                                    </button>
                                </div>
                            </div>
                        </div><!-- .box-account -->
                    </div>
                </div>
            </div>
        </div>
        <!-- service section -->
        <?
        $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            array(
                "AREA_FILE_RECURSIVE" => "Y",
                "AREA_FILE_SHOW"      => "file",
                "AREA_FILE_SUFFIX"    => "inc",
                "EDIT_TEMPLATE"       => "standard.php",
                "PATH"                => "/bitrix/modules/altermax.shop/include/banner.php",
            )
        ); ?>
        <!-- .jtv-service-area -->
    </div>
</section>



