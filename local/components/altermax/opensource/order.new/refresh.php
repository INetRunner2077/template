<?php
define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC','Y');
define('DisableEventsCheck', true);
define('BX_SECURITY_SHOW_MESSAGE', true);
define('NOT_CHECK_PERMISSIONS', true);

$siteId = isset($_REQUEST['SITE_ID']) && is_string($_REQUEST['SITE_ID']) ? $_REQUEST['SITE_ID'] : '';
$siteId = mb_substr(preg_replace('/[^a-z0-9_]/i', '', $siteId), 0, 2);
if (!empty($siteId) && is_string($siteId))
{
    define('SITE_ID', $siteId);
}

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$request->addFilter(new \Bitrix\Main\Web\PostDecodeFilter);

if (!Bitrix\Main\Loader::includeModule('sale'))

    return;

Bitrix\Main\Localization\Loc::loadMessages(__DIR__.'/class.php');

$signer = new \Bitrix\Main\Security\Sign\Signer;
try
{
    $signedParamsString = $request->get('signedParamsString') ?: '';
    $params = $signer->unsign($signedParamsString, 'sale.order.ajax');
    $params = unserialize(base64_decode($params), ['allowed_classes' => false]);
    $allParam = $request->get('param');
    $params[$allParam['nameParam']] = $allParam['param'];

    /* Чистим параметры */
    unset($params['location']);
    unset($params['LOCATION']);
    unset($params['~LOCATION']);
    unset($params['~location']);
    unset($params['~SAVE']);
    unset($params['SAVE']);
    unset($params['SERVICE']);
    unset($params['~SERVICE']);
    unset($params['']);
    unset($params['~']);
    /* Чистим параметры */

    $params['SERVICE'] = [];
    foreach ($allParam as $key => $paramValue) {
        if (($paramValue != 'undefined') and ($paramValue != 'null') and ($paramValue != 0)) {
            $params[$key] = $paramValue;
        }
    }

    $loc = $request->get('location');
    if(!empty($loc)) {
        $params['location'] = $loc;
    }
}
catch (\Bitrix\Main\Security\Sign\BadSignatureException $e)
{
    die();
}


global $APPLICATION;
$_REQUEST['step'] = '2';
$_REQUEST['refresh'] = 'Y';
$APPLICATION->IncludeComponent(
    'opensource:order.new',
    'example_person_type',
    $params
);
