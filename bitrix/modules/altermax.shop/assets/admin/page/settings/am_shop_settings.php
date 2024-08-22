<?
// подключим все необходимые файлы:
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php"); // первый общий пролог
CUtil::InitJSCore();
CJSCore::Init(array("jquery"));
use Bitrix\Main\IO;
use Bitrix\Intranet;
use Bitrix\Main\ModuleManager;
use \Bitrix\Main\Config\Option;

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
$APPLICATION->SetTitle('Импотр из файла');
$APPLICATION->SetAdditionalCss("/bitrix/components/bitrix/desktop/templates/admin/style.css");


$siteTitle = trim(Option::get("bitrix24", "site_title", ""));
if ($siteTitle == '')
{
    $siteTitle =
        ModuleManager::isModuleInstalled("bitrix24")
            ? GetMessage('BITRIX24_SITE_TITLE_DEFAULT')
            : Option::get("main", "site_name", "")
    ;
}

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

                            <form method="post" id='bx-am_add_exel' ENCTYPE="multipart/form-data">
                                <? echo bitrix_sessid_post(); ?>
                                <input type="submit" value="Выполнить импорт">
                                <div class="status" id="status">

                                </div>
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


        $( "#bx-am_add_exel" ).submit(function( event ) {
            event.preventDefault();
            var form = $( "#bx-am_add_exel");
            $.ajax({
                type: "POST",
                url: "/exel/index.php",
                data: form.serialize(),
                dataType: "json",
                success: function (data) {
                    if(data.STATUS == "OK") {

                      $('#status').text('Запрос ушел обработку');
                        $('#status').addClass('green');
                    } else {
                         $('#status').text('Ошибка');
                         $('#status').addClass('red');
                    }
                }
            })

        });

    </script>

    <style>
        .red {
            color:red;
        }
        .green {
            color:green;
        }
        #bx-am_add_exel {
        display:flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
        }
    </style>

<?
// завершение страницы
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
?>