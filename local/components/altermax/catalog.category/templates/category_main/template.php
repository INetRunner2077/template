<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
            <div class="col-xs-12 col-sm-9 col-sm-push-3" id="product_column">
                <div class="center_column">
                    <div class="shop-inner">
                        <div class="page-title">
                            <h2>Каталог</h2>
                        </div>
                        <div class="product-grid-area">
                            <ul class="products-grid category-grid">
                                <?foreach( $arResult["SECTIONS"] as $arItems ):
                                $this->AddEditAction($arItems['ID'], $arItems['EDIT_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "ELEMENT_EDIT"));
                                $this->AddDeleteAction($arItems['ID'], $arItems['DELETE_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                                ?>
                                <li class="item col-lg-4 col-md-4 col-sm-6 col-xs-6" id="<?=$this->GetEditAreaId($arItems['ID']);?>">
                                    <div class="product-item">
                                        <div class="item-inner">
                                            <div class="product-thumbnail">
                                                <div class="pr-img-area">
                                                    <a title="<?=$arItems['NAME']?>" href="<?=$arItems['SECTION_PAGE_URL']?>">
                                                        <figure>
                                                            <img class="first-img" src="/upload/iblock/c7f/gvcubgz0gngglav17oy7xprugdmwzmoh.png" alt="<?=$arItems['NAME']?>">
                                                            <img class="hover-img" src="/upload/iblock/c7f/gvcubgz0gngglav17oy7xprugdmwzmoh.png" alt="<?=$arItems['NAME']?>">
                                                        </figure>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="item-info">
                                                <div class="info-inner">
                                                    <div class="item-title"> <a title="<?=$arItems['NAME']?>" href="<?=$arItems['SECTION_PAGE_URL']?>"><?=$arItems['NAME']?></a> </div>
                                                    <div class="item-content">
                                                        <div class="pro-action">
                                                            <button type="button" class="add-to-cart hashref" data-href="<?=$arItems['SECTION_PAGE_URL']?>"><span> Показать</span> </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                               <? endforeach; ?>
                            </ul><!-- .products-grid -->
                        </div><!-- .product-grid-area -->
                    </div><!-- .shop-inner -->
                </div><!-- .center_column -->
            </div><!-- #product_column -->

<?$APPLICATION->IncludeComponent(
    "altermax:section",
    "sections_list_danil",
    array(
        "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
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
            <!-- Left colunm -->
