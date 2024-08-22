<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;
use \Bitrix\Main\Loader;
require_once($_SERVER["DOCUMENT_ROOT"]
    ."/bitrix/modules/main/include/prolog_before.php");

class AddProductAltermax {

    public static $IBLOCK_ID = 45;

    public static function Go() {

    //    self::CreateSection();
    //    self::CreateProducer();
      //  self::CreateCustomer();
        self::CreateProduct();

    }

    public static function CreateSection() {

      Loader::includeModule('catalog');

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
                    "IBLOCK_ID"         => self::$IBLOCK_ID,
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
                    "IBLOCK_ID"         => self::$IBLOCK_ID,
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

    }

    public static function CreateProducer() {


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

    }

    public static function CreateCustomer() {

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
    }

    public static function CreateProduct() {

        require($_SERVER["DOCUMENT_ROOT"]
            .'/csv/product/orm.php');
        require($_SERVER["DOCUMENT_ROOT"]
            .'/csv/customer/orm.php');
        require($_SERVER["DOCUMENT_ROOT"]
            .'/csv/producer/orm.php');
        require($_SERVER["DOCUMENT_ROOT"]
            .'/csv/section/orm.php');


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

            $dbBitrixSection = \Altermax\SectionTable::getList(
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
                    'QUANTITY' => $row[11],
                    'PRICE' => $row[11],
                    'VENDOR' => $row[24],
                    'TIME_UPDATE' => $row[25],
                )
            );

        }

// Закрываем файл
        fclose($file);
    }

    public static function CreateProductBitrix() {

        $dbProducer = \Altermax\ProducerTable::getList(
            array(
                'filter' => array(),
                'select' => array('NAME', 'BITRIX_SECTION_ID', 'PRODUCER_NAME', 'YEAR_CREATE', 'QUANTITY', 'PRICE', 'VENDOR')
            )
        );

        $el = new \CIBlockElement;
        while ($product = $dbProducer->fetch()) {

            self::$MANUFACTURERID = \CIBlockProperty::GetByID("VENDOR_ALTERMAX", self::$IBLOCKID)->GetNext()['ID'];

         //   $PROP[self::$MANUFACTURERID] = $product['PRODUCER_NAME'];

            $Productarray = array(
                'IBLOCK_SECTION_ID' => $product['BITRIX_SECTION_ID'],
                'IBLOCK_ID'         => self::$IBLOCKID,
              //  'PROPERTY_VALUES'   => $PROP,
                'NAME'              => $product['NAME'],
                'ACTIVE'            => 'Y',
            );

            if ($PRODUCT_ID = $el->Add($Productarray)) {
                file_put_contents(
                    dirname(__FILE__).'/create.txt',
                    $PRODUCT_ID." ",
                    FILE_APPEND
                );
            } else {
                $productArray['error'] = 'Элемент не создан';
                $productArray['mass'] = $Productarray;
                file_put_contents(
                    dirname(__FILE__).'/bigErrorMass.txt',
                    print_r($productArray, true),
                    FILE_APPEND
                );
            }

            $QUANTITY = $Productarray['QUANTITY'];


            $product = array(
                'ID'       => $PRODUCT_ID,
                'QUANTITY' => $QUANTITY,
            );
            \Bitrix\Catalog\Model\Product::add(
                self::makeArrayProduct($PRODUCT_ID, $product)
            );

            $price = [
                'PRODUCT_ID'       => $PRODUCT_ID,
                'PRICE'            => $product['PRICE'],
                'CATALOG_GROUP_ID' => '1',
                'CURRENCY'         => 'RUB',
                ];
            \Bitrix\Catalog\Model\Price::add($price);


        }

    }




}
AddProductAltermax::Go();