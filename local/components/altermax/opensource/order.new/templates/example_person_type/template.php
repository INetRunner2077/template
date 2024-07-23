<?php

use Bitrix\Main\Error;
use Bitrix\Sale\Order;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var OpenSourceOrderComponent $component */
CJSCore::Init(['jquery']);
$this->addExternalJs($templateFolder . '/selectize/selectize.js');
$this->addExternalCss($templateFolder . '/selectize/selectize.default.css');
$this->addExternalCss($templateFolder . '/selectize/selectize.dropdown.css');
$this->addExternalJs($templateFolder . '/fias-api/src/js/core.js');
$this->addExternalJs($templateFolder . '/fias-api/src/js/fias.js');
$this->addExternalJs($templateFolder . '/fias-api/src/js/fias_zip.js');
$this->addExternalCss($templateFolder . '/fias-api/src/css/style.css');
?>
<div class="os-order">

    <? 
    if($arResult['REGISTER'] == 'N') {
        include 'register.php';
    }

    if($_REQUEST['step'] != '2' and $arResult['REGISTER'] != 'N') {
        include 'step1.php';
    }

    if ($component->order instanceof Order && count($component->order->getBasket()) > 0) {

       include 'form.php';

    } 
    ?>
</div>

<div class="popup-window-overlay bx-step-opacity" id="popup-window-overlay-loading_screen"></div>
<div class="" id="loading_screen">
    <img class="loading_screen_ressi" src="<?=$templateFolder?>/img/reload.png">
</div>



