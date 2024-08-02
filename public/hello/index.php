<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php"); ?>

    <section class="main-container col2-right-layout">
        <div class="main container">
            <div class="row">
                <div class="col-sm-7">
                    <div class="page-title">
                        <h2 class="text-uppercase">Заказ завершен</h2>
                    </div>
                    <br>
                    <h4>СПАСИБО ЗА РЕГИСТРАЦИЮ!</h4>
                    <p>На указанный Вам адрес электронной почты отправлено соответствующее уведомление.</p>
                </div>
            </div><!-- .row -->
            <div class="row">
                <div class="col-sm-7">
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="button subm-btm full-line-size" type="button" onclick="location.href='/catalog/'">
                                <span>Перейти к подбору товаров</span></button>
                        </div>
                        <div class="col-sm-6">
                            <button class="button subm-btm button-green full-line-size" type="button" onclick="location.href='/account/'">
                                <span>Перейти в личный кабинет</span></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- service section -->
        </div>
    </section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>