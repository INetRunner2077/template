<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use \Bitrix\Main\Application;
use Bitrix\Main\Security\Random;
class Registry extends CBitrixComponent
{
    protected $request;

    public function DoRegister() {
        global $APPLICATION, $DB, $USER;
        if (!$APPLICATION->CaptchaCheckCode($this->request->get("captcha_word"), $this->request->get("captcha_sid"))) {
            $this->arResult["ERRORS"][] = 'Капча не верная';
        }

        if(empty($this->arResult["ERRORS"]))
        {

            $this->arResult['VALUES']['EMAIL'] = $this->request->get("email");
            $this->arResult['VALUES']['PASSWORD'] = $this->request->get("pass");

            if(strlen($this->arResult['VALUES']['PASSWORD']) < 8) {
                $this->arResult["ERRORS"][] = 'Минимум 8 символов в пароле';
                return;
            }

            if (!filter_var($this->arResult['VALUES']['EMAIL'], FILTER_VALIDATE_EMAIL)) {

                $this->arResult["ERRORS"][] = 'Не валидный EMAIL';
                return;
            }

            /* Присваиваем дефолтное значение пользовательскому полю с типом покупателя (частное лицо) */
            $obEnum = new \CUserFieldEnum;
            $rsEnum = $obEnum->GetList(array(), array("USER_FIELD_NAME" => 'UF_TYPE_OF_BUYER', 'XML_ID' => 'PRIVATE'));
            $enum = array();
            if($arEnum = $rsEnum->Fetch())
            {
                $personIdDefault = $arEnum['ID'];
            }

            $this->arResult['VALUES']['UF_TYPE_OF_BUYER'] = $personIdDefault;
            /* Присваиваем дефолтное значение пользовательскому полю с типом покупателя (частное лицо) */

            $filter = Array
            (
                "EMAIL" => $this->arResult['VALUES']['EMAIL'],
            );
            $rsUsers = CUser::GetList(array(), array(), $filter);
            if($arUser = $rsUsers->Fetch()){
                $this->arResult["ERRORS"][] = 'Пользователь с E-MAIL '.$this->arResult['VALUES']['EMAIL'].' уже существует.';
                return;
            }


            $this->arResult['VALUES']['LOGIN'] = $this->request->get("email");

            $this->arResult['VALUES']["GROUP_ID"] = array();
            $def_group = COption::GetOptionString("main", "new_user_registration_def_group", "");
            if($def_group != "")
                $this->arResult['VALUES']["GROUP_ID"] = explode(",", $def_group);


            $this->arResult['VALUES']["CHECKWORD"] = Random::getString(32);
            $this->arResult['VALUES']["~CHECKWORD_TIME"] = $DB->CurrentTimeFunction();
            $this->arResult['VALUES']["ACTIVE"] = 'Y';
            $this->arResult['VALUES']["LID"] = SITE_ID;
            $this->arResult['VALUES']["LANGUAGE_ID"] = LANGUAGE_ID;

            $this->arResult['VALUES']["USER_IP"] = $_SERVER["REMOTE_ADDR"];
            $this->arResult['VALUES']["USER_HOST"] = @gethostbyaddr($_SERVER["REMOTE_ADDR"]);

            if($this->arResult["VALUES"]["AUTO_TIME_ZONE"] <> "Y" && $this->arResult["VALUES"]["AUTO_TIME_ZONE"] <> "N")
                $this->arResult["VALUES"]["AUTO_TIME_ZONE"] = "";
            $bOk = true;

            $events = GetModuleEvents("main", "OnBeforeUserRegister", true);
            foreach($events as $arEvent)
            {
                if(ExecuteModuleEventEx($arEvent, array(&$this->arResult['VALUES'])) === false)
                {
                    if($err = $APPLICATION->GetException())
                        $this->arResult['ERRORS'][] = $err->GetString();

                    $bOk = false;
                    break;
                }
            }
            $ID = 0;
            $user = new CUser();
            if ($bOk)
            {
                $ID = $user->Add($this->arResult["VALUES"]);
            }

            if (intval($ID) > 0)
            {
                $register_done = true;

                // authorize user

                if (!$arAuthResult = $USER->Login($this->arResult["VALUES"]["LOGIN"], $this->arResult["VALUES"]["PASSWORD"]))
                    $this->arResult["ERRORS"][] = $arAuthResult;


                $this->arResult['VALUES']["USER_ID"] = $ID;

                $arEventFields = $this->arResult['VALUES'];
                $arEventFields['PASSWORD'] =  $this->arResult['VALUES']['PASSWORD'];
                $arEventFields['EMAIL'] = $this->arResult['VALUES']['EMAIL'];
                //unset($arEventFields["PASSWORD"]);
                //unset($arEventFields["CONFIRM_PASSWORD"]);

                $event = new CEvent;
                $event->SendImmediate("NEW_USER", SITE_ID, $arEventFields, 'N', 1);
                $event->SendImmediate("NEW_USER", SITE_ID, $arEventFields,'N',106);
                if($bConfirmReq)
                    $event->SendImmediate("NEW_USER_CONFIRM", SITE_ID, $arEventFields);
                LocalRedirect($this->arParams["SUCCESS_PAGE"]);
            }
            else
            {
                $this->arResult["ERRORS"][] = $user->LAST_ERROR;
            }

        }

    }

    public function executeComponent()
    {
        global $APPLICATION, $USER;
        /* if(!$USER->IsAuthorized()) {

          if(!empty( $_REQUEST["backurl"] )) {
             LocalRedirect( $_REQUEST["backurl"] );
          } else {
             LocalRedirect(SITE_DIR.'personal/');
          }

         } */

        if($this->request->get("save") == "Y") {

            $this->DoRegister();

        }
        !empty($_REQUEST['email']) ? $this->arResult["VALUES"]['EMAIL'] = $_REQUEST['email']: '';
        !empty($_REQUEST['pass']) ? $this->arResult["VALUES"]['PASS'] = $_REQUEST['pass']: '';
        $this->arResult["CAPTCHA_CODE"] = htmlspecialcharsbx($APPLICATION->CaptchaGetCode());


        $this->includeComponentTemplate();

    }

}



