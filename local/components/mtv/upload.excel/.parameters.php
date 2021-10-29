<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
	die();
}
/** @var array $arCurrentValues */


// GROUP LIST
$filter = Array();
$rsGroups = CGroup::GetList(($by = "c_sort"), ($order = "desc"), $filter);
$arrGroupList = [];

while($item = $rsGroups->Fetch())
	$arrGroupList[$item['ID']] = '['.$item['ID'].'] ' . $item['NAME'];

// DISCOUNT LIST
\Bitrix\Main\Loader::includeModule('sale');
$discountIterator = \Bitrix\Sale\Internals\DiscountTable::getList(array(
	'select' => array('ID', 'NAME'),
	'filter' => array('ACTIVE' => 'Y'),
	'order' => array('SORT' => 'ASC', 'ID' => 'ASC')
));

$arrDiscountList = [0 => GetMessage("COMP_PARAM_BTN_DISCOUNT_EMPTY")];
while ($item = $discountIterator->Fetch())
	$arrDiscountList[$item['ID']] = '['.$item['ID'].'] ' . $item['NAME'];

// CATALOG
$arIBlocks = array();
$db_iblock = CIBlock::GetList(
	array("SORT"=>"ASC"),
	array("TYPE" => ($arCurrentValues["IBLOCK_TYPE"]!="-"?$arCurrentValues["IBLOCK_TYPE"]:""))
);
while($arRes = $db_iblock->Fetch())
	$arIBlocks[$arRes["ID"]] = "[".$arRes["ID"]."] ".$arRes["NAME"];

// PROPERTY
$arrPropertyList = [0 => GetMessage("COMP_PARAM_BTN_PROPERTY_EMPTY")];
$properties = CIBlockProperty::GetList(
	Array("NAME" => "ASC"),
	Array("ACTIVE" => "Y", "IBLOCK_ID" => $arCurrentValues['IBLOCK_ID'])
);
while ($prop_fields = $properties->GetNext())
	if($prop_fields["CODE"] != 'CML2_ARTICLE') // CML2_ARTICLE - добавлено
		$arrPropertyList[$prop_fields["ID"]] = '[' . $prop_fields["ID"] . "] " . $prop_fields["NAME"];

// PICTURE
$arrImageList = [
	0 => GetMessage("COMP_PARAM_BTN_PROPERTY_EMPTY"),
	'DETAIL_PICTURE' => 'Детальная картинка',
	'PREVIEW_PICTURE' => 'Картинка для анонса'
];

$arComponentParameters = array(
	"GROUPS" => array(
		"BASE" => array(
			"NAME" => GetMessage("COMP_GROUP_BASE_SETTINGS"),
			"SORT" => 600
		),
	),
	"PARAMETERS" => array(

		"BTN_TEXT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("COMP_PARAM_BTN_NAME"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => 'Загрузить файл',
			"COLS" => 50,
		),

		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("COMP_PARAM_BTN_CATALOG"),
			"TYPE" => "LIST",
			"DEFAULT" => '',
			"ADDITIONAL_VALUES"	=> "N",
			"MULTIPLE" => "N",
			"VALUES" => $arIBlocks,
			'REFRESH' => 'Y',
		),

		"USER_GROUP_LIST" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("COMP_PARAM_BTN_USER_GROUP"),
			"TYPE" => "LIST",
			"DEFAULT" => '',
			"SIZE" => 5,
			"ADDITIONAL_VALUES"	=> "N",
			"MULTIPLE" => "Y",
			"VALUES" => $arrGroupList,
		),

		"DISCOUNT_LIST" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("COMP_PARAM_BTN_DISCOUNT_LIST"),
			"TYPE" => "LIST",
			"DEFAULT" => '',
			"MULTIPLE" => "Y",
			"SIZE" => 5,
			"ADDITIONAL_VALUES"	=> "N",
			"VALUES" => $arrDiscountList,
		),

		"PROPERTY_LIST" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("COMP_PARAM_BTN_PROPERTY_LIST"),
			"TYPE" => "LIST",
			"DEFAULT" => '',
			"MULTIPLE" => "Y",
			"SIZE" => 5,
			"ADDITIONAL_VALUES"	=> "N",
			"VALUES" => $arrPropertyList,
		),

		"IMAGE_LIST" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("COMP_PARAM_BTN_IMAGE_LIST"),
			"TYPE" => "LIST",
			"DEFAULT" => '',
			"MULTIPLE" => "Y",
			"SIZE" => 2,
			"ADDITIONAL_VALUES"	=> "N",
			"VALUES" => $arrImageList,
		),

		"BTN_DISPLAY_FOR_GROUP" => Array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("COMP_PARAM_BTN_DISPLAY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'N',
		),
	)
);
?>
