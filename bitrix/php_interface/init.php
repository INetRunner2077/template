<?
/*
You can place here your functions and event handlers

AddEventHandler("module", "EventName", "FunctionName");
function FunctionName(params)
{
	//code
}
*/


AddEventHandler('main', 'OnBeforeEventSend', Array("MyForm", "my_OnBeforeEventSend"));
class MyForm
{
    public static function my_OnBeforeEventSend(&$arFields, &$arTemplate, &$content)
    {
        $arTemplate['ID'] = 91;
        $arTemplate['EMAIL_TO'] = '#EMAIL#';
        return [
            $arFields,
            $arTemplate,
            $content,
        ];

    }
}

AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("Vendor", "CreateVendor"));

class Vendor {

public static function CreateVendor(&$arFields)
{
    $VendorValue = COption::GetOptionInt("altermax.shop", "VENDOR_ALTERMAX", 1);
    Bitrix\Main\Loader::includeModule('iblock');
    $propVendor = CIBlockProperty::GetByID("VENDOR_ALTERMAX", $arFields['IBLOCK_ID'])->GetNext();
    unset($arFields['PROPERTY_VALUES'][$propVendor['ID']]);

    while(!$vendor_get = self::GetSameProduct($arFields['IBLOCK_ID'], self::NumberFormat($VendorValue,7))) {
        $VendorValue = $VendorValue + 1;
    }
    $arFields['PROPERTY_VALUES'][$propVendor['ID']]['n0']['VALUE'] = $vendor_get;
    COption::SetOptionInt("altermax.shop", "VENDOR_ALTERMAX",$VendorValue+1);
    $arFields = self::CreateClearNameProperty($arFields);
    return $arFields;
}

public static function CreateClearNameProperty($arFields) {

    $propClearName = CIBlockProperty::GetByID("CLEAN_NAME_ALTERMAX", $arFields['IBLOCK_ID'])->GetNext();
    $clearName = preg_replace('/[^ a-zа-я\d]/ui', '', $arFields['NAME']);
    $arFields['PROPERTY_VALUES'][$propClearName['ID']]['n0']['VALUE'] = $clearName;
    return $arFields;
}

    public static function NumberFormat ($digit, $width)
    {
        while (strlen($digit) < $width) {
            $digit = '0'.$digit;
        }

        return $digit;
    }

    public static function GetSameProduct($iblock_id, $value) {

        $arFilter = Array("IBLOCK_ID"=>$iblock_id, 'PROPERTY_VENDOR_ALTERMAX'=> $value);
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), array());
        if($ob = $res->GetNextElement())
        {
         return false;
        }
        else {
            return $value;
        }

    }

}


AddEventHandler("main", "OnAdminTabControlBegin", "MyOnAdminTabControlBegin");
function MyOnAdminTabControlBegin(&$form)
{
    if($form->group == 'IBLOCK_ELEMENT_PROPERTY') {
   $idVendorProp = CIBlockProperty::GetByID("VENDOR_ALTERMAX", false)->Fetch()['ID'];
    if(!empty($form->arFields['PROPERTY_'.$idVendorProp])) {
    ?>
    <script>
        window.onload = function() {
            var prop = document.getElementById('tr_PROPERTY_187');
            prop.querySelector('input').setAttribute('readonly', '');
        };
    </script>
    <?php
    }
  }
}
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate","SaveMyVendor");
function SaveMyVendor(&$arFields)
{
    Bitrix\Main\Loader::includeModule('iblock');
    $propVendor = CIBlockProperty::GetByID("VENDOR_ALTERMAX", $arFields['IBLOCK_ID'])->GetNext();
    $propLast = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], array(), Array("CODE"=>"VENDOR_ALTERMAX"))->Fetch();
    $vendorVal = $propLast['VALUE'];
    unset($arFields['PROPERTY_VALUES'][$propVendor['ID']]);
    $arFields['PROPERTY_VALUES'][$propVendor['ID']]['n0']['VALUE'] = $vendorVal;
    return $arFields;
}




\Bitrix\Main\EventManager::getInstance()->addEventHandlerCompatible(

    'main',

    'OnBeforeUserChangePassword',

    'SendPassword::onBeforeUserChangePassword'

);



\Bitrix\Main\EventManager::getInstance()->addEventHandlerCompatible(

    'main',

    'OnBeforeEventAdd',

    'SendPassword::onBeforeEventAdd'

);


\Bitrix\Main\EventManager::getInstance()->addEventHandlerCompatible(

    'main',

    'OnBeforeUserChangePassword',

    'SendPassword::onBeforeUserChangePassword'

);



\Bitrix\Main\EventManager::getInstance()->addEventHandlerCompatible(

    'main',

    'OnBeforeEventAdd',

    'SendPassword::onBeforeEventAdd'

);



class SendPassword

{

    static  function onBeforeUserChangePassword($arParams)

    {

        self::singleton(true,$arParams["PASSWORD"]);

    }



static function onBeforeEventAdd(&$event, &$lid, &$arFields, &$message_id, &$files)

    {

        if($event=="USER_PASS_CHANGED")

            $arFields["PASSWORD"] = self::singleton();

    }



    static function singleton($write=false,$newValue=false)

    {

        static $value;

        if($write)

            $value = $newValue;

        return $value;

    }

}

?>