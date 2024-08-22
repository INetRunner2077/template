<?

namespace Altermax;

require_once($_SERVER["DOCUMENT_ROOT"]
    ."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->SetTitle("find");
?><?


use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;

define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
\Bitrix\Main\Loader::includeModule('iblock');


require($_SERVER["DOCUMENT_ROOT"]
    .'/csv/customer/orm.php');

if (Application::getConnection()->isTableExists(
  Base::getInstance('Altermax\CustomerTable')->getDBTableName()
)) {
    $connection = Application::getConnection();
    $connection->dropTable(\Altermax\CustomerTable::getTableName());
    Base::getInstance('Altermax\CustomerTable')->createDBTable();
} else {
    Base::getInstance('Altermax\CustomerTable')->createDBTable();
}
$file = fopen("customers.csv", "r");

\Bitrix\Main\Loader::includeModule('iblock');

// Читаем и разбираем строки CSV
while (($row = fgetcsv($file, 0, ";")) !== false) {

    \Altermax\CustomerTable::add(
        array(
            'CUSTOMER_NAME' => $row[1],
            'ID_CUSTOMER' => $row[0],
        )
    );

}

// Закрываем файл
fclose($file);