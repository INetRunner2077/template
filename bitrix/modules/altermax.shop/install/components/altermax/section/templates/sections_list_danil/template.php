<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>

<aside class="sidebar col-xs-12 col-sm-3 col-sm-pull-9">
    <div class="block blog-module fly-menu-bx">
        <div class="sidebar-bar-title">
            <h3>Каталог</h3>
        </div>
        <div class="block_content">
            <div class="mega-menu-category open-start">
                <?foreach( $arResult["SECTIONS"] as $arItems ):
                $this->AddEditAction($arItems['ID'], $arItems['EDIT_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItems['ID'], $arItems['DELETE_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <ul class="nav" id="<?=$this->GetEditAreaId($arItems['ID']);?>">
                    <li <? empty($arItems["SECTIONS"]) ? print 'class="nosub"' : print '' ?>>
                        <a href="<?=$arItems["SECTION_PAGE_URL"]?>"><?=$arItems["NAME"]?></a>
                    <? if($arItems["SECTIONS"]): ?>
                        <div class="wrap-popup">
                            <div class="popup">
                                <div class="row">
                                    <div class="col-md-4 col-sm-6">
                                        <ul class="nav">
                                            <? foreach( $arItems["SECTIONS"] as $arItem ): ?>
                                            <li>
                                                <a class="one-line-cut" title="Микросхемы" href="<?=$arItem["SECTION_PAGE_URL"]?>"><span><?=$arItem["NAME"]?> <? echo $arItem["UF_BX_ELEMENT_CNT"]?'&nbsp;('.$arItem["UF_BX_ELEMENT_CNT"].')':'';?></span></a>
                                            </li>
                                             <? endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <? endif; ?>
                    </li>
                </ul>
                <? endforeach; ?>
            </div><!-- .mega-menu-category -->
        </div><!-- .block_content -->
    </div><!-- .blog-module -->
</aside>