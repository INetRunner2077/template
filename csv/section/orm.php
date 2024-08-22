<?php
namespace Altermax;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields;


class SectionTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'section_altermax';
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
            /** Название секции в Битриксе */
            new Fields\StringField(
                'NAME_SECTION', [
                                  'required' => true,
                              ]
            ),
            /** ID текущей секции */
            new Fields\IntegerField(
                'ID_SECTION', [
                                'required' => true,
                            ]
            ),
            /** ID родительской секции */
            new Fields\IntegerField(
                'ID_PARENT_SECTION', [
                                       'required' => true,
                                   ]
            ),
            /** ID родительской секции в Битриксе */
            new Fields\IntegerField(
                'ID_PARENT_BITRIX_SECTION', [
                                       'required' => true,
                                   ]
            ),
            /** ID секции в Битриксе */
            new Fields\IntegerField(
                'ID_BITRIX_SECTION', [
                                       'required' => true,
                                   ]
            ),
        ];
    }
}