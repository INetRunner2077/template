
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новости");

?>
<section class="blog_post" id="home">
    <div class="container">
        <div class="row">
            <!-- Center colunm-->
            <div class="col-xs-12 col-sm-9 col-sm-push-3" id="center_column">
                <div class="center_column">
                    <?$APPLICATION->IncludeComponent(
                        "altermax:slider.altermax",
                        "slider",
                        Array(
                            "GROUPS" => "",
                            "IBLOCK_ID" => \Altermax\Shop\Tools\Content::getIBlockIdByCode( 'advertising', 'advertising'),
                            "IBLOCK_TYPE" => "altermax_advertising"
                        )
                    );?>


                    <?$APPLICATION->IncludeComponent(
                        "bitrix:news",
                        "",
                        Array(
                            "ADD_ELEMENT_CHAIN" => "N",
                            "ADD_SECTIONS_CHAIN" => "Y",
                            "AJAX_MODE" => "N",
                            "AJAX_OPTION_ADDITIONAL" => "",
                            "AJAX_OPTION_HISTORY" => "N",
                            "AJAX_OPTION_JUMP" => "N",
                            "AJAX_OPTION_STYLE" => "Y",
                            "BROWSER_TITLE" => "-",
                            "CACHE_FILTER" => "N",
                            "CACHE_GROUPS" => "Y",
                            "CACHE_TIME" => "36000000",
                            "CACHE_TYPE" => "A",
                            "CHECK_DATES" => "Y",
                            "DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
                            "DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
                            "DETAIL_DISPLAY_TOP_PAGER" => "N",
                            "DETAIL_FIELD_CODE" => array("",""),
                            "DETAIL_PAGER_SHOW_ALL" => "Y",
                            "DETAIL_PAGER_TEMPLATE" => "",
                            "DETAIL_PAGER_TITLE" => "Страница",
                            "DETAIL_PROPERTY_CODE" => array("",""),
                            "DETAIL_SET_CANONICAL_URL" => "N",
                            "DISPLAY_BOTTOM_PAGER" => "Y",
                            "DISPLAY_DATE" => "Y",
                            "DISPLAY_NAME" => "Y",
                            "DISPLAY_PICTURE" => "Y",
                            "DISPLAY_PREVIEW_TEXT" => "Y",
                            "DISPLAY_TOP_PAGER" => "N",
                            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                            "IBLOCK_ID" => "1",
                            "IBLOCK_TYPE" => "news",
                            "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
                            "LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
                            "LIST_FIELD_CODE" => array("",""),
                            "LIST_PROPERTY_CODE" => array("",""),
                            "MESSAGE_404" => "",
                            "META_DESCRIPTION" => "-",
                            "META_KEYWORDS" => "-",
                            "NEWS_COUNT" => "20",
                            "PAGER_BASE_LINK_ENABLE" => "N",
                            "PAGER_DESC_NUMBERING" => "N",
                            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                            "PAGER_SHOW_ALL" => "N",
                            "PAGER_SHOW_ALWAYS" => "N",
                            "PAGER_TEMPLATE" => ".default",
                            "PAGER_TITLE" => "Новости",
                            "PREVIEW_TRUNCATE_LEN" => "",
                            "SEF_MODE" => "N",
                            "SET_LAST_MODIFIED" => "N",
                            "SET_STATUS_404" => "N",
                            "SET_TITLE" => "Y",
                            "SHOW_404" => "N",
                            "SORT_BY1" => "ACTIVE_FROM",
                            "SORT_BY2" => "SORT",
                            "SORT_ORDER1" => "DESC",
                            "SORT_ORDER2" => "ASC",
                            "STRICT_SECTION_CHECK" => "N",
                            "USE_CATEGORIES" => "N",
                            "USE_FILTER" => "N",
                            "USE_PERMISSIONS" => "N",
                            "USE_RATING" => "N",
                            "USE_REVIEW" => "N",
                            "USE_RSS" => "N",
                            "USE_SEARCH" => "N",
                            "USE_SHARE" => "N",
                            "VARIABLE_ALIASES" => Array("ELEMENT_ID"=>"ELEMENT_ID","SECTION_ID"=>"SECTION_ID")
                        )
                    );?>

                    <div class="slider-items-products">
                        <div id="latest-news-slider" class="product-flexslider hidden-buttons">
                            <div class="slider-items slider-width-col6">
                                <div class="item">
                                    <div class="blog-box">
                                        <a href="/vashi_otzyvy_-_eto_nashe_zoloto_">
                                            <img class="primary-img" src="/images/2024_08/2024-08-12_10-57-02_384x226.png" alt="HTML template">
                                        </a>
                                        <div class="blog-btm-desc">
                                            <div class="blog-top-desc">
                                                <div class="blog-date"> 07 авг 2024 </div>
                                                <h5><a href="/vashi_otzyvy_-_eto_nashe_zoloto_">Ваши отзывы - это наше золото! ✨</a></h5>
                                            </div>
                                            <p>Ваши отзывы - это наше золото!&nbsp;
                                                &nbsp;
                                                Мы очень ценим ваши отзывы, потому что они помогают...</p>
                                            <a class="read-more" href="/vashi_otzyvy_-_eto_nashe_zoloto_"> Подробнее</a> </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="blog-box">
                                        <a href="/top_5_vybor_pokupateley__tovary_dlya_promyshlennogo_oborudovaniya_za_iyul">
                                            <img class="primary-img" src="/images/2024_07/2024-07-18_14-12-34_384x226.png" alt="HTML template">
                                        </a>
                                        <div class="blog-btm-desc">
                                            <div class="blog-top-desc">
                                                <div class="blog-date"> 18 июл 2024 </div>
                                                <h5><a href="/top_5_vybor_pokupateley__tovary_dlya_promyshlennogo_oborudovaniya_za_iyul">ТОП 5  ВЫБОР ПОКУПАТЕЛЕЙ : ТОВАРЫ ДЛЯ ПРО...</a></h5>
                                            </div>
                                            <p>2РТТ32КПЭ10Ш15В Вилка кабельная разъём
                                                Цилиндрический низкочастотный соединитель пылебрызгозащищ...</p>
                                            <a class="read-more" href="/top_5_vybor_pokupateley__tovary_dlya_promyshlennogo_oborudovaniya_za_iyul"> Подробнее</a> </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="blog-box">
                                        <a href="/v_rossii_uspeshno_zaversheno_korpusirovanie_testovoy_partii_slojnyh_mnogovyvodnyh_mikroshem">
                                            <img class="primary-img" src="/images/2023_06/gs_group.JPG" alt="HTML template">
                                        </a>
                                        <div class="blog-btm-desc">
                                            <div class="blog-top-desc">
                                                <div class="blog-date"> 05 июл 2024 </div>
                                                <h5><a href="/v_rossii_uspeshno_zaversheno_korpusirovanie_testovoy_partii_slojnyh_mnogovyvodnyh_mikroshem">В России успешно завершено корпусирование...</a></h5>
                                            </div>
                                            <p>Корпусирование микросхем осуществлялось по технологии Flip-Chip (метод перевернутого кристалла) в...</p>
                                            <a class="read-more" href="/v_rossii_uspeshno_zaversheno_korpusirovanie_testovoy_partii_slojnyh_mnogovyvodnyh_mikroshem"> Подробнее</a> </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="blog-box">
                                        <a href="/nayden_sposob_sozdavat_akkumulyatory_menshe_goroshiny_s_udelnoy_emkostyu_v_100_raz_vyshe">
                                            <img class="primary-img" src="/images/2024_06/td6_384x226.jpg" alt="HTML template">
                                        </a>
                                        <div class="blog-btm-desc">
                                            <div class="blog-top-desc">
                                                <div class="blog-date"> 18 июн 2024 </div>
                                                <h5><a href="/nayden_sposob_sozdavat_akkumulyatory_menshe_goroshiny_s_udelnoy_emkostyu_v_100_raz_vyshe">Найден способ создавать аккумуляторы мень...</a></h5>
                                            </div>
                                            <p>Японская TDK придумала, как можно делать аккумуляторы для мобильной электроники одновременно и ко...</p>
                                            <a class="read-more" href="/nayden_sposob_sozdavat_akkumulyatory_menshe_goroshiny_s_udelnoy_emkostyu_v_100_raz_vyshe"> Подробнее</a> </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="blog-box">
                                        <a href="/gigaio_i_sourcecode_zapuskayut_pervyy_v_istorii_superkompyuter_iskusstvennogo_intellekta_razmerom_s_chemodan2">
                                            <img class="primary-img" src="/images/2024_05/p7rjbvdvxihfsyzyn4zrjv-970-80_384x226.png" alt="HTML template">
                                        </a>
                                        <div class="blog-btm-desc">
                                            <div class="blog-top-desc">
                                                <div class="blog-date"> 03 июн 2024 </div>
                                                <h5><a href="/gigaio_i_sourcecode_zapuskayut_pervyy_v_istorii_superkompyuter_iskusstvennogo_intellekta_razmerom_s_chemodan2">GigaIO и SourceCode запускают первый в ис...</a></h5>
                                            </div>
                                            <p>Четыре графических процессора, 246 ТБ встроенной памяти и встроенный блок питания мощностью 2500 ...</p>
                                            <a class="read-more" href="/gigaio_i_sourcecode_zapuskayut_pervyy_v_istorii_superkompyuter_iskusstvennogo_intellekta_razmerom_s_chemodan2"> Подробнее</a> </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="blog-box">
                                        <a href="/roselektronika_razrabotala_gerkonovye_datchiki_vzloma">
                                            <img class="primary-img" src="/images/2024_05/ruselectronics_logo_1_ru_01_384x226.jpg" alt="HTML template">
                                        </a>
                                        <div class="blog-btm-desc">
                                            <div class="blog-top-desc">
                                                <div class="blog-date"> 08 май 2024 </div>
                                                <h5><a href="/roselektronika_razrabotala_gerkonovye_datchiki_vzloma">«Росэлектроника» разработала герконовые д...</a></h5>
                                            </div>
                                            <p>Холдинг «Росэлектроника» госкорпорации «Ростех» создал линейку охранных д...</p>
                                            <a class="read-more" href="/roselektronika_razrabotala_gerkonovye_datchiki_vzloma"> Подробнее</a> </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- .product-flexslider -->
                    </div>


                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_RECURSIVE" => "Y",
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "standard.php",
                            "PATH" => "/bitrix/modules/altermax.shop/include/banner.php"
                        )
                    );?>


                    <!-- banners -->
                    <div class="banner-section">
                        <div class="row">
                            <div class="col-sm-6">
                                <figure> <a href="#" target="_self" class="image-wrapper"><img src="images/banners/banner-provider.jpg" alt="banner laptop"></a></figure>
                            </div>
                            <div class="col-sm-6">
                                <figure> <a href="#" target="_self" class="image-wrapper"><img src="images/banners/banner-track.jpg" alt="banner moblie"></a></figure>
                            </div>
                        </div>
                    </div>
                    <!-- banners -->
                    <!-- SEO text -->
                    <div class="text-block seo-text row">
                        <div class="col-xs-12">
                            <div class="best-title text-left">
                                <h2>О компании</h2>
                            </div>
                            <div class="text-post" style="padding-top: 12px;">
                                <p>Компания «Энергофлот» входит в состав холдинга «База Электроники» и осуществляет поставки промышленной электроники, электротехнического оборудования, измерительных приборов, средств связи, кабельной продукции, КИПиА, паяльного оборудования и других изделий электронной техники в регионах Южного и Северо-Кавказского Федеральных округов. Тесное сотрудничество с ведущими производителями на территории РФ и за рубежом, позволяют осуществлять поставки по комфортным ценам и в кратчайшие сроки. Основным преимуществом Компании является широкий спектр поставляемой продукции – более 7 миллионов наименований.</p>
                                <p>За почти 25 лет работы холдинга, мы накопили огромный опыт и уверенную репутацию надежного поставщика, и будем рады сделать все возможное для взаимовыгодного сотрудничества с заказчиками не только в пределах указанных выше регионов, но и расположенных во всех уголках РФ.</p>
                            </div>
                        </div>
                    </div>
                    <!-- .SEO text -->
                </div><!-- .center_column -->
            </div><!-- ./ Center colunm -->
            <!-- Left colunm -->
            <!-- Left colunm -->


            <?$APPLICATION->IncludeComponent(
                "altermax:section",
                "sections_list_danil",
                array(
                    "IBLOCK_TYPE" => "altermax_catalog",
                    "IBLOCK_ID" =>  \Altermax\Shop\Tools\Content::getIBlockIdByCode( 'catalog_altermax', 'altermax_catalog'),
                    "DISPLAY_PANEL" => '',
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => 3600,
                    "CACHE_GROUPS" => "N",
                    "COUNT_ELEMENTS" => "N",
                    "SECTION_URL" => '',
                    "SECTIONS_LIST_PREVIEW_DESCRIPTION" => '',
                    "SECTIONS_LIST_PREVIEW_PROPERTY" => '',
                    "SHOW_SUBSECTION" => '',
                    "SHOW_SECTION_LIST_PICTURES" => '',
                    "TOP_DEPTH" => (($arParams["SECTION_TOP_DEPTH"]&&$arParams["SECTION_TOP_DEPTH"]<=2)?$arParams["SECTION_TOP_DEPTH"]:2),
                    "COMPONENT_TEMPLATE" => "sections_list_danil",
                    "SECTION_ID" => '',
                    "SECTION_CODE" => "",
                    "COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE",
                    "ADDITIONAL_COUNT_ELEMENTS_FILTER" => "additionalCountFilter",
                    "HIDE_SECTIONS_WITH_ZERO_COUNT_ELEMENTS" => "N",
                    "SECTION_FIELDS" => array(
                        0 => "",
                        1 => "",
                    ),
                    "SECTION_USER_FIELDS" => array(
                        0 => "",
                        1 => "",
                    ),
                    "FILTER_NAME" => "sectionsFilter",
                    "CACHE_FILTER" => "N",
                    "ADD_SECTIONS_CHAIN" => "Y"
                ),
                $component
            ); ?>


        </div>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>