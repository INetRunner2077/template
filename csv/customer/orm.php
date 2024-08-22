<?php
namespace Altermax;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields;
;
Loc::loadMessages(__FILE__);

class CustomerTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'customer_altermax';
    }

    /**
     * @return array
     */
    public static function getMap()
    {
        return [
            /** ID поля */
            new Fields\IntegerField(
                'ID', [
                        'primary'      => true,
                        'autocomplete' => true,
                    ]
            ),
            /** Название поставщика */
            new Fields\StringField(
                'CUSTOMER_NAME', [
                                  'required' => true,
                              ]
            ),
            /** ID Поставщика */
            new Fields\IntegerField(
                'ID_CUSTOMER', [
                                       'required' => true,
                                   ]
            ),
        ];
    }
}