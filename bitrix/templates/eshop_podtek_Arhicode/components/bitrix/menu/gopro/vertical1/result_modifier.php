<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

if (!CModule::IncludeModule('iblock'))
	return;

if ($arParams['RSGOPRO_MAX_ITEM'] == '')
	$arParams['RSGOPRO_MAX_ITEM'] = 9;

if (is_array($arResult) && count($arResult) > 0)
{
    //if($USER->IsAdmin() && $USER->GetID() == 8 && $_SESSION['SESS_IP'] == '188.163.120.85')
        $arParams["IBLOCK_ID"] = 7;

	////////////////////////////////// get section detail pictures //////////////////////////////////
	if (intval($arParams["IBLOCK_ID"]) > 0)
	{
		$arTmpSections = array();
		$arFilter = array(
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"GLOBAL_ACTIVE" => "Y",
			"IBLOCK_ACTIVE" => "Y",
			"<="."DEPTH_LEVEL" => 1,
		);
		$arOrder = array(
			"left_margin" => "asc",
		);
		$arSelect = array(
			"ID",
			"DEPTH_LEVEL",
			"NAME",
			"SECTION_PAGE_URL",
			"DETAIL_PICTURE",
            "PICTURE",
            "UF_*",
		);

		$rsSections = CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect);
		if ($arParams["IS_SEF"] !== "Y")
			$rsSections->SetUrlTemplates("", $arParams["SECTION_URL"]);
		else
			$rsSections->SetUrlTemplates("", $arParams["SEF_BASE_URL"].$arParams["SECTION_PAGE_URL"]);
		while ($arSection = $rsSections->GetNext()) {
			$arTmpSections[$arSection["~SECTION_PAGE_URL"]] = $arSection;
		}
	}

	////////////////////////////////// base //////////////////////////////////
	$last_key_lvl1 = 0;
	foreach ($arResult as $key => $arItem)
	{
        $arResult[$key]['MENU_ADDITIONAL_PICTURE'] = [];

		$arResult[$key]['IS_LAST_LVL1'] = 'N';
		if ($arItem['DEPTH_LEVEL'] == 1){
			$last_key_lvl1 = $key;
		}

		if (!empty($arTmpSections[$arItem['LINK']]))
		{
		    if(!empty($arTmpSections[$arItem['LINK']]['DETAIL_PICTURE']))
			    $arResult[$key]['DETAIL_PICTURE'] = CFile::GetPath($arTmpSections[$arItem['LINK']]['DETAIL_PICTURE']);
		    elseif(!empty($arTmpSections[$arItem['LINK']]['PICTURE']))
                $arResult[$key]['DETAIL_PICTURE'] = CFile::GetPath($arTmpSections[$arItem['LINK']]['PICTURE']);

            $arResult[$key]['MENU_ADDITIONAL_PICTURE'] = [
                'UF_ADDITIONAL_PAGE' => $arTmpSections[$arItem['LINK']]['UF_ADDITIONAL_PAGE'] ?? '',
                'UF_DESCRIPTION_ADDITIONAL_PAGE' => $arTmpSections[$arItem['LINK']]['UF_DESCRIPTION_ADDITIONAL_PAGE'] ?? '',
                'UF_IMG_ADDITIONAL_PAGE' => null,
            ];
            if(!empty($arTmpSections[$arItem['LINK']]['UF_IMG_ADDITIONAL_PAGE']))
            {
                $arResult[$key]['MENU_ADDITIONAL_PICTURE']['UF_IMG_ADDITIONAL_PAGE'] = CFile::GetPath($arTmpSections[$arItem['LINK']]['UF_IMG_ADDITIONAL_PAGE']);
            }
		}
	}
	$arResult[$last_key_lvl1]['IS_LAST_LVL1'] = 'Y';

	////////////////////////////////// element in menu //////////////////////////////////
	if (IntVal($arParams['IBLOCK_ID']) > 0 && $arParams['RSGOPRO_PROPCODE_ELEMENT_IN_MENU'] != '')
	{
		foreach ($arResult as $key1 => $arItem1){
			if ($arItem1['DEPTH_LEVEL'] == 1 && $arItem1['LINK'] != '') {	
				$arResult[$key1]['PARAMS']['ELEMENT'] = 'N';
				$arOrder = array('SORT' => 'ASC', 'ID' => 'ASC');
				$arFilter = array(
					'IBLOCK_ID' => IntVal($arParams['IBLOCK_ID'][0]),
					'ACTIVE' => 'Y', 
					'INCLUDE_SUBSECTIONS' => 'Y',
					'PROPERTY_'.$arParams['RSGOPRO_PROPCODE_ELEMENT_IN_MENU'] => $arItem1['LINK'],
				);
				$arNavStartParams = array('nTopCount' => '1');
				$arSelect = array('ID','IBLOCK_ID','ACTIVE','SECTION_ID','PROPERTY_'.$arParams['RSGOPRO_PROPCODE_ELEMENT_IN_MENU']);
				$res = CIBlockElement::GetList($arOrder, $arFilter, false, $arNavStartParams, $arSelect);
				if ($arObj = $res->GetNextElement()) {
					$arFields = $arObj->GetFields();
					$arResult[$key1]['PARAMS']['ELEMENT'] = 'Y';
					$arResult[$key1]['PARAMS']['ELEMENT_ID'] = $arFields['ID'];
				}
			}
		}
	}
}
