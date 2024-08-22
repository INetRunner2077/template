<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>





<?php
/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if(empty($arResult))
    return "";

$strReturn = '';


$strReturn .= '<div class="breadcrumbs">';
$strReturn .= '<div class="container">';
$strReturn .= '<div class="row">';
$strReturn .= '<div class="col-xs-12">';
$strReturn .= '<ul>';

$itemSize = count($arResult);
for($index = 0; $index < $itemSize; $index++)
{
    $title = htmlspecialcharsex($arResult[$index]["TITLE"]);
    $arrow = '<span>»</span>';

    if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
    {
        $strReturn .= '<li class="home" id="bx_breadcrumb_'.$index.'" itemprop="itemListElement">
                            <a title="'.$title.'" href="'.$arResult[$index]["LINK"].'">'.$title.'</a>
                            '.$arrow.'
                        </li>';
    }
    else
    {

        $strReturn .= '<li> <strong>'.$title.'</strong></li>';
    }
}

$strReturn .= '<ul>';
$strReturn .= '</div>';
$strReturn .= '</div>';
$strReturn .= '</div>';
$strReturn .= '</div>';


return $strReturn;
?>


