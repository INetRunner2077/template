<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php $this->addExternalJS(SITE_TEMPLATE_PATH.'/js/revolution-slider.js'); ?>
<?php $this->addExternalJS(SITE_TEMPLATE_PATH.'/js/jquery.flexslider.js'); ?>
<?php $this->addExternalCss(SITE_TEMPLATE_PATH.'/css/revolution-slider.css'); ?>

<div id="jtv-slideshow">
    <div id="rev_slider_4_wrapper" class="rev_slider_wrapper fullwidthbanner-container">
        <div id="rev_slider_4" class="rev_slider fullwidthabanner">
            <ul>
                <? foreach ($arResult['ITEMS'] as $photo): ?>
                <li data-transition="fade" data-slotamount="7" data-masterspeed="1000" data-thumb="">
                    <img src="<?=$photo['PICTURE_SRC']?>" alt="<?=$photo['NAME']?>" data-bgposition="left top" data-bgfit="cover" data-bgrepeat="no-repeat" alt="banner">
                </li>
                <? endforeach; ?>
            </ul>
            <div class="tp-bannertimer"></div>
        </div>
    </div>
</div>

