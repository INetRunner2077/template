<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
if($arResult['ERROR'] &&$arResult['ERROR_MESSAGE']['TYPE'] === 'ERROR' && $arResult['ERROR_MESSAGE']['ERROR_TYPE'] === 'CHANGE_PASSWORD' &&$arParams['CHANGE_PASSWORD_URL']):?>	
	<?$_SESSION['arAuthResult'] = $APPLICATION->arAuthResult;
	$_SESSION['lastLoginSave'] = $arResult["USER_LOGIN"];
	?>
	<script>
	location.href = '<?=$arParams['CHANGE_PASSWORD_URL'].(strlen($arResult['BACKURL']) ? (strpos($arParams['CHANGE_PASSWORD_URL'], '?') ? '&' : '?').'backurl='.$arResult['BACKURL'] : '')?>';
	</script>
<?else:?>
    <section class="main-container col2-right-layout">
    <div class="main container">
<?if(!$USER->isAuthorized()):?>
	<div class="module-authorization">
		<?if($arResult['SHOW_ERRORS'] == 'Y'):?>
			<?ShowMessage($arResult['ERROR_MESSAGE']);?>
		<?endif;?>		
		<?if($arResult['FORM_TYPE'] == 'login'):?>
			<div class="authorization-cols">
				<div class="col authorization">
					<div class="auth-title"><?=GetMessage("ALLREADY_REGISTERED");?></div>
					<div class="form-block">
						<?$sLoginEqual = COption::GetOptionString('aspro.mshop', 'LOGIN_EQUAL_EMAIL', 'Y');?>
						<div class="form_wrapp">
							<form id="avtorization-form-page" name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=SITE_DIR?>auth/<?=!empty( $_REQUEST["backurl"] ) ? '?backurl='.$_REQUEST["backurl"] : ''?>">
								<?if($arResult["BACKURL"] <> ''):?>
                                    <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
                                <?endif?>
								<?foreach($arResult["POST"] as $key => $value):?>
									<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
								<?endforeach;?>
								<input type="hidden" name="AUTH_FORM" value="Y" />
								<input type="hidden" name="TYPE" value="AUTH" />
								<div class="r form-control">
									<input type="text"  name="USER_LOGIN" placeholder="<?=($sLoginEqual == 'Y' ? GetMessage("EMAIL") : GetMessage("AUTH_LOGIN"));?> *" required maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" size="17" tabindex="7" />
									<?if($_POST["USER_LOGIN"]=='' && isset($_POST["USER_LOGIN"])){?><label class="error"><?=GetMessage("FIELD_REQUIRED")?></label><?}?>
								</div>
								<div class="r form-control">
									<input type="password" placeholder="<?=GetMessage("AUTH_PASSWORD")?> *" class="password" name="USER_PASSWORD" required maxlength="50" size="17" tabindex="8" />
									<?if($_POST["USER_PASSWORD"]=='' && isset($_POST["USER_PASSWORD"])){?>
                                        <label class="error"><?=GetMessage("FIELD_REQUIRED")?></label><?}?>
								</div>
								<?if ($arResult["CAPTCHA_CODE"]):?>
									<div class="captcha-row clearfix">
										<?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:<br />
										<div class="captcha_image">
											<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
											<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
											<div class="captcha_reload"></div>
										</div>
										<div class="captcha_input">
											<input type="text" name="captcha_word" maxlength="50" value="" />
										</div>
									</div>
								<?endif?>
								<div class="but-r">
									<div class="filter block">
										<a class="forgot" href="<?=SITE_DIR?>auth/forgot-password/<?=!empty( $_REQUEST["backurl"] ) ? '?backurl='.$_REQUEST["backurl"] : ''?>" tabindex="9"><?=GetMessage("FORGOT_PASSWORD")?></a>
										<div class="remember">
											<input id="remuser" type="checkbox" tabindex="11" />
											<label for="remuser" tabindex="11"><?=GetMessage("AUTH_REMEMBER_ME")?></label>
										</div>
										<div class="clearboth"></div>
									</div>
									<div class="buttons">
										<button type="submit" class="danil_button_target" name="Login" tabindex="10"><span><?=GetMessage("AUTH_LOGIN_BUTTON")?></span></button>
									</div>
								</div>							
							</form>
						</div>
						
					</div>
				</div>
				<div class="col registration">
					<div class="auth-title"><?=GetMessage("NEW_USER");?></div>
					<div class="form-block">
						<div class="form_wrapp">
							<!--noindex-->
								<a href="<?=SITE_DIR?>auth/registration/<?=!empty( $_REQUEST["backurl"] ) ? '?backurl='.$_REQUEST["backurl"] : ''?>" class="danil_button_register" rel="nofollow">
									<span><?=GetMessage("REGISTER")?></span>
								</a>
							<!--/noindex-->
							<div class="more_text_small">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/top_auth.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("TOP_AUTH_REGISTER")));?>
							</div>
						</div>
					</div>					
				</div>
			</div>
			<?
			elseif($arResult["FORM_TYPE"] == "otp"):
			?>

			<form class="otp" name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
			<?if($arResult["BACKURL"] <> ''):?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?endif?>
				<input type="hidden" name="AUTH_FORM" value="Y" />
				<input type="hidden" name="TYPE" value="OTP" />
				<table width="95%">
					<tr>
						<td colspan="2">
						<?echo GetMessage("auth_form_comp_otp")?><br />
						<input type="text" name="USER_OTP" maxlength="50" value="" size="17" autocomplete="off" /></td>
					</tr>
			<?if ($arResult["CAPTCHA_CODE"]):?>
					<tr>
						<td colspan="2">
						<?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:<br />
						<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
						<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /><br /><br />
						<input type="text" name="captcha_word" maxlength="50" value="" /></td>
					</tr>
			<?endif?>
			<?if ($arResult["REMEMBER_OTP"] == "Y"):?>
					<tr>
						<td valign="top"><input type="checkbox" id="OTP_REMEMBER_frm" name="OTP_REMEMBER" value="Y" /></td>
						<td width="100%"><label for="OTP_REMEMBER_frm" title="<?echo GetMessage("auth_form_comp_otp_remember_title")?>"><?echo GetMessage("auth_form_comp_otp_remember")?></label></td>
					</tr>
			<?endif?>
					<tr>
						<td colspan="2"><input class="button vbig_btn wides" type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" /></td>
					</tr>
					<tr>
						<td colspan="2"><noindex><a href="<?=$arResult["AUTH_LOGIN_URL"]?>" rel="nofollow"><?echo GetMessage("auth_form_comp_auth")?></a></noindex><br /></td>
					</tr>
				</table>
			</form>
		<?endif?>
	</div>
    </div>
    </section>
	<script type="text/javascript">
	if($(window).width() >= 600){
		$('.authorization-cols').equalize({children: '.col .auth-title', reset: true});
		$('.authorization-cols').equalize({children: '.col .form-block', reset: true}); 
	}
	
	$(document).ready(function(){
		$(window).resize();
		
		$(".authorization-cols .col.authorization .soc-avt .row a").click(function(){
			$(window).resize();
		});
		
		$("#avtorization-form-page").validate({
			rules: {
				USER_LOGIN: {
					<?if($sLoginEqual == 'Y'):?>
					email: true,
					<?endif;?>
					required:true
				}
			}
		});
		
		$("form[name=bx_auth_servicesform]").validate(); 
	});
	</script>
<?endif;?>
<script>
BX.loadScript(['<?=Bitrix\Main\Page\Asset::getInstance()->getFullAssetPath('/bitrix/js/main/pageobject/pageobject.js')?>']);
</script>
<?endif;?>