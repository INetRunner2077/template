<?php

use Bitrix\Iblock\TypeLanguageTable;
use Bitrix\Iblock\TypeTable;

class IBlockTypesModuleManager {
    private array $iBlockTypeList;

    public function __construct()
    {
        $this->iBlockTypeList = [
            [
                'type' => [
                    'ID' => 'altermax_catalog',
                    'SECTIONS' => 'Y',
                    'IN_RSS'=>'N',
                    'SORT' => 100,
                ],
                'lang' => [
                    'LANGUAGE_ID' => 'ru',
                    'NAME' => 'Альтермакс: Каталог',
                    'SECTION_NAME' => 'Каталог',
                    'ELEMENT_NAME' => 'файл'
                ]
            ],
            [
                'type' => [
                    'ID' => 'altermax_advertising',
                    'SECTIONS' => 'Y',
                    'IN_RSS'=>'N',
                    'SORT' => 100,
                ],
                'lang' => [
                    'LANGUAGE_ID' => 'ru',
                    'NAME' => 'Альтермакс: Реклама',
                    'SECTION_NAME' => 'Реклама',
                    'ELEMENT_NAME' => 'фото'
                ]
            ],
        ];
    }

    public function doInstall()
    {
        foreach ($this->iBlockTypeList as $iblockType) {
            if (!$typeTableObject = TypeTable::getById($iblockType['type']['ID'])->fetchObject()) {
                $typeTableObject = TypeTable::add($iblockType['type']);
            }

            $iblockType['lang']['IBLOCK_TYPE_ID'] = $typeTableObject->getId();
            TypeLanguageTable::add($iblockType['lang']);
        }
    }

    public function doUninstall()
    {
        foreach ($this->iBlockTypeList as $iBlockType) {
            if ($typeTableObject = TypeTable::getById($iBlockType['type']['ID'])->fetchObject()) {
                $typeTableObject->delete();
            }
        }
    }
}