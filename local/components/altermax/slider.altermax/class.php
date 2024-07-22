<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use \Bitrix\Main\Application;
use Bitrix\Main\Loader;
class Registry extends CBitrixComponent
{
    protected $request;


    public function executeComponent()
    {

        Loader::includeModule('iblock');

        $rs = CIBlockElement::GetList (
            Array(),
            Array(
                "IBLOCK_ID" => $this->arParams['IBLOCK_ID'],
                "ACTIVE"=>"Y",
            ),
            false,
            false,
            Array ('DETAIL_PICTURE', 'ID', 'PREVIEW_PICTURE'),
        );

        while ($item = $rs->GetNextElement()) {

            $arItem = $item->GetFields();
            if(!empty($arItem['DETAIL_PICTURE'])) {
                $this->arResult['ITEMS'][$arItem['ID']]['PICTURE'] = $arItem['DETAIL_PICTURE'];
                $this->arResult['ITEMS'][$arItem['ID']]['NAME'] = $arItem['NAME'];
            } elseif (!empty($arItem['PREVIEW_PICTURE'])) {
                $this->arResult['ITEMS'][$arItem['ID']]['PICTURE'] = $arItem['PREVIEW_PICTURE'];
                $this->arResult['ITEMS'][$arItem['ID']]['NAME'] = $arItem['NAME'];
            }
        }

        foreach ($this->arResult['ITEMS'] as $id => $picture) {
            $src = CFile::GetPath($picture['PICTURE']);
            $this->arResult['ITEMS'][$id]['PICTURE_SRC'] = $src;
        }

        $this->includeComponentTemplate();

    }

}



