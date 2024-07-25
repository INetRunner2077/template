<?php

namespace Altermax\Shop\Tools;

use Bitrix\Main\Web\Uri;

class Content
{
    private static $instance = null;

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if(self::$instance == null)
            self::$instance = new self();
        return self::$instance;
    }

    public static function getIBlockIdByCode($code, $typeCode = '')
    {
        if (!\Bitrix\Main\Loader::includeModule('iblock')) {
            return;
        }

        $arFilter = [
            'CODE' => $code,
        ];

        if($typeCode)
        {
            $arFilter['IBLOCK_TYPE_ID'] = $typeCode;
        }

        $IB = \Bitrix\Iblock\IblockTable::getList([
            'select' => ['ID'],
            'filter' => $arFilter,
            'limit'  => '1',
            'cache'  => ['ttl' => 3600],
        ]);
        $return = $IB->fetch();
        if (!$return) {
            throw new \Exception('IBlock with code"' . $code . '" not found');
        }

        return $return['ID'];
    }

}