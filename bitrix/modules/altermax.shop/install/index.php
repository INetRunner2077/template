<?
use Bitrix\Main\IO;
use Bitrix\Main\Application;
use Altermax\Shop\Admin\Menu;

class altermax_shop extends CModule {
 
	public $MODULE_ID = 'altermax.shop';
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	/**
	* Инициализация модуля для страницы "Управление модулями"
	*/
	function __construct() {

		include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/version.php");
		$this->MODULE_NAME       	= GetMessage( 'CITFACT_EMPTY_MODULNAME' );
		$this->MODULE_DESCRIPTION	= GetMessage( 'CITFACT_EMPTY_DESC' );
		$this->MODULE_VERSION		= $arModuleVersion['VERSION'];
		$this->MODULE_VERSION_DATE	= $arModuleVersion['VERSION_DATE'];
		$this->PARTNER_NAME			= GetMessage("CITFACT_OPTIONS_PARTNER_NAME");
		$this->PARTNER_URI			= GetMessage("CITFACT_OPTIONS_PARTNER_URI");
	}
	
	
	
	/**
	* Устанавливаем модуль
	*/
	public function DoInstall() {

		$this->InstallEvents();
		$this->InstallFiles();
		$this->ChangeTemplate();
		$this->InstalIblock();
		$this->InstallUTF();
		$this->registerModule();

		return true;
	}

	/**
	 * Установка пользовательских свойств
	 * */
	public static function InstallUTF() {

		/*  Создание пользовательского поля 'должность' */
		$oUserTypeEntity    = new CUserTypeEntity();

		$aUserFields    = array(
			/*
            *  Идентификатор сущности, к которой будет привязано свойство.
            * Для секция формат следующий - IBLOCK_{IBLOCK_ID}_SECTION
            */
			"ENTITY_ID" => "USER",
			/* Код поля. Всегда должно начинаться с UF_ */
			'FIELD_NAME'        => 'UF_POST_ALTERMAX',
			/* Указываем, что тип нового пользовательского свойства строка */
			'USER_TYPE_ID'      => 'string',
			/*
            * XML_ID пользовательского свойства.
            * Используется при выгрузке в качестве названия поля
            */
			/* Сортировка */
			'SORT'              => 500,
			/* Является поле множественным или нет */
			'MULTIPLE'          => 'N',
			/* Обязательное или нет свойство */
			'MANDATORY'         => 'N',
			/*
            * Показывать в фильтре списка. Возможные значения:
            * не показывать = N, точное совпадение = I,
            * поиск по маске = E, поиск по подстроке = S
            */
			'SHOW_FILTER'       => 'N',
			/*
            * Не показывать в списке. Если передать какое-либо значение,
            * то будет считаться, что флаг выставлен.
            */
			'SHOW_IN_LIST'      => '',
			'SETTINGS'          => array(
				/* Значение по умолчанию */
				'DEFAULT_VALUE' => '',
				/* Размер поля ввода для отображения */
				'SIZE'          => '20',
				/* Количество строчек поля ввода */
				'ROWS'          => '1',
				/* Минимальная длина строки (0 - не проверять) */
				'MIN_LENGTH'    => '0',
				/* Максимальная длина строки (0 - не проверять) */
				'MAX_LENGTH'    => '0',
				/* Регулярное выражение для проверки */
				'REGEXP'        => '',
			),
			'EDIT_IN_LIST'      => '',
			/* Значения поля участвуют в поиске */
			'IS_SEARCHABLE'     => 'N',

			/* Подпись в форме редактирования */
			'EDIT_FORM_LABEL'   => array(
				'ru'    => 'Должность',
			),
			/* Заголовок в списке */
			'LIST_COLUMN_LABEL' => array(
				'ru'    => 'Должность',
			),
			/* Подпись фильтра в списке */
			'LIST_FILTER_LABEL' => array(
				'ru'    => 'Должность',
			),
			/* Сообщение об ошибке (не обязательное) */
			'ERROR_MESSAGE'     => array(
				'ru'    => 'Должность',
			),
			/* Помощь */
			'HELP_MESSAGE'      => array(
				'ru'    => '',
			),
		);

		$iUserFieldId   = $oUserTypeEntity->Add($aUserFields);
		/*  Создание пользовательского поля 'должность' */

		/*  Создание пользовательского поля 'юр адрес' */
		$aUserFields['FIELD_NAME'] = 'UF_LEGAL_ADRESS_ALTERMAX';
		$aUserFields['EDIT_FORM_LABEL']['ru'] = 'Юридический адрес';
		$aUserFields['LIST_COLUMN_LABEL']['ru'] = 'Юридический адрес';
		$aUserFields['LIST_FILTER_LABEL']['ru'] = 'Юридический адрес';
		$aUserFields['ERROR_MESSAGE']['ru'] = 'Юридический адрес';
		$iUserFieldId   = $oUserTypeEntity->Add($aUserFields);
		/*  Создание пользовательского поля 'юр адрес' */

		/*  Создание пользовательского поля 'ИНН'  */
		$aUserFields['FIELD_NAME'] = 'UF_INN_ALTERMAX';
		$aUserFields['EDIT_FORM_LABEL']['ru'] = 'ИНН';
		$aUserFields['LIST_COLUMN_LABEL']['ru'] = 'ИНН';
		$aUserFields['LIST_FILTER_LABEL']['ru'] = 'ИНН';
		$aUserFields['ERROR_MESSAGE']['ru'] = 'ИНН';
		$iUserFieldId   = $oUserTypeEntity->Add($aUserFields);
		/*  Создание пользовательского поля 'юр адрес  */

		/*  Создание пользовательского поля 'Наименование организации'  */
		$aUserFields['FIELD_NAME'] = 'UF_NAME_ORGANIZATION_ALTERMAX';
		$aUserFields['EDIT_FORM_LABEL']['ru'] = 'Наименование организации';
		$aUserFields['LIST_COLUMN_LABEL']['ru'] = 'Наименование организации';
		$aUserFields['LIST_FILTER_LABEL']['ru'] = 'Наименование организации';
		$aUserFields['ERROR_MESSAGE']['ru'] = 'Наименование организации';
		$iUserFieldId   = $oUserTypeEntity->Add($aUserFields);
		/*  Создание пользовательского поля 'юр адрес  */

		/*  Создание пользовательского поля 'Наименование организации'  */
		$aUserFields['FIELD_NAME'] = 'UF_OGRNIP_ALTERMAX';
		$aUserFields['EDIT_FORM_LABEL']['ru'] = 'ОГРНИП';
		$aUserFields['LIST_COLUMN_LABEL']['ru'] = 'ОГРНИП';
		$aUserFields['LIST_FILTER_LABEL']['ru'] = 'ОГРНИП';
		$aUserFields['ERROR_MESSAGE']['ru'] = 'ОГРНИП';
		$iUserFieldId   = $oUserTypeEntity->Add($aUserFields);
		/*  Создание пользовательского поля 'юр адрес  */

		/*  Создание пользовательского поля 'Наименование организации'  */
		$aUserFields['FIELD_NAME'] = 'UF_OGRNIP_ALTERMAX';
		$aUserFields['EDIT_FORM_LABEL']['ru'] = 'ОГРНИП';
		$aUserFields['LIST_COLUMN_LABEL']['ru'] = 'ОГРНИП';
		$aUserFields['LIST_FILTER_LABEL']['ru'] = 'ОГРНИП';
		$aUserFields['ERROR_MESSAGE']['ru'] = 'ОГРНИП';
		$iUserFieldId   = $oUserTypeEntity->Add($aUserFields);
		/*  Создание пользовательского поля 'юр адрес  */

		/*  Создание пользовательского поля 'Форма собственности'  */
		$aUserFields['USER_TYPE_ID'] = 'enumeration';
		$aUserFields['SETTINGS'] = array();
		$aUserFields['FIELD_NAME'] = 'UF_OWNERSHIP_ALTERMAX';
		$aUserFields['EDIT_FORM_LABEL']['ru'] = 'Форма собственности';
		$aUserFields['LIST_COLUMN_LABEL']['ru'] = 'Форма собственности';
		$aUserFields['LIST_FILTER_LABEL']['ru'] = 'Форма собственности';
		$aUserFields['ERROR_MESSAGE']['ru'] = 'Форма собственности';
		$iUserFieldId   = $oUserTypeEntity->Add($aUserFields);

		$form['OOO'] = array( 'VALUE' => 'ООО', 'SORT' => 500, 'DEF' => 'N');
		$form['ZAO'] = array( 'VALUE' => 'ЗАО', 'SORT' => 500, 'DEF' => 'N');
		$form['PAO'] = array( 'VALUE' => 'ПАО', 'SORT' => 500, 'DEF' => 'N');
		$form['AO'] = array( 'VALUE' => 'АО', 'SORT' => 500, 'DEF' => 'N');
		$form['FGYP'] = array( 'VALUE' => 'ФГУП', 'SORT' => 500, 'DEF' => 'N');

		$i = 0;
		foreach ($form as $xml => $field) {
			$i++;
			$arAddEnum['n'.$i] = array(
				'XML_ID' => $xml,
				'VALUE'  => $field['VALUE'],
				'DEF'    => $field['DEF'],
				'SORT'   => $i * 10
			);
		}

		$obEnum = new CUserFieldEnum();
		$obEnum->SetEnumValues($iUserFieldId, $arAddEnum);

		/*  Создание пользовательского поля 'Форма собственности'  */


		/*  Создание пользовательского поля 'Тип покупателя'  */
		$aUserFields['USER_TYPE_ID'] = 'enumeration';
		$aUserFields['SETTINGS'] = array();
		$aUserFields['FIELD_NAME'] = 'UF_TYPE_OF_BUYER_ALTERMAX';
		$aUserFields['EDIT_FORM_LABEL']['ru'] = 'Тип покупателя';
		$aUserFields['LIST_COLUMN_LABEL']['ru'] = 'Тип покупателя';
		$aUserFields['LIST_FILTER_LABEL']['ru'] = 'Тип покупателя';
		$aUserFields['ERROR_MESSAGE']['ru'] = 'Тип покупателя';
		$aUserFields['SETTINGS']['DISPLAY'] = 'CHECKBOX';
		$iUserFieldId   = $oUserTypeEntity->Add($aUserFields);
		$type['PRIVATE'] = array( 'VALUE' => 'Частное лицо', 'SORT' => 500, 'DEF' => 'Y');
		$type['INDIVIDUAL'] = array( 'VALUE' => 'Индивидуальный предприниматель', 'SORT' => 500, 'DEF' => 'N');
		$type['ORGANIZATION'] = array( 'VALUE' => 'Организация', 'SORT' => 500, 'DEF' => 'N');
		unset($arAddEnum);
		$i = 0;
		foreach ($type as $xml => $field) {
			$i++;
			$arAddEnum['n'.$i] = array(
				'XML_ID' => $xml,
				'VALUE'  => $field['VALUE'],
				'DEF'    => $field['DEF'],
				'SORT'   => $i * 10
			);
		}


		$obEnum = new CUserFieldEnum();
		$obEnum->SetEnumValues($iUserFieldId, $arAddEnum);


		/*  Создание пользовательского поля 'Тип покупателя'  */

		return true;

	}


	public function InstalIblock() {

		\Bitrix\Main\Loader::IncludeModule('iblock');

		require_once __DIR__ . '/db/IBlockTypesModuleManager.php';
		$iBlockTypesModuleManager = new IBlockTypesModuleManager();
		$iBlockTypesModuleManager->doInstall();
		$res = ImportXMLFile(
			__DIR__ .  "/db/iblock_xml/calalog_altermax.xml",
			'altermax_catalog',
			's1',
			'N',
			'N'
		);
		$res = ImportXMLFile(
			__DIR__ .  "/db/iblock_xml/calalog_altermax.xml",
			'altermax_advertising',
			's1',
			'N',
			'N'
		);

	}

	/**
	 * Метод регистрирует модуль в битрикс
	 */
	public function registerModule()
	{
		RegisterModule($this->MODULE_ID);
	}

	/**
	 * Метод удаляет модуль из списка зарегистрированых
	 */
	public function unRegisterModule()
	{
		UnRegisterModule($this->MODULE_ID);
	}

	/**
	* Удаляем модуль
	*/
	public function DoUninstall() {

		$this->UnInstallEvents();
		$this->unRegisterModule();
		$this->UnInstallFiles();
		return true;

	}
	
	/**
	* Добавляем почтовые события
	*
	* @return bool
	*/
	public function InstallEvents() {
		$eventManager = \Bitrix\Main\EventManager::getInstance();
		$eventManager->registerEventHandlerCompatible('main',"OnBuildGlobalMenu",$this->MODULE_ID, Menu::class,"doOnBuildGlobalMenu");
		return true;
	}
	
	/**
	* Удаляем почтовые события
	*
	* @return bool
	*/
	public function UnInstallEvents() {

		$eventManager = \Bitrix\Main\EventManager::getInstance();
		$eventManager->unRegisterEventHandler('main', 'OnBuildGlobalMenu', $this->MODULE_ID);
		return true;
	}
	
	/**
	* Копируем файлы административной части
	*
	* @return bool
	*/
	public function InstallFiles() {

		global $APPLICATION;
		$adminPages = new IO\Directory(__DIR__ . '/../assets/admin/page/');
		if ($adminPages->isExists()) {
			foreach ($adminPages->getChildren() as $pageFolder) {
				if (!$pageFolder->isDirectory()) {
					continue;
				}

				$childDirectory =  new IO\Directory(__DIR__ . '/../assets/admin/page/' . $pageFolder->getName());

				foreach ($childDirectory->getChildren() as $pageFile) {
					$file1 = new IO\File(Application::getDocumentRoot() .'/bitrix/admin/' . $pageFile->getName());
					$file1->putContents(
						'<?require_once ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/altermax.shop/assets/admin/page/'
						. $pageFolder->getName()
						. '/'
						. $pageFile->getName()
						.'");?>'
					);
				}
			}
		}

		CopyDirFiles(__DIR__ . '/components/', Application::getDocumentRoot() . '/local/components/', true, true);
		CopyDirFiles(__DIR__ . '/templates/', Application::getDocumentRoot() . '/local/templates/', true, true);
		CopyDirFiles(__DIR__ . '/public/', Application::getDocumentRoot(), true, true);


		return true;
	}
	
	/**
	* Удаляем файлы административной части
	*
	* @return bool
	*/
	public function UnInstallFiles() {

		$adminPages = new IO\Directory(__DIR__ . '/../assets/admin/page/');
		if ($adminPages->isExists()) {
			foreach ($adminPages->getChildren() as $pageFolder) {
				if (!$pageFolder->isDirectory()) {
					continue;
				}

				$childDirectory =  new IO\Directory(__DIR__ . '/../assets/admin/page/' . $pageFolder->getName());

				foreach ($childDirectory->getChildren() as $pageFile) {
					(new IO\File(Application::getDocumentRoot().'/bitrix/admin/'.$pageFile->getName()))->delete();
				}
			}
		}

		return true;
	}
    
	/**
	 * Добавляем таблицы в БД
	 *
	 * @return bool
	 */
	 public function InstallDB() {

	 	return true;

	}


	public function ChangeTemplate() {

		$obSite = new CSite();
		$t = $obSite->Update('s1', array(
			'ACTIVE' => "Y",
			'TEMPLATE'=>array(
				array(
					'CONDITION' => "",
					'SORT' => 1,
					'TEMPLATE' => "altermax"
				),
			)
		));
	 	return true;
	}

	/**
	 * Удаляем таблицы из БД
	 *
	 * @return bool
	 */
	 public function UnInstallDB() {

		 return true;

	} 
    

}?>
