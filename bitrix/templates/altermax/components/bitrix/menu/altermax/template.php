<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>

<?
foreach($arResult as $arItem):
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>
    <li class="mt-root">
	<?if($arItem["SELECTED"]):?>
    <div class="mt-root-item">
        <a href="<?=$arItem["LINK"]?>">
            <div class="title title_font"><span class="title-text"><?=$arItem["TEXT"]?></span></div>
       </a>
    </div>
	<?else:?>
    <div class="mt-root-item">
        <a href="<?=$arItem["LINK"]?>">
            <div class="title title_font"><span class="title-text"><?=$arItem["TEXT"]?></span></div>
        </a>
    </div>
	<?endif?>
    </li>
<?endforeach?>

<?endif?>
