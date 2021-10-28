<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:form.result.new", 
	"pool_calculation", 
	array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "N",
		"CHAIN_ITEM_LINK" => "",
		"CHAIN_ITEM_TEXT" => "Расчет бассейна",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"SEF_MODE" => "N",
		"USE_EXTENDED_ERRORS" => "Y",
		"WEB_FORM_ID" => "1",
		"COMPONENT_TEMPLATE" => "pool_calculation",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"LIST_URL" => "/pool_calculation/result_list.php",
		//"EDIT_URL" => "/pool_calculation/result_edit.php",
		"SUCCESS_URL" => "/pool_calculation_test/",
		"VARIABLE_ALIASES" => array(
			"WEB_FORM_ID" => "WEB_FORM_ID",
			"RESULT_ID" => "RESULT_ID",
		)
	),
	false
);?>
<br>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>