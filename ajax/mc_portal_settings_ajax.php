<?

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

global $USER, $APPLICATION;

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\IO;
/**
 * обработаем запрос настроек портала
 * */

$instance = \Bitrix\Main\Application::getInstance();
$context = $instance->getContext();
$request = $context->getRequest();
$arPostList = $request->toArray();

$arWhiteType = [
    'MCPORTAL_SETTINGS',
];

$arReturn = [
    'status' => 'error',
    'txt' => 'Error! CODE 971'
];
if($USER->IsAdmin() && check_bitrix_sessid() && in_array($arPostList['BX_TYPE'], $arWhiteType))
{
    function am_save_logo_file($arFile, $arRestriction = Array(), $mode = "")
    {
        Loader::includeModule('iblock');
        $oldFileID = \Bitrix\Main\Config\Option::get("altermax.shop", "client_logo".($mode == "retina" ? "_retina" : ""), "");

        $arFile = array_merge(
                \CIBlock::makeFileArray($arFile),
                Array(
                    "del" => ($oldFileID ? "Y" : ""),
                    "old_file" => (intval($oldFileID) > 0 ? intval($oldFileID): 0 ),
                    "MODULE_ID" => "bitrix24",
                )
        );

        $extensions = (array_key_exists("extensions", $arRestriction) && $arRestriction["extensions"] <> '' ? trim($arRestriction["extensions"]) : false);

        $error = \CFile::CheckFile($arFile, /*$max_file_size*/0, false, $extensions);
        if ($error <> '')
        {
            return $error;
        }

        $fileID = (int)CFile::SaveFile($arFile, "bitrix24");

        $file = CFile::ResizeImageGet($fileID, array(
            'width'=>'420',
            'height'=>'100'
        ), BX_RESIZE_IMAGE_PROPORTIONAL, true);

        // добавляем в массив VALUES новую уменьшенную картинку
        $VALUES = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].$file["src"]);
        $fileID = (int)CFile::SaveFile($VALUES, "bitrix24");

        return $fileID;
    }

    switch ($arPostList['BX_TYPE'])
    {
        case 'MCPORTAL_SETTINGS':

            \Bitrix\Main\Config\Option::set("main", "site_name", $arPostList['SITE_NAME']);

            \Bitrix\Main\Config\Option::set("altermax.shop", "site_time_work", $arPostList['TIME_WORK']);

            \Bitrix\Main\Config\Option::set("altermax.shop", "adress_cor", $arPostList['ADRESS_COR']);

            \Bitrix\Main\Config\Option::set("altermax.shop", "email_work", $arPostList['EMAIL_WORK']);

            \Bitrix\Main\Config\Option::set("altermax.shop", "phone_work", $arPostList['PHONE_WORK']);


            if (isset($arPostList["client_logo_retina_del"]) && $arPostList["client_logo_retina_del"] == "Y")
            {
                $mode = $_POST["mode"] == "retina" ? "_retina" : "";

                $fileId = \Bitrix\Main\Config\Option::get("bitrix24", "client_logo".$mode, "");

                \CFile::Delete($fileId);

                \Bitrix\Main\Config\Option::delete('bitrix24', ['name' => "client_logo".$mode]);

                $arReturn = [
                    'status' => 'success',
                    'txt' => 'Данные успешно сохранены'
                ];
            }
            else
            {
                $fileID = am_save_logo_file($arPostList['client_logo_retina'], array("extensions" => "svg,png"), "retina");

                if (intval($fileID))
                {
                    \Bitrix\Main\Config\Option::set("altermax.shop", "client_logo_retina", $fileID);

                    $arReturn = [
                        'status' => 'success',
                        'txt' => 'Данные успешно сохранены'
                    ];
                }
                elseif($fileID)
                {
                    $error = str_replace("<br>", "", $fileID);

                    $arReturn = [
                        'status' => 'error',
                        'txt' => $error
                    ];
                }
                else
                {
                    $arReturn = [
                        'status' => 'success',
                        'txt' => 'Данные успешно сохранены'
                    ];
                }
            }

            break;

    }
}

$APPLICATION->RestartBuffer();

echo \Bitrix\Main\Web\Json::encode($arReturn);

die();