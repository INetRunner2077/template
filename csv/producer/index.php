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
\Bitrix\Main\Loader::includeModule('catalog');
\Bitrix\Main\Loader::includeModule('iblock');


Loc::loadMessages(__FILE__);

require($_SERVER["DOCUMENT_ROOT"]
    .'/csv/producer/orm.php');

if (Application::getConnection()->isTableExists(
  Base::getInstance('Altermax\ProducerTable')->getDBTableName()
)) {
    $connection = Application::getConnection();
    $connection->dropTable(\Altermax\ProducerTable::getTableName());
    Base::getInstance('Altermax\ProducerTable')->createDBTable();
} else {
    Base::getInstance('Altermax\ProducerTable')->createDBTable();
}
$file = fopen("producers.csv", "r");

\Bitrix\Main\Loader::includeModule('iblock');
// Читаем и разбираем строки CSV
while (($row = fgetcsv($file, 0, ";")) !== false) {

    \Altermax\ProducerTable::add(
        array(
            'PRODUCER_NAME'        => $row[1],
            'PRODUCER_SITE' => $row[2],
            'ID_PRODUCER' => $row[0],
        )
    );

}

// Закрываем файл
fclose($file);