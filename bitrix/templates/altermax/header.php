<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Bitrix\Main\Page\Asset;
use Bitrix\Main\UI\Extension;
Extension::load('ui.bootstrap4');
CJSCore::Init(array("jquery"));
?>
    <!DOCTYPE html>
    <html lang="<?=LANGUAGE_ID?>"><head>
        <meta charset="<?=LANG_CHARSET?>">
        <?$APPLICATION->ShowHead();?>
        <? Asset::getInstance()->addCss("/bitrix/templates/altermax/css/style.css"); ?>
    <? Asset::getInstance()->addCss("/bitrix/templates/altermax/css/style_bitrix.css"); ?>
    <? Asset::getInstance()->addCss("/bitrix/templates/altermax/css/addons.css"); ?>
    <? Asset::getInstance()->addCss("/bitrix/templates/altermax/css/theme-color.css"); ?>
    <? Asset::getInstance()->addCss("/bitrix/templates/altermax/css/all.css"); ?>
    <? Asset::getInstance()->addCss("/bitrix/css/main/bootstrap.min.css"); ?>
    <? Asset::getInstance()->addCss("/bitrix/templates/altermax/css/simple-line-icons.css"); ?>
    <? Asset::getInstance()->addCss("/bitrix/templates/altermax/css/flexslider.css"); ?>
    <? Asset::getInstance()->addCss("/bitrix/templates/altermax/css/font-awesome.min.css"); ?>
    <? Asset::getInstance()->addJs('/bitrix/templates/altermax/js/main.js'); ?>
    <? Asset::getInstance()->addJs('/bitrix/templates/altermax/js/jquery.validate.js'); ?>
    <? Asset::getInstance()->addJs('/bitrix/templates/altermax/js/owl.carousel.min.js'); ?>
    <? Asset::getInstance()->addJs('/bitrix/templates/altermax/js/cloud-zoom.js'); ?>
    <? Asset::getInstance()->addJs('/bitrix/templates/altermax/js/jquery.flexslider.js'); ?>
    <? Asset::getInstance()->addJs('/bitrix/templates/altermax/js/jqModal.js'); ?>
    <? Asset::getInstance()->addCss("/bitrix/templates/altermax/css/quick_view_popup.css"); ?>
    <? Asset::getInstance()->addCss("/bitrix/templates/altermax/css/pe-icon-7-stroke.min.css"); ?>
    <? Asset::getInstance()->addJs('/bitrix/templates/altermax/js/jq.functions.js'); ?>

        <title>Поставщик промышленной электроники, электротехники, КИПиА - Компания «ЭНЕРГОФЛОТ» - Краснодар</title>
    </head>
    <body class="cms-pamcms-index-index cms-home-page ">
    <?$APPLICATION->ShowPanel();?>
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <!-- mobile menu -->
    <div id="mobile-menu">
        <ul>
            <li>
                <a href="/">
                    <span>Главная</span>
                </a>
            </li>
            <li>
                <a href="/besplatno">
                    <span>Бесплатно</span>
                </a>
            </li>
            <li>
                <a href="/partneram">
                    <span>Партнерам</span>
                </a>
            </li>
            <li>
                <a href="/about">
                    <span>Компания</span>
                </a>
            </li>
            <li>
                <a href="/contacts">
                    <span>Контакты</span>
                </a>
            </li>
            <li>
                <a href="#">Каталог</a>
                <ul>
                    <li>
                        <a href="/category/ehlektronnye-komponenty">Электронные компоненты</a>
                    </li>
                    <li>
                        <a href="/category/izmeritelnye-pribory">Измерительные приборы</a>
                    </li>
                    <li>
                        <a href="/category/optoehlektronika">Оптоэлектроника</a>
                    </li>
                    <li>
                        <a href="/category/ehlektrotekhnika">Электротехника</a>
                    </li>
                    <li>
                        <a href="/category/bloki-i-ehlementy-pitaniya">Блоки и элементы питания</a>
                    </li>
                    <li>
                        <a href="/category/korpusnye_i_ustanovochnye_izdeliya">Корпусные и установочные изделия</a>
                    </li>
                    <li>
                        <a href="/category/payalnoe-oborudovanie">Паяльное оборудование</a>
                    </li>
                    <li>
                        <a href="/category/raskhodnye-materialy">Расходные материалы</a>
                    </li>
                    <li>
                        <a href="/category/instrument">Инструмент</a>
                    </li>
                    <li>
                        <a href="/category/sredstva_razrabotki_konstruktory_moduli">Средства разработки, конструкторы, модули</a>
                    </li>
                    <li>
                        <a href="/category/pozicii_na_zakaz">Позиции на заказ</a>
                    </li>
                    <li>
                        <a href="/category/knopki-pereklyuchateli-razemy-rele">Кнопки, переключатели, разъемы, реле</a>
                    </li>
                    <li>
                        <a href="/category/provoda_kabeli_antenny">Провода, кабели, антенны</a>
                    </li>
                    <li>
                        <a href="/category/radiolyubitelskoe_oborudovanie">Радиолюбительское оборудование</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div><!-- end mobile menu -->
    <div id="page">
        <!-- Header -->
        <header class="header-fly">
            <div class="header-container">
                <div class="header-top">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-4 col-md-4 col-xs-12">
                                <!-- Default Welcome Message -->
                                <!-- Language &amp; Currency wrapper -->
                                <div class="language-currency-wrapper">
                                    <div class="inner-cl">
                                        <div class="block us-select-bx">
                                            <div class="us-sity-slk">
                                                <div class="elem-text">
                                                    <a href="#" class="change_user_sity-js open-win-get-ajax" data-fnc="cityget">
                                                        Ваш населенный пункт:
                                                        <span id="user-siti-select-ymap" class="user-sity-sl">Выберите Город</span>
                                                        <span class="link-s small" data-fnc="cityget">(изменить)</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- top links -->
                            <div class="headerlinkmenu col-md-8 col-sm-8 col-xs-12">
                                <ul class="links">
                                    <li class="text-uppercase"><a title="Вход" href="/auth"><span>Вход</span></a></li>
                                    <li class="text-uppercase"><a title="Регистрация" href="/auth/registration"><span>Регистрация</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- header inner -->
                <div class="header-inner">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 col-md-3 col-sm-6 jtv-logo-block">

                                <!-- Header Logo -->
                                <div class="logo">
                                    <a title="Энергофлот" href="/">
                                        <img alt="Изделия электронной техники" title="Изделия электронной техники" src="<?=SITE_TEMPLATE_PATH?>/img/hd_logo.png">
                                    </a>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4 col-sm-6 jtv-top-search">

                                <!-- Search -->

                                <div class="top-search">
                                    <div id="search">
                                        <form method="get" action="/search" id="main-seach-bx">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Поиск по названию" name="s" value="" minlength="4" required="">
                                                <button class="btn-search" type="submit"><i class="fa fa-search"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- End Search -->

                            </div>
                            <div class="col-xs-12 col-md-5 col-sm-6 top-cart">
                                <div class="link-wishlist text-uppercase button-el seach-copy">
                                    <button class="button-green" type="submit" form="main-seach-bx"><span> Поиск</span></button>
                                </div>
                                <div class="link-wishlist text-uppercase strok-bx button-orange">
                                    <a href="/askbyfile">Загрузить<br>файлом</a>
                                </div>
                                <!-- top cart -->
                                <div id="minicart-ajax-rfsh" class="top-cart-contain">
                                    <div class="mini-cart slim-cart">
                                        <div data-toggle="dropdown" data-hover="dropdown" class="basket dropdown-toggle">
                                            <a href="/checkout">
                                                <div class="cart-icon">
                                                    <i class="icon-basket-loaded icons"></i>
                                                </div>
                                                <div class="shoppingcart-inner hidden-xs"><span class="cart-title text-uppercase">Корзина</span> </div>
                                            </a>
                                        </div>
                                        <div>
                                            <div class="top-cart-content">
                                                <div class="block-subtitle">Нет товаров в корзине</div>
                                            </div>
                                        </div>
                                    </div><!-- .mini-cart -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header><!-- end header -->
        <nav class="header-fly">
            <div class="container">
                <div class="row">
                    <div class="mm-toggle-wrap">
                        <div class="mm-toggle"><i class="fa fa-align-justify"></i> </div>
                        <span class="mm-label">Меню</span>
                    </div>
                    <div class="col-main col-sm-9 col-xs-12">
                        <div class="mtmegamenu">
                            <ul class="hidden-xs">
                                <li class="mt-root">
                                    <div class="mt-root-item">
                                        <a href="/">
                                            <div class="title title_font"><span class="title-text">Главная</span></div>
                                        </a>
                                    </div>
                                </li>
                                <li class="mt-root">
                                    <div class="mt-root-item">
                                        <a href="/besplatno">
                                            <div class="title title_font"><span class="title-text">Бесплатно</span></div>
                                        </a>
                                    </div>
                                </li>
                                <li class="mt-root">
                                    <div class="mt-root-item">
                                        <a href="/partneram">
                                            <div class="title title_font"><span class="title-text">Партнерам</span></div>
                                        </a>
                                    </div>
                                </li>
                                <li class="mt-root">
                                    <div class="mt-root-item">
                                        <a href="/about">
                                            <div class="title title_font"><span class="title-text">Компания</span></div>
                                        </a>
                                    </div>
                                </li>
                                <li class="mt-root">
                                    <div class="mt-root-item">
                                        <a href="/contacts">
                                            <div class="title title_font"><span class="title-text">Контакты</span></div>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="mtmegamenu menu-phone">
                            <ul class="hidden-xs">
                                <li class="mt-root">
                                    <div class="mt-root-item">
                                        <a href="#">
                                            <div class="title title_font"><span class="title-text"><i class="fa fa-phone"></i> <span id="geo-phone">+7(861)205-03-77</span></span></div>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

