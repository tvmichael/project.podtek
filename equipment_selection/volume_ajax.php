<?php
/**
 * User: Michael
 * Date: 26.03.2019
 */
define('STOP_STATISTICS', true);
define('NOT_CHECK_PERMISSIONS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');


CModule::IncludeModule("catalog");

$request = $context->getRequest();
$valueAction = $request->getPost("action");

if($valueAction=='equipment') {

    $volume = floatval($request->getPost("volume"));
    if ($volume > 0) {
        $rem_volume = 0;
        $arSelect = Array();
        $arFilter = Array("IBLOCK_ID"=>"11", "SECTION_ID"=>"703", "ACTIVE"=>"Y");
        $res_product = CIBlockElement::GetList( Array(),$arFilter,false,Array(),$arSelect);
        while($ob = $res_product->GetNextElement())
        {
            $arProps = $ob->GetProperties();
            $arFields = $ob->GetFields();
            $max_volume = floatval($arProps['max_volume']['VALUE']);
            if(isset($arProps['max_volume']) && isset($arProps['composition_catalog']) && $max_volume > 0)
            {
                if($volume <= $max_volume && ($max_volume <= $rem_volume || $rem_volume == 0))
                {
                    $rem_volume = $max_volume;
                    $acText = '';
                    if(is_array($arProps['composition_catalog']['VALUE']) && count($arProps['composition_catalog']['VALUE']) > 0)
                    {
                        $acText = "<table class='ac-table-poll'><tbody>";
                        $acText .="<tr><td colspan='2'>".$arFields['NAME']."</td></tr><tr>";
                        //print_r($arProps['composition_catalog']['VALUE']);
                        foreach ($arProps['composition_catalog']['VALUE'] as $id)
                        {
                            $arPrice = CCatalogProduct::GetOptimalPrice($id, 1, $USER->GetUserGroupArray(), 'N');
                            //print_r($arPrice);
                            $res = CIBlockElement::GetByID($id);
                            if($ar_res = $res->GetNext())
                                //print_r($ar_res);
                                $acText .="<td>".$ar_res['NAME']."</td><td>".$arPrice['PRICE']['PRICE'].' '.$arPrice['PRICE']['CURRENCY']."</td></tr>";
                        }
                        $acText .="</tr></tbody></table>";
                    }
                }
            }
        }
        if ($acText!='') echo $acText;
            else echo "<div class='alert alert-danger' role='alert'>Оборудованиe не найдено. $max_volume</div>";
    }
}
?>