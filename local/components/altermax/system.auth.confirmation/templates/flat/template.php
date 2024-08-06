<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}
/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @var array $arParams
 * @var array $arResult
 */

//one css for all system.auth.* forms
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");

//here you can place your own messages
switch($arResult["MESSAGE_CODE"])
{
	case "E01":
		//When user not found
		$class = "alert-warning";
		break;
	case "E02":
		//User was successfully authorized after confirmation
		$class = "alert-success";
		break;
	case "E03":
		//User already confirm his registration
		$class = "alert-warning";
		break;
	case "E04":
		//Missed confirmation code
		$class = "alert-warning";
		break;
	case "E05":
		//Confirmation code provided does not match stored one
		$class = "alert-danger";
		break;
	case "E06":
		//Confirmation was successfull
		$class = "alert-success";
		break;
	case "E07":
		//Some error occured during confirmation
		$class = "alert-danger";
		break;
	default:
		$class = "alert-warning";
}
?>

<?
if($arResult["MESSAGE_TEXT"] <> ''):
	$text = str_replace(array("<br>", "<br />"), "\n", $arResult["MESSAGE_TEXT"]);
?>

<section class="main-container col2-right-layout">
    <div class="main container">
        <div class="row">
            <div class="col col-sm-12 col-xs-12">
                <div class="col-sm-6">

<div class="alert <?=$class?>">
 <?echo nl2br(htmlspecialcharsbx($text))?>
</div>

<?endif?>

<?if($arResult["SHOW_FORM"]):?>

	<form method="post" action="<?echo $arResult["FORM_ACTION"]?>">
		<div class="bx-authform-formgroup-container">
			<label><?echo GetMessage("CT_BSAC_LOGIN")?></label>
			<div class="bx-authform-input-container">
				<input type="text" class="form-control input" name="<?echo $arParams["LOGIN"]?>" maxlength="50" value="<?echo $arResult["LOGIN"]?>" />
			</div>
		</div>

		<div class="bx-authform-formgroup-container">
            <label><?echo GetMessage("CT_BSAC_CONFIRM_CODE")?></label>
			<div class="bx-authform-input-container">
				<input type="text" class="form-control input" name="<?echo $arParams["CONFIRM_CODE"]?>" maxlength="50" value="<?echo $arResult["CONFIRM_CODE"]?>" />
			</div>
		</div>

		<div class="bx-authform-formgroup-container">
			<button type="submit" class="button button-green" value="<?echo GetMessage("CT_BSAC_CONFIRM")?>"><i class="icon-login"></i>&nbsp; <span><?echo GetMessage("CT_BSAC_CONFIRM")?></span></button>
		</div>
		<input type="hidden" name="<?echo $arParams["USER_ID"]?>" value="<?echo $arResult["USER_ID"]?>" />
	</form>

<?elseif(!$USER->IsAuthorized()):?>
	<?$APPLICATION->IncludeComponent("bitrix:system.auth.authorize", "flat", array());?>
<?endif?>

                </div>
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

        </div>
    </div>
</section>




