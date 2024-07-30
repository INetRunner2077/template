<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use \Bitrix\Main\Application;
use Bitrix\Main\Security\Random;
class Registry extends CBitrixComponent
{
    protected $request;



    public function executeComponent()
    {
        global $USER_FIELD_MANAGER, $USER, $APPLICATION;
        $this->setFrameMode(false);
        $this->arResult["FORM_TARGET"] = $APPLICATION->GetCurPage();
        $arResult["ID"] = intval($USER->GetID());
        $this->arParams['CHECK_RIGHTS'] = $this->arParams['CHECK_RIGHTS'] == 'Y' ? 'Y' : 'N';
        if(!($USER->CanDoOperation('edit_own_profile')) || $arResult["ID"]<=0)
        {
            $APPLICATION->ShowAuthForm("");
            return;
        }


        if($_SERVER["REQUEST_METHOD"]=="POST" && ($_REQUEST["save"] <> '' || $_REQUEST["apply"] <> '') && check_bitrix_sessid())
        {

            if($strError == '')
            {
                $bOk = false;
                $obUser = new CUser;

                $arPERSONAL_PHOTO = $_FILES["PERSONAL_PHOTO"];
                $arWORK_LOGO = $_FILES["WORK_LOGO"];

                $rsUser = CUser::GetByID($arResult["ID"]);
                $arUser = $rsUser->Fetch();
                if($arUser)
                {
                    $arPERSONAL_PHOTO["old_file"] = $arUser["PERSONAL_PHOTO"];
                    $arPERSONAL_PHOTO["del"] = $_REQUEST["PERSONAL_PHOTO_del"];

                    $arWORK_LOGO["old_file"] = $arUser["WORK_LOGO"];
                    $arWORK_LOGO["del"] = $_REQUEST["WORK_LOGO_del"];
                }

                $arEditFields = array(
                    "TITLE",
                    "NAME",
                    "LAST_NAME",
                    "SECOND_NAME",
                    "EMAIL",
                    "LOGIN",
                    "PERSONAL_PROFESSION",
                    'PERSONAL_BIRTHDATE',
                    "PERSONAL_WWW",
                    "PERSONAL_BIRTHDAY",
                    "PERSONAL_PHONE",
                    "PERSONAL_FAX",
                    "PERSONAL_MOBILE",
                    "PERSONAL_STREET",
                    "PERSONAL_CITY",
                    "PERSONAL_STATE",
                    "PERSONAL_ZIP",
                    "PERSONAL_COUNTRY",
                    "PERSONAL_NOTES",
                    "WORK_COMPANY",
                    "WORK_DEPARTMENT",
                    "WORK_POSITION",
                    "WORK_WWW",
                    "WORK_PHONE",
                    "WORK_PAGER",
                    "WORK_STREET",
                    "WORK_MAILBOX",
                    "WORK_CITY",
                    "WORK_STATE",
                    "WORK_ZIP",
                    "WORK_NOTES",
                    "TIME_ZONE",
                    "PHONE_NUMBER",
                );

                $arFields = array();
                foreach($arEditFields as $field)
                {
                    if(isset($_REQUEST[$field]))
                    {
                        $arFields[$field] = $_REQUEST[$field];
                    }
                }

                if(isset($_REQUEST["AUTO_TIME_ZONE"]))
                {
                    $arFields["AUTO_TIME_ZONE"] = ($_REQUEST["AUTO_TIME_ZONE"] == "Y" || $_REQUEST["AUTO_TIME_ZONE"] == "N"? $_REQUEST["AUTO_TIME_ZONE"] : "");
                }

                if($USER->IsAdmin() && isset($_REQUEST["ADMIN_NOTES"]))
                {
                    $arFields["ADMIN_NOTES"] = $_REQUEST["ADMIN_NOTES"];
                }

                $arResult['CAN_EDIT_PASSWORD'] = $arUser['EXTERNAL_AUTH_ID'] == ''
                    || in_array($arUser['EXTERNAL_AUTH_ID'], $arParams['EDITABLE_EXTERNAL_AUTH_ID'], true);

                if($_REQUEST["NEW_PASSWORD"] <> '' && $arResult['CAN_EDIT_PASSWORD'])
                {
                    $arFields["PASSWORD"] = $_REQUEST["NEW_PASSWORD"];
                    $arFields["CONFIRM_PASSWORD"] = $_REQUEST["NEW_PASSWORD_CONFIRM"];
                }

                $arFields["PERSONAL_PHOTO"] = $arPERSONAL_PHOTO;
                $arFields["WORK_LOGO"] = $arWORK_LOGO;

                if($arUser)
                {
                    if($arUser['EXTERNAL_AUTH_ID'] <> '')
                    {
                        $arFields['EXTERNAL_AUTH_ID'] = $arUser['EXTERNAL_AUTH_ID'];
                    }
                }

                $USER_FIELD_MANAGER->EditFormAddFields("USER", $arFields);


                if(empty($arFields['NAME'])) {
                    $this->arResult['ERRORS'][] = 'Обязательно для заполнения: Имя пользователя';
                }

                if(empty($arFields['LAST_NAME'])) {
                    $this->arResult['ERRORS'][] = 'Обязательно для заполнения: Фамилия';
                }

                unset($arFields['EMAIL']);

                if(empty($this->arResult['ERRORS'])) {


                if($obUser->Update($arResult["ID"], $arFields))
                {
                    if (empty($_REQUEST['acc'])) {
                        LocalRedirect('/hello/');
                    }
                }
                else
                {
                    $strError .= $obUser->LAST_ERROR;
                }

                }
            }
        }


        $rsUser = CUser::GetByID($arResult["ID"]);
        if(!$this->arResult["arUser"] = $rsUser->GetNext(false))
        {
            $this->arResult["ID"] = 0;
        }

        $this->arResult["arUser"]["PHONE_NUMBER"] = "";
        if($this->arResult["PHONE_REGISTRATION"])
        {
            if($phone = \Bitrix\Main\UserPhoneAuthTable::getRowById($this->arResult["ID"]))
            {
                $this->arResult["arUser"]["PHONE_NUMBER"] = htmlspecialcharsbx($phone["PHONE_NUMBER"]);
            }
        }

        if (!empty($this->arParams["USER_PROPERTY"]))
        {
            $arUserFields = $USER_FIELD_MANAGER->GetUserFields("USER", $arResult["ID"], LANGUAGE_ID);
            foreach ($arUserFields as $FIELD_NAME => $arUserField)
            {
                if (!in_array($FIELD_NAME, $this->arParams["USER_PROPERTY"]))
                    continue;
                $arUserField["EDIT_FORM_LABEL"] = $arUserField["EDIT_FORM_LABEL"] <> '' ? $arUserField["EDIT_FORM_LABEL"] : $arUserField["FIELD_NAME"];
                $arUserField["EDIT_FORM_LABEL"] = htmlspecialcharsEx($arUserField["EDIT_FORM_LABEL"]);
                $arUserField["~EDIT_FORM_LABEL"] = $arUserField["EDIT_FORM_LABEL"];
                $this->arResult["USER_PROPERTIES"]["DATA"][$FIELD_NAME] = $arUserField;
            }
            if (!empty($this->arResult["USER_PROPERTIES"]["DATA"]))
            {
                $this->arResult["USER_PROPERTIES"]["SHOW"] = "Y";
            }
            $this->arResult["bVarsFromForm"] = $strError != '';
        }

        /* foreach ($this->arResult["USER_PROPERTIES"]['DATA'] as $enum) {
            if($enum['USER_TYPE_ID'] != 'enumeration' or  $enum['VALUE'] == null) { continue; }
             $property_enums = CUserFieldEnum::GetList(Array(), Array("VALUE "=>$enum['VALUE'], 'USER_FIELD_NAME' => $enum['FIELD_NAME'] ));
             if($enum_fields = $property_enums->GetNext())
             {
                 $this->arResult["USER_PROPERTIES"]['DATA'][$enum['FIELD_NAME']]['VALUE_XML'] = $enum_fields['XML_ID'];
             }
        } */


        $this->arResult["BX_SESSION_CHECK"] = bitrix_sessid_post();
        $this->includeComponentTemplate();

    }

}



