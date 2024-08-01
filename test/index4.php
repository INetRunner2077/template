<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
?>


<?

$arrCeventTemplates = Array(
	'NEW_USER' => Array(
		'ACTIVE'=> 'Y',
		'EVENT_NAME' => 'NEW_USER',
		'LID' => Array(SITE_ID),
		'EMAIL_FROM' => 'Регистрация в Интернет-магазине Альтермакс <#DEFAULT_EMAIL_FROM#>',
		'EMAIL_TO' => '#DEFAULT_EMAIL_FROM#',
		'SUBJECT' => 'Регистрация в Интернет-магазине Altermax',
		'BODY_TYPE' => 'html',
		'MESSAGE' => '
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Регистрация в Интернет-магазине Альтермакс</title>
</head>
<body>
<p>
	 Здравствуйте!
</p>
<p>
	 Благодарим за регистрацию в Интернет-магазине<br>
	 Компании Альтермакс "altermax.ru"
</p>
<p>
 <b>Данные для доступа в личный кабинет:</b>
</p>
<p>
	 Ваш логин: #EMAIL# <br>
	 Ваш пароль:#PASSWORD#
</p>
<p>
	 В любое время в личном кабинете Вы можете изменить<br>
	 проверить регистрационные данные и проверить статус<br>
	 Ваших заказов: <a href="https://ressi.ru/personal/private/">личный кабинет</a>
</p>
<p>
	 Благодарим за оказанное доверие.<br>
	 Мы внимательно относимся ко всем покупателям и сделаем<br>
	 все возможное для эффективного сотрудничества с Вами.
</p>
 В случае возникновения вопросов, пожалуйста, обращайтесь к<br>
 нам любым удобным способом:
<p>
</p>
<p>
	 Телефоны для связи:<br>
	 Офис: г. Воронеж: <span class="js-phone-number">+7(473)212-03-77</span> <br>
	 Офис: г. Краснодар: <span class="js-phone-number">+7(861)205-03-77</span><br>
	 Офис: г. Москва: <span class="js-phone-number">+7(495)120-03-77</span><br>
</p>
<p>
	 Email: <a href="/compose?To=altermax2@yandex.ru">altermax2@yandex.ru</a>
</p>
<p>
	 С Уважением, Компания «Altermax»<br>
	 Altermax.ru
</p>
 <br>
 
</body>
</html>',
	),);



	$em = new CEventMessage;

		$res_em = $em->Add($arrCeventTemplates['NEW_USER']);

		if(!$res_em){
			echo $em->LAST_ERROR;
		}
		else{
			echo 'Шаблон создан '.$res_em.'<br />';
			COption::SetOptionInt("altermax.shop", "EMAIL_NEW_USER", $res_em);
		}



echo COption::GetOptionInt("altermax.shop", "EMAIL_NEW_USER");


?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>







