<?
// подключим все необходимые файлы:
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php"); // первый общий пролог
CUtil::InitJSCore();

use Bitrix\Main\IO;
use Bitrix\Intranet;
use Bitrix\Main\ModuleManager;

global $APPLICATION, $USER;

if(!$USER->IsAdmin())
    LocalRedirect('/');


$instance = \Bitrix\Main\Application::getInstance();
$context = $instance->getContext();
$request = $context->getRequest();
$arPostList = $request->toArray();

// не забудем разделить подготовку данных и вывод
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
?>

<?
$obFile = new IO\File(__FILE__);
$ajaxFile = str_replace('.php', '_ajax.php', $obFile->getName());
$dir = str_replace(\Bitrix\Main\Application::getDocumentRoot(), '', $obFile->getDirectoryName());

// установим заголовок страницы
$APPLICATION->SetTitle('Основные настройки');
$APPLICATION->SetAdditionalCss("/bitrix/components/bitrix/desktop/templates/admin/style.css");


$siteTitle = trim(\Bitrix\Main\Config\Option::get("bitrix24", "site_title", ""));
if ($siteTitle == '')
{
    $siteTitle =
        ModuleManager::isModuleInstalled("bitrix24")
            ? GetMessage('BITRIX24_SITE_TITLE_DEFAULT')
            : \Bitrix\Main\Config\Option::get("main", "site_name", "")
    ;
}

$siteTitle = htmlspecialcharsbx($siteTitle);
?>
    <div class="bx-gadgets-container-new">

        <table class="gadgetholder" id="SAPHCM_WRAP_EMPLOYEE" width="100%" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td class="gd-page-column0" id="s0" width="60%" valign="top">

                    <div class="bx-gadgets">

                        <div class="bx-gadgets-top-wrap">
                            <div class="bx-gadgets-top-center">
                                <div class="bx-gadgets-top-title">Настройки</div>
                            </div>
                        </div>
                        <div class="bx-gadgets-content">

                            <form
                                method="post"
                                id='bx-mcart_create_new_type'
                                ENCTYPE="multipart/form-data"
                            >
                                <? echo bitrix_sessid_post(); ?>
                                <input type="hidden" name="BX_TYPE" value="MCPORTAL_SETTINGS"/>

                                <table class="bx-gadgets-info-site-table" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td width="40%">Наименование портала</td>
                                        <td width="60%">
                                            <input type="text" valign="middle" size="60" name="SITE_NAME" value="<?=$siteTitle?>" placeholder="pass">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="40%">Логотип</td>
                                        <td width="60%">
                                            <?
                                            if (class_exists('\Bitrix\Main\UI\FileInput', true))
                                            {
                                                $isMulty = false;
                                                $arExt = ['svg', 'png'];
                                                $arFiles = array();
                                                echo \Bitrix\Main\UI\FileInput::createInstance(array(
                                                    "name" => 'client_logo_retina',
                                                    "description" => false,
                                                    "upload" => true,
                                                    "allowUpload" => "F",
                                                    "medialib" => false,
                                                    "fileDialog" => false,
                                                    "cloud" => false,
                                                    "delete" => true,
                                                    "edit" => false,
                                                    "allowUploadExt" => $arExt ? implode(', ', $arExt) : false,
                                                    "maxCount" => $isMulty ? '0' : '1',
                                                    "maxSize" => 1024*1024*1 //1МБ
                                                ))->show($siteLogo['logo'], false);
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center">
                                            <input type="button" onclick="return __MCART_SEND('bx-mcart_create_new_type')" name="execute" value="Сохранить" class="adm-btn-save send_req_user_one">
                                            <div class="status-send"></div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>

                        </div>
                    </div>


                </td>
                <td width="20">
                    <div style="WIDTH: 20px"></div>
                    <br>
                </td>
                <td class="gd-page-column1" id="s2" width="50%" valign="top">

                </td>
            </tr>
            </tbody>
        </table>


    </div>

    <script>
        function __MCART_SEND($blockID)
        {
            var ob = BX($blockID);

            BX.showWait($blockID)

            var formData = BX.ajax.prepareData(BX.ajax.prepareForm(ob).data);
            BX.ajax.post(
                '/ajax/mc_portal_settings_ajax.php',
                formData,
                function(result){

                    answer = JSON.parse(result);

                    var errorBlock = BX.findChildren(ob, {
                        tag: 'div',
                        className: 'status-send'
                    }, true);

                    BX.adjust(errorBlock[0], {
                        html: answer.txt,
                        // text: answer.txt,
                        props: {className: 'status-send ' + answer.status}
                    });

                    BX.closeWait();
                }
            );
        }
    </script>

    <style>
        .status-send.error {color: red;}
        .status-send.success {color: green;}
    </style>

<?
// завершение страницы
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
?>