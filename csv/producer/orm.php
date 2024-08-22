<?php
namespace Altermax;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields;

//ini_set("memory_limit", "1024M");
//ini_set("max_execution_time", "0");


Loc::loadMessages(__FILE__);

class ProducerTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'producer_altermax';
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
            /** Название Производителя */
            new Fields\StringField(
                'PRODUCER_NAME', [
                                  'required' => true,
                              ]
            ),
            /** Сайт Производителя */
            new Fields\StringField(
                'PRODUCER_SITE', [
                                      'required' => false,
                                  ]
            ),
            /** ID Производителя */
            new Fields\IntegerField(
                'ID_PRODUCER', [
                                       'required' => true,
                                   ]
            ),
        ];
    }
}