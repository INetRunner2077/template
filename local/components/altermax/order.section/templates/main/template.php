<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
global $APPLICATION;
?>
<?php if($arResult['show'] == 'Y'): ?>

    <section class="main-container col2-right-layout">
        <div class="main container">
            <div class="row">
                <div class="col col-sm-12 col-xs-12">
                    <div class="my-account">
                        <div class="page-title">
                            <div class="row">
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <h2>Order #<?=$arResult['ORDERS'][$arResult['ID']]['ID']?></h2>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12 text-right">
                                    <h2 style="font-weight: normal;"></h2>
                                </div>
                            </div>
                        </div><!-- .page-title -->
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="orders-list table-responsive">
                                    <table class="table table-bordered cart_summary table-striped">
                                        <tbody><tr>
                                            <td class="order-number">Номер</td>
                                            <td>#<?=$arResult['ORDERS'][$arResult['ID']]['ID']?></td>
                                        </tr>
                                        <tr>
                                            <td class="order-number">Дата заказа</td>
                                            <td>
                                                <?=$arResult['ORDERS'][$arResult['ID']]['DATE_INSERT']->toString() ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="order-number">Статус</td>
                                            <td>
                                                <?=$arResult['ORDERS'][$arResult['ID']]['STATUS']['NAME']?>
                                            </td>
                                        </tr>
                                        </tbody></table>
                                </div>
                            </div>
                        </div>
                        <div class="recent-orders">
                            <div class="table-responsive">
                                <table class="table table-bordered cart_summary table-striped">
                                    <colgroup>
                                        <col width="1">
                                        <col>
                                        <col style="width:10%;">
                                        <col width="1">
                                        <col style="width:10%;">
                                    </colgroup>
                                    <thead>
                                    <tr class="first last">
                                        <th class="text-center">Номер</th>
                                        <th>Название</th>
                                        <th class="text-right">Цена руб.</th>
                                        <th class="text-center"><span class="nobr">К-во товаров</span></th>
                                        <th class="text-right"><span class="nobr">Сумма руб.</span></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <? $number = 1; ?>
                                    <? foreach ($arResult['ORDERS'][$arResult['ID']]['PRODUCTS'] as $product): ?>
                                    <tr>
                                        <td class="text-center"><?=$number?></td>
                                        <td>
                                            <?=$product['NAME']?>
                                        </td>
                                        <td class="text-right">27.05</td>
                                        <td class="text-center"><?=$product['QUANTITY']?></td>
                                        <td class="text-right"><?=$product['PRICE']?></td>
                                    </tr>
                                      <?$number++?>
                                      <? endforeach; ?>
                                    </tbody>
                                </table>
                            </div><!-- .table-responsive -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <button class="button subm-btm full-line-size" type="button" onclick="location.href='/account/'">
                                        <span>назад</span>
                                    </button>
                                </div>
                                <div class="col-sm-6">

                                </div>
                            </div>
                        </div><!-- .recent-orders -->
                    </div>
                </div>
            </div>

        </div>
    </section>

<?php else: ?>

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
                                        <button class="button subm-btm button-orange full-line-size"
                                                type="button"
                                                onclick="location.href='/account/'">
                                            <span>Заказы</span>
                                        </button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button class="button subm-btm button-gray full-line-size"
                                                type="button"
                                                onclick="location.href='/register2/'">
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
                        <div class="recent-orders">
                            <div class="title-buttons">
                                <strong>Последние Заказы</strong>
                                <a href="/account?all=all">Смотреть все</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered cart_summary table-striped table-hover">
                                    <colgroup>
                                        <col>
                                        <col style="width:10%;">
                                        <col style="width:10%;">
                                        <col style="width:2%;">
                                        <col>
                                        <col>
                                    </colgroup>
                                    <thead>
                                    <tr class="first last">
                                        <th class="text-center">Номер</th>
                                        <th class="text-center">Дата заказа</th>
                                        <th class="text-center">Время заказа
                                        </th>
                                        <th class="text-center"><span
                                                    class="nobr">К-во товаров</span>
                                        </th>
                                        <th class="text-right"><span
                                                    class="nobr">Сумма, руб.</span>
                                        </th>
                                        <th>Статус</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <? if(empty($arResult['ORDERS'])): ?>
                                    <tbody>
                                    <tr class="odd">
                                        <td></td>
                                        <td valign="top" class="dataTables_empty">
                                            Нет данных для отображения
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                    <? endif; ?>
                                    <? foreach ($arResult['ORDERS'] as $order): ?>
                                        <tr class="first odd">
                                            <td class="text-center"><?=$order['ID']?></td>
                                            <td class="text-center">
                                                <?=$order['DATE_INSERT']->format("d-m-Y")?>
                                            </td>
                                            <td class="text-center">
                                                <?=$order['DATE_INSERT']->format("H:i:s")?>
                                            </td>
                                            <td class="text-center"><?=count($order['PRODUCTS'])?></td>
                                            <td class="text-right"><span
                                                        class="price"><?=$order['PRICE']?></span>
                                            </td>
                                            <td><em><?=$order['STATUS']['NAME']?></em></td>
                                            <td class="a-center last">
                                  <span class="nobr">
                                    <a href="<?=$APPLICATION->GetCurPage()?>?orderId=<?=$order['ID']?>">Просмотр заказа</a>
                                    <span class="separator">|</span>
                                    <a class="del_order" data-orderid="<?=$order['ID']?>">Удалить заказ</a>
                                   </span>
                                            </td>
                                        </tr>
                                    <?
                                    endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .recent-orders -->
                    </div>
                </div>
            </div>
        </div>
        <!-- service section -->
        <div class="jtv-service-area" data-file="baner_line_serv.php">
            <!--div class="container"-->
            <div class="row">
                <div class="col col-md-4 col-sm-6 col-xs-12 ">
                    <div class="block-wrapper return">
                        <div class="text-des">
                            <div class="icon-wrapper"><i
                                        class="fa fa-truck"></i></div>
                            <div class="service-wrapper">
                                <h3>Доставка в регионы</h3>
                                <p>Доставляем по всей России.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-md-4 col-sm-6 col-xs-12">
                    <div class="block-wrapper support">
                        <div class="text-des">
                            <div class="icon-wrapper"><i
                                        class="far fa-question-circle"></i>
                            </div>
                            <div class="service-wrapper">
                                <h3>Тех. поддержка</h3>
                                <p>Всегда вам поможем.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-md-4 col-sm-6 col-xs-12">
                    <div class="block-wrapper user">
                        <div class="text-des">
                            <div class="icon-wrapper"><i class="fa fa-tags"></i>
                            </div>
                            <div class="service-wrapper">
                                <h3>Скидки и акции</h3>
                                <p>С нами выгоднее.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/div-->
        </div><!-- .jtv-service-area -->
    </div>
</section>
<?php endif; ?>