<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use \Bitrix\Main\Application;
use Bitrix\Main\Security\Random;
class FormFront extends CBitrixComponent
{
    protected $request;

    public function DoRegister() {



    }

    public function executeComponent()
    {
       global $USER;
        if($USER->IsAuthorized()) {
            $this->arResult['USER']['NAME'] = $USER->GetFullName();
            $this->arResult['USER']['EMAIL'] = $USER->GetEmail();
        }
        $this->includeComponentTemplate();

    }

}



