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

        $this->arResult["BX_SESSION_CHECK"] = bitrix_sessid_post();
        $this->includeComponentTemplate();

    }

}



