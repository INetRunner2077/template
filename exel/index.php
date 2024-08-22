<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->SetTitle("find");
?><?


//ini_set("memory_limit", "1024M");
//ini_set("max_execution_time", "0");
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
\Bitrix\Main\Loader::includeModule('catalog');
\Bitrix\Main\Loader::includeModule('iblock');
//$_SERVER['DOCUMENT_ROOT'] = realpath(dirname(__FILE__).'/../..');


class UpdateBaseElectronic
{
    public static $SECTION_PARENT = '';
    public static $PROPERTY_ID = 473;
    public static $IBLOCKID = 44;
    public static $MANUFACTURERID;

    public static $massColumn = array();

    public static function doDownload()
    {
       /* $file = 'http://template/exel/upload.xlsx';
        $ch = curl_init($file);
        $fp = fopen(dirname(__FILE__).'/upload.xlsx', 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);*/

        self::$MANUFACTURERID = \CIBlockProperty::GetByID("VENDOR_ALTERMAX", self::$IBLOCKID)->GetNext()['ID'];

        require_once dirname(__FILE__).'/phpexcel/PHPExcel.php';
        $excel = PHPExcel_IOFactory::load(
            dirname(__FILE__).'/upload.xlsx'
        );
        foreach ($excel->getWorksheetIterator() as $worksheet) {
            $lists[] = $worksheet->toArray();
        }
        foreach ($lists as $list) {
            unset($list[0]);

            self::FindColumn($list[1]);

            unset($list[1]);


            foreach ($list as $row) {
                self::RenameColumn($row);
                if(empty($row['NAME']) or htmlentities($row['NAME']) == "&nbsp;") { continue; }
                    if ($PRODUCT_ID = self::PutElements($row)) {
                        self::putProducts($PRODUCT_ID, $row);
                }
            }
        }
    }

    private static function updateProduct($PRODUCT_ID, $arLoadProductArray)
    {
        \Bitrix\Catalog\Model\Product::update(
            $PRODUCT_ID,
            self::makeArrayProduct(
                $PRODUCT_ID,
                $arLoadProductArray
            )
        );

        if ($priceID = self::getPriceId($PRODUCT_ID)) {
            \Bitrix\Catalog\Model\Price::update(
                $priceID,
                self::makeArrayPrice(
                    $PRODUCT_ID,
                    $arLoadProductArray
                )
            );
        } else {
            file_put_contents(
                dirname(__FILE__).'/bigErrorMass.txt',
                "Не нашел PriceId - ".$PRODUCT_ID.' ',
                FILE_APPEND
            );
            if (!Bitrix\Catalog\Model\Price::add(
                self::makeArrayPrice($PRODUCT_ID, $arLoadProductArray)
            )
            ) {
                $arLoadProductArray['error'] = 'Цена не добавленна';
                $arLoadProductArray['mass'] = self::makeArrayPrice(
                    $PRODUCT_ID,
                    $arLoadProductArray
                );
                file_put_contents(
                    dirname(__FILE__).'/bigErrorMass.txt',
                    print_r($arLoadProductArray, true),
                    FILE_APPEND
                );
            }
        }
    }

    private static function getPriceId($PRODUCT_ID)
    {
        $iterator = Bitrix\Catalog\PriceTable::getList(
            array(
                'select' => array('ID'),
                'filter' => array('PRODUCT_ID' => array($PRODUCT_ID)),
                'order'  => array(),
            )
        );

        if ($row = $iterator->fetch()) {
            return $row['ID'];
        } else {
            return false;
        }
    }

    public static function putProducts($PRODUCT_ID, $arLoadProductArray)
    {
        \Bitrix\Catalog\Model\Product::add(
            self::makeArrayProduct($PRODUCT_ID, $arLoadProductArray)
        );

        \Bitrix\Catalog\Model\Price::add(
            self::makeArrayPrice($PRODUCT_ID, $arLoadProductArray)
        );
    }

    private static function makeArrayProduct($PRODUCT_ID, $productArray)
    {
        $QUANTITY = (int)$productArray['COUNT'];


        $product = array(
            'ID'       => $PRODUCT_ID,
            'QUANTITY' => $QUANTITY,
        );
        if(!empty($productArray['WIDTH']) and (htmlentities($productArray['WIDTH']) != "&nbsp;")) {
            $product['WIDTH'] = $productArray['WIDTH'];
        }
        if(!empty($productArray['WEIGHT']) and (htmlentities($productArray['WEIGHT']) != "&nbsp;")) {
            $product['WEIGHT'] = $productArray['WEIGHT'];
        }
        if(!empty($productArray['LENGTH']) and (htmlentities($productArray['LENGTH']) != "&nbsp;")) {
            $product['LENGTH'] = $productArray['LENGTH'];
        }
        return $product;
    }

    private static function makeArrayPrice($PRODUCT_ID, $productArray)
    {
        if (str_contains($productArray['PRICE'], ',')) {
            $productArray['PRICE'] = strstr($productArray['PRICE'], '.', true);
            $productArray['PRICE'] = str_replace(
                ',',
                '',
                $productArray['PRICE']
            );
        }

        return [
            'PRODUCT_ID'       => $PRODUCT_ID,
            'PRICE'            => $productArray['PRICE'],
            'CATALOG_GROUP_ID' => '1',
            'CURRENCY'         => 'RUB',
        ];
    }


    public static function PutElements($productArray)
    {
        $arLoadIblockArray = self::makeArrayIblock($productArray);
        if(\Bitrix\Main\Loader::includeModule('iblock')) {
            $el = new \CIBlockElement;
            if ($PRODUCT_ID = $el->Add($arLoadIblockArray)) {
                file_put_contents(
                    dirname(__FILE__).'/create.txt',
                    $PRODUCT_ID." ",
                    FILE_APPEND
                );

                return $PRODUCT_ID;
            } else {
                $productArray['error'] = 'Элемент не создан';
                $productArray['mass'] = $arLoadIblockArray;
                file_put_contents(
                    dirname(__FILE__).'/bigErrorMass.txt',
                    print_r($productArray, true),
                    FILE_APPEND
                );
            }
        }
    }

    private static function GetSectionId($str) {

        $sections = explode("/", $str);

        foreach ($sections as $key => $section) {
            if(empty($section)) { unset($sections[$key]); }
            $sections[$key] = preg_replace('/[\s]{2,}/', ' ', trim($section));
        }

        $sections = (array_reverse($sections));

        foreach ($sections as $key => $section) {

            $getsections = \CIBlockSection::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID" => self::$IBLOCKID, "NAME" => $section), false, array());

            if ($section = $getsections->GetNext()) {
                $tmp_id = $section['ID'];
            } else {
                return false;
                break;
            }

            $arSection = self::getParentSection(self::$IBLOCKID,$tmp_id);
            if($arSection) {

                if(array_key_exists ($key + 1, $sections)) {

                    $lev =  self::getParentSection(self::$IBLOCKID, $arSection['IBLOCK_SECTION_ID']);
                    if(self::levenFunc($lev['NAME'], $sections[$key + 1])) {
                        return $arSection['ID'];
                        break;
                    }

                } else {
                    return $arSection['ID'];
                }

            } else {
                false;
                break;
            }



        }

    }


public static function getParentSection($iblock_id,$tmp_id) {

    $arSection = \Bitrix\Iblock\SectionTable::getList(
        [
            'filter' => ['IBLOCK_ID' => $iblock_id, 'ID' => $tmp_id],
            'select' => ['ID', 'IBLOCK_SECTION_ID', 'NAME'],
        ]
    )->fetchAll();

    if($arSection) {
        $arSection = array_shift($arSection);
        return $arSection;
    } else {
        false;
    }

}


    private static function makeArrayIblock($productArray)
    {
       if(!$sectionId =  self::GetSectionId($productArray['PATH_CATALOG'])) { $sectionId =  self::$SECTION_PARENT; }

        $PROP[self::$MANUFACTURERID] = $productArray['MANUFACTURER'];

        $returnArray = array(
            'IBLOCK_SECTION_ID' => $sectionId,
            'IBLOCK_ID'         => self::$IBLOCKID,
            'PROPERTY_VALUES'   => $PROP,
            'NAME'              => $productArray['NAME'],
            'ACTIVE'            => $productArray['ACTIVE'],
            'PREVIEW_TEXT'      => $productArray['PREVIEW_TEXT'],
            'DETAIL_TEXT'       => $productArray['DETAIL_TEXT'],
        );

        //$clonElementParam = self::CheckIssetElementName($productArray['NAME']);

        if (!empty($clonElementParam['PREVIEW_TEXT'])) {
            $returnArray['PREVIEW_TEXT'] = $clonElementParam['PREVIEW_TEXT'];
        }
        if (!empty($clonElementParam['PREVIEW_PICTURE'])) {
            $returnArray['PREVIEW_PICTURE'] = CFile::MakeFileArray(
                $clonElementParam['PREVIEW_PICTURE']
            );
        }
        if (!empty($clonElementParam['DETAIL_PICTURE'])) {
            $returnArray['DETAIL_PICTURE'] = CFile::MakeFileArray(
                $clonElementParam['DETAIL_PICTURE']
            );
        }
        if (!empty($clonElementParam['DETAIL_TEXT'])) {
            $returnArray['DETAIL_TEXT'] = $clonElementParam['DETAIL_TEXT'];
        }
        if (!empty($clonElementParam['IBLOCK_SECTION_ID'])) {
            $returnArray['IBLOCK_SECTION_ID']
                = $clonElementParam['IBLOCK_SECTION_ID'];
        }

        return $returnArray;
    }

    public static function DeleteProduct($productArray)
    {
        $del = true;
        if (!empty($productArray['PROPERTY_CROKS_ID_VALUE'])) {
            $del = false;
        }
        if (!empty($productArray['PROPERTY_PROM_ID_VALUE'])) {
            $del = false;
        }

        if (!empty($productArray['PROPERTY_CML2_TRAITS_VALUE'])) {
            $del = false;
        }
        if (!empty($productArray['EXTERNAL_ID'])) {
            if (strlen($productArray['EXTERNAL_ID']) > 30) {
                $del = false;
            }
        }
        if ($del) {
            $delete = new \CIBlockElement;
            $delete->Delete($productArray['ID']);
        }
    }

    private static function CheckIssetElementName($name)
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        $arFilter = array("IBLOCK_ID" => 47, "NAME" => $name);
        $res = \CIBlockElement::GetList(
            array('cnt' => 1, 'sort' => 'asc'),
            $arFilter,
            false,
            array(),
            array(
                'ID',
                'PREVIEW_TEXT',
                'DETAIL_TEXT',
                'PREVIEW_PICTURE',
                'DETAIL_PICTURE',
                'IBLOCK_SECTION_ID',
                'PROPERTY_CROKS_ID',
                'PROPERTY_PROM_ID',
                'PROPERTY_CML2_TRAITS',
                'EXTERNAL_ID'
            )
        );
        if ($ob = $res->GetNext()) {
            self::DeleteProduct($ob);

            return
                [
                    'PREVIEW_TEXT'      => $ob['PREVIEW_TEXT'],
                    'PREVIEW_PICTURE'   => $ob['PREVIEW_PICTURE'],
                    'DETAIL_PICTURE'    => $ob['DETAIL_PICTURE'],
                    'DETAIL_TEXT'       => $ob['DETAIL_TEXT'],
                    'IBLOCK_SECTION_ID' => $ob['IBLOCK_SECTION_ID'],
                ];
        } else {
            return false;
        }
    }

    private static function CheckIssetElement($baseArt)
    {
        if (empty($baseArt)) {
            return false;
        }
        $arFilter = array(
            "IBLOCK_ID"               => self::$IBLOCKID,
            "MANUFACTURER" => $baseArt
        );
        $res = \CIBlockElement::GetList(
            array('cnt' => 1, 'sort' => 'asc'),
            $arFilter,
            false,
            array(),
            array('ID')
        );
        if ($ob = $res->GetNext()) {
            return $ob['ID'];
        } else {
            return false;
        }
    }

    public static function RenameColumn(&$row)
    {
        foreach (self::$massColumn as $key => $value) {
            $temp[$value] = $row[$key];
        }
        $row = $temp;

        return $row;
    }

    public static function FindColumn($columns)
    {
        foreach ($columns as $idColumn => $value) {
            if (self::levenFunc($value, 'Артикул')) {
                self::$massColumn[$idColumn] = 'ART';
                continue;
            }
            if (self::levenFunc($value, 'Активность')) {
                self::$massColumn[$idColumn] = 'ACTIVE';
                continue;
            }
            if (self::levenFunc($value, 'Доступное количество')) {
                self::$massColumn[$idColumn] = 'COUNT';
                continue;
            }
            if (self::levenFunc($value, 'Название')) {
                self::$massColumn[$idColumn] = 'NAME';
                continue;
            }
            if (self::levenFunc($value, 'Ед.')) {
                self::$massColumn[$idColumn] = 'ED';
                continue;
            }
            if (self::levenFunc($value, 'Базовая цена')) {
                self::$massColumn[$idColumn] = 'PRICE';
                continue;
            }
            if (self::levenFunc($value, 'Поставщик')) {
                self::$massColumn[$idColumn] = 'MANUFACTURER';
                continue;
            }
            if (self::levenFunc($value, 'Путь в каталоге')) {
                self::$massColumn[$idColumn] = 'PATH_CATALOG';
                continue;
            }
            if (self::levenFunc($value, 'Вес (грамм)')) {
                self::$massColumn[$idColumn] = 'WEIGHT';
                continue;
            }
            if (self::levenFunc($value, 'Длина (грамм)')) {
                self::$massColumn[$idColumn] = 'LENGTH';
                continue;
            }
            if (self::levenFunc($value, 'Ширина (грамм)')) {
                self::$massColumn[$idColumn] = 'WIDTH';
                continue;
            }
            if (self::levenFunc($value, 'Описание для анонса')) {
                self::$massColumn[$idColumn] = 'PREVIEW_TEXT';
                continue;
            }
            if (self::levenFunc($value, 'Детальное описание')) {
                self::$massColumn[$idColumn] = 'DETAIL_TEXT';
                continue;
            }
        }
    }

    public static function levenFunc($str1, $str2)
    {
        similar_text($str1, $str2, $perc);

        if ($perc >= 70) {
            return true;
        } else {
            return false;
        }
    }


}

unlink(dirname(__FILE__).'/updater.txt');
unlink(dirname(__FILE__).'/bigErrorMass.txt');
if($USER->IsAdmin()) {
    echo json_encode(array("STATUS"=> "OK"));
    UpdateBaseElectronic::doDownload();
} else {
    echo json_encode(array("STATUS"=> "ERROR"));
}
unlink(dirname(__FILE__).'/elbasepriceMain.xls');

