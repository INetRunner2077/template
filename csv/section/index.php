<?


require_once($_SERVER["DOCUMENT_ROOT"]
    ."/bitrix/modules/main/include/prolog_before.php");
?><?


use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;

define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
\Bitrix\Main\Loader::includeModule('catalog');
\Bitrix\Main\Loader::includeModule('iblock');


require($_SERVER["DOCUMENT_ROOT"]
    .'/csv/section/orm.php');

if (Application::getConnection()->isTableExists(Base::getInstance('Altermax\SectionTable')->getDBTableName())) {
    $connection = Application::getConnection();
    $connection->dropTable(\Altermax\SectionTable::getTableName());
    Base::getInstance('Altermax\SectionTable')->createDBTable();
} else {
    Base::getInstance('Altermax\SectionTable')->createDBTable();
}

$file = fopen("sections.csv", "r");

\Bitrix\Main\Loader::includeModule('iblock');
// Читаем и разбираем строки CSV
while (($row = fgetcsv($file, 0, ";")) !== false) {

    $bs = new \CIBlockSection;
    if($row[1] == '\N') {
        $arFields = array(
            "ACTIVE"            => 'Y',
            "IBLOCK_ID"         => 45,
            "NAME"              => $row[6],
        );
    } else {

        $getTask = \Altermax\SectionTable::getList(
            array(
                'filter' => array(
                    'ID_SECTION' => $row[1],
                ),
                'select' => array('ID', 'ID_BITRIX_SECTION', 'ID_SECTION')
            )
        );

        if($task = $getTask->fetch()) {
            $parentSection = $task['ID_BITRIX_SECTION'];
        }

        $arFields = array(
            "ACTIVE"            => 'Y',
            "IBLOCK_SECTION_ID" => $parentSection,
            "IBLOCK_ID"         => 45,
            "NAME"              => $row[6],
        );
    }
    $ID = $bs->Add($arFields);
    $res = ($ID>0);

    if($row[1] == '\N') {
        \Altermax\SectionTable::add(
            array(
                'ID_SECTION'        => $row[0],
                'ID_PARENT_SECTION' => $row[0],
                'ID_BITRIX_SECTION' => $ID,
                'NAME_SECTION' =>     $row[6],
                'ID_PARENT_BITRIX_SECTION' => $ID,
            )
        );
    } else {


        \Altermax\SectionTable::add(
            array(
                'ID_SECTION'        => $row[0],
                'ID_PARENT_SECTION' => $row[1],
                'ID_BITRIX_SECTION' => $ID,
                'NAME_SECTION' =>     $row[6],
                'ID_PARENT_BITRIX_SECTION' => $parentSection,

            )
        );
    }

}

// Закрываем файл
fclose($file);
