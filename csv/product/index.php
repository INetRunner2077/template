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
    .'/csv/product/orm.php');
require($_SERVER["DOCUMENT_ROOT"]
    .'/csv/customer/orm.php');
require($_SERVER["DOCUMENT_ROOT"]
    .'/csv/producer/orm.php');
require($_SERVER["DOCUMENT_ROOT"]
    .'/csv/orm.php');


if (Application::getConnection()->isTableExists(
  Base::getInstance('Altermax\ProductTable')->getDBTableName()
)) {
    $connection = Application::getConnection();
    $connection->dropTable(\Altermax\ProductTable::getTableName());
    Base::getInstance('Altermax\ProductTable')->createDBTable();
} else {
    Base::getInstance('Altermax\ProductTable')->createDBTable();
}


$file = fopen("products.csv", "r");

\Bitrix\Main\Loader::includeModule('iblock');
// Читаем и разбираем строки CSV
while (($row = fgetcsv($file, 0, ";")) !== false) {


    $dbProducer = \Altermax\ProducerTable::getList(
        array(
            'filter' => array(
                'ID_PRODUCER' => $row[3],
            ),
            'select' => array('ID', 'PRODUCER_NAME')
        )
    );
    if($GetProducer = $dbProducer->fetch()) {
        $producer = $GetProducer['PRODUCER_NAME'];
    }

    $dbCustomer = \Altermax\CustomerTable::getList(
        array(
            'filter' => array(
                'ID_CUSTOMER' => $row[5],
            ),
            'select' => array('ID', 'CUSTOMER_NAME')
        )
    );
    if($GetCustomer = $dbCustomer->fetch()) {
        $customer = $GetCustomer['CUSTOMER_NAME'];
    }

    $dbBitrixSection = \Altermax\SectionTableAlermax::getList(
        array(
            'filter' => array(
                'ID_SECTION' => $row[2],
            ),
            'select' => array('ID', 'ID_BITRIX_SECTION')
        )
    );
    if($GetBitrixSection = $dbBitrixSection->fetch()) {
        $bitrixSection = $GetBitrixSection['ID_BITRIX_SECTION'];
    }

    \Altermax\ProductTable::add(
        array(
            'NAME'        => $row[6],
            'NAME_FIND' => $row[7],
            'PARTNER_ID' => $row[0],
            'DESCRIPTION_ID' => $row[1],
            'SECTION_ID' => $row[2],
            'BITRIX_SECTION_ID' => $bitrixSection,
            'PRODUCER_NAME' => $producer,
            'CURRENCY_ID' => $row[4],
            'CUSTOMER_NAME' => $customer,
            'YEAR_CREATE' => $row[8],
            'MEASURE' => $row[9],
            'QUANTITY' => $row[10],
            'PRICE' => $row[11],
            'VENDOR' => $row[12],
            'TIME_UPDATE' => $row[13],
        )
    );

}

// Закрываем файл
fclose($file);
