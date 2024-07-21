<?php

namespace Altermax\Shop\Admin;

class Menu
{
    public static function doOnBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu)
    {
        global $USER;

        if($USER->IsAdmin())
        {
            $aGlobalMenu['global_menu_mcart_portal'] = [
                'menu_id' => 'ckr',
                'text' => 'AltermaxShop',
                'title' => 'AltermaxShop',
                'sort' => '70',
                'items_id' => 'global_menu_mcart_portal',
                'help_section' => 'settings',
                'items' => [

                ]
                //'url' => '',
            ];

            $aModuleMenu[] = [
                'parent_menu' => 'global_menu_mcart_portal',
                'sort' => '100',
                'text' => 'Настройки',
                'title' => 'Настройки',
                'more_url' => [
                ],
                'icon' => 'sender_menu_icon',
                'page_icon' => 'sender_menu_icon',
                'items_id' => 'mc_settings',
                'items' => [
                    [
                        'text' => 'Основные',
                        'title' => 'Основные',
                        'url' => 'am_shop_settings.php',
                    ],
                ]
            ];

        }
    }
}
