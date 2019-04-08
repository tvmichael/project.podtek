<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?><?$APPLICATION->IncludeComponent(
	"arhicode:form.result.view", 
	"test_1", 
	array(
		"CHAIN_ITEM_LINK" => "",
		"CHAIN_ITEM_TEXT" => "",
		"COMPONENT_TEMPLATE" => "",
		"EDIT_URL" => "edit/#RESULT_ID#/",
		"RESULT_ID" => $_REQUEST[RESULT_ID],
		"SEF_FOLDER" => "/test_1/",
		"SEF_MODE" => "N",
		"SHOW_ADDITIONAL" => "Y",
		"SHOW_ANSWER_VALUE" => "Y",
		"SHOW_STATUS" => "Y",
		"SET_TITLE" => "N"
	),
	false
);?>
<br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>