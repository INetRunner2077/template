
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