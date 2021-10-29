<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
    die();
}

$arComponentDescription = array(
	"NAME" => GetMessage("MAIN_BTN_ITEMS_NAME"),
	"DESCRIPTION" => GetMessage("MAIN_BTN_ITEMS_DESC"),
	"ICON" => "/images/menu.gif",
	"PATH" => array(
		"ID" => "content",
		"CHILD" => array(
			"ID" => "excel_btn",
			"NAME" => GetMessage("MAIN_BTN_SERVICE")
		)
	),
);
?>