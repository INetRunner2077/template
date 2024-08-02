<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-3 col-xs-12">
                <div class="footer-logo">
                    <a href="/">
                        <img src="<?=CFile::GetPath( \Bitrix\Main\Config\Option::get("altermax.shop", "client_logo_retina"))?>" alt="Изделия электронной техники">
                    </a>
                </div>
            </div>
            <div class="col-sm-3 col-md-2 col-xs-12 collapsed-block">
                <div class="footer-links">
                    <h5 class="links-title">Документация<a class="expander visible-xs" href="#TabBlock-3">+</a></h5>
                    <div class="tabBlock" id="TabBlock-3">
                        <ul class="list-links list-unstyled">
                            <li><a href="/spravochniki">Справочники</a></li>
                            <li><a href="/tehnicheskaya_dokumentaciya">Тех. документация</a></li>
                            <li><a href="/soglashenie-polzovatelja">Соглашение пользователя</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 col-md-2 col-xs-12 collapsed-block">
                <div class="footer-links">
                    <h5 class="links-title">О компании<a class="expander visible-xs" href="#TabBlock-4">+</a></h5>
                    <div class="tabBlock" id="TabBlock-4">
                        <ul class="list-links list-unstyled">
                            <li><a href="/contacts">Контакты</a></li>
                            <li><a href="/rekvizity">Реквизиты</a></li>
                            <li><a href="/partneram">Партнеры</a></li>
                            <li><a href="/about">История компании</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3 col-xs-12 collapsed-block">
                <div class="footer-links">
                    <h5 class="links-title">Часы работы<a class="expander visible-xs" href="#TabBlock-5">+</a></h5>
                    <div class="tabBlock" id="TabBlock-5">
                        <div class="footer-description"><b> <?=\Bitrix\Main\Config\Option::get("altermax.shop", "site_time_work", "ПН-ПТ: 9:00 — 18.00 МСК")?></b> <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-coppyright">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-xs-12 coppyright"> Копирайт © <?=date('Y')?> <a href="/" target="_blank">"<?=\Bitrix\Main\Config\Option::get("main", "site_name", "ООО Энергофлот")?>"</a>. Все права защищены.</div>
                <div class="col-sm-6 col-xs-12">
                    <ul class="footer-company-links">
                        <li> <a href="/about" target="_blank">О компании</a> </li>
                        <li> <a href="/soglashenie-polzovatelja" target="_blank">Соглашение пользователя</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>