<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
* Bitrix vars
*
* @var array $arResult
* @var array $arParams
* @var CMain $APPLICATION
* @var CUser $USER
* @var CBitrixMenuComponent $this
*/

global $APPLICATION;

$arResult['BTN_TEXT'] = $arParams["BTN_TEXT"] ?? 'Button';
$arResult['BTN_DISPLAY_FOR_USER'] = false;

$arResult['IBLOCK_ID'] = $arParams['IBLOCK_ID'] ?? '';

$arResult['USER_GROUP_LIST'] = [];
if(isset($arParams['USER_GROUP_LIST']) && is_array($arParams['USER_GROUP_LIST']))
{
    $arResult['USER_GROUP_LIST'] = $arParams['USER_GROUP_LIST'];

    $arUserGroups = CUser::GetUserGroup($USER->GetID());
    foreach ($arUserGroups as $item)
    {
        if(in_array($item, $arParams['USER_GROUP_LIST']))
        {
            $arResult['BTN_DISPLAY_FOR_USER'] = true;
            break;
        }
    }
}

if(isset($arParams["BTN_DISPLAY_FOR_GROUP"]) && $arParams["BTN_DISPLAY_FOR_GROUP"] == 'Y')
{
    $arResult['BTN_DISPLAY_FOR_USER'] = true;
}

$arResult["DISCOUNT_LIST"] = [];
if(isset($arParams["DISCOUNT_LIST"]) && is_array($arParams["DISCOUNT_LIST"]))
    $arResult["DISCOUNT_LIST"] = $arParams["DISCOUNT_LIST"];

$arResult["BTN_ID"] = 'excel-load-' . rand(1, 1000);

$arResult['PROPERTY_LIST'] = $arParams["PROPERTY_LIST"] ?? [];
$arResult['IMAGE_LIST'] = $arParams["IMAGE_LIST"] ?? [];

$arResult['JS_PARAMS'] = [
    'BTN_ID' => $arResult["BTN_ID"],
    'USER_ID' => $USER->GetID(),
    'USER_GROUP_LIST' => $arResult['USER_GROUP_LIST'],
    'DISCOUNT_LIST' => $arResult["DISCOUNT_LIST"],
    'IMAGE_LIST' => $arResult["IMAGE_LIST"],
    'PROPERTY_LIST' => $arResult['PROPERTY_LIST'],
    'IBLOCK_ID' => $arResult['IBLOCK_ID'],
];

$this->IncludeComponentTemplate();