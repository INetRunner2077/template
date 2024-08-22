<?php
namespace Altermax;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields;

//ini_set("memory_limit", "1024M");
//ini_set("max_execution_time", "0");


Loc::loadMessages(__FILE__);

class ProductTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'product_altermax';
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
            /** Наименование позиции */
            new Fields\StringField(
                'NAME', [
                          'required' => true,
                      ]
            ),
            /** Наименование для поиска */
            new Fields\StringField(
                'NAME_FIND', [
                               'required' => true,
                           ]
            ),
            /** ID в партнерке */
            new Fields\IntegerField(
                'PARTNER_ID', [
                                  'required' => true,
                              ]
            ),
            /** ID в description */
            new Fields\IntegerField(
                'DESCRIPTION_ID', [
                                'required' => false,
                            ]
            ),
            /** ID родительской секции */
            new Fields\IntegerField(
                'SECTION_ID', [
                                    'required' => true,
                                ]
            ),
            /** ID секции в Битриксе */
            new Fields\IntegerField(
                'BITRIX_SECTION_ID', [
                                'required' => true,
                            ]
            ),
            /** Название Производителя */
            new Fields\StringField(
                'PRODUCER_NAME', [
                                   'required' => false,
                               ]
            ),
            /** Код валюты */
            new Fields\IntegerField(
                'CURRENCY_ID', [
                                'required' => false,
                            ]
            ),
            /** Название Поставщика */
            new Fields\StringField(
                'CUSTOMER_NAME', [
                                   'required' => false,
                               ]
            ),
            /** Год выпуска */
            new Fields\StringField(
                'YEAR_CREATE', [
                               'required' => false,
                           ]
            ),
            /** Единица измерения */
            new Fields\StringField(
                'MEASURE', [
                                 'required' => true,
                             ]
            ),
            /** Остаток */
            new Fields\DecimalField(
                'QUANTITY', [
                             'required' => true,
                         ]
            ),
            /** Цена */
            new Fields\DecimalField(
                'PRICE', [
                              'required' => true,
                          ]
            ),
            /** Артикул */
            new Fields\StringField(
                'VENDOR', [
                               'required' => true,
                           ]
            ),
            /** Время обновления */
            new Fields\StringField(
                'TIME_UPDATE', [
                            'required' => true,
                        ]
            ),

        ];
    }
}