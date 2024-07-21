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
		$this->registerModule();
		return true;
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

	/**
	 * Удаляем таблицы из БД
	 *
	 * @return bool
	 */
	 public function UnInstallDB() {

		 return true;

	} 
    

}?>
