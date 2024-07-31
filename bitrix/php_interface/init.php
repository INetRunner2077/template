<?
/*
You can place here your functions and event handlers

AddEventHandler("module", "EventName", "FunctionName");
function FunctionName(params)
{
	//code
}
*/
AddEventHandler('main', 'OnBeforeEventSend', Array("MyForm", "my_OnBeforeEventSend"));
class MyForm
{
    public static function my_OnBeforeEventSend(&$arFields, &$arTemplate, &$content)
    {
        $arTemplate['ID'] = 91;
        $arTemplate['EMAIL_TO'] = '#EMAIL#';
        return [
            $arFields,
            $arTemplate,
            $content,
        ];

    }
}
?>