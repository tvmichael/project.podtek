<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (isset($arParams["TEMPLATE_THEME"]) && !empty($arParams["TEMPLATE_THEME"]))
{
	$arAvailableThemes = array();
	$dir = trim(preg_replace("'[\\\\/]+'", "/", dirname(__FILE__)."/themes/"));
	if (is_dir($dir) && $directory = opendir($dir))
	{
		while (($file = readdir($directory)) !== false)
		{
			if ($file != "." && $file != ".." && is_dir($dir.$file))
				$arAvailableThemes[] = $file;
		}
		closedir($directory);
	}

	if ($arParams["TEMPLATE_THEME"] == "site")
	{
		$solution = COption::GetOptionString("main", "wizard_solution", "", SITE_ID);
		if ($solution == "eshop")
		{
			$templateId = COption::GetOptionString("main", "wizard_template_id", "eshop_bootstrap", SITE_ID);
			$templateId = (preg_match("/^eshop_adapt/", $templateId)) ? "eshop_adapt" : $templateId;
			$theme = COption::GetOptionString("main", "wizard_".$templateId."_theme_id", "blue", SITE_ID);
			$arParams["TEMPLATE_THEME"] = (in_array($theme, $arAvailableThemes)) ? $theme : "blue";
		}
	}
	else
	{
		$arParams["TEMPLATE_THEME"] = (in_array($arParams["TEMPLATE_THEME"], $arAvailableThemes)) ? $arParams["TEMPLATE_THEME"] : "blue";
	}
}
else
{
	$arParams["TEMPLATE_THEME"] = "blue";
}

$arParams["FILTER_VIEW_MODE"] = (isset($arParams["FILTER_VIEW_MODE"]) && toUpper($arParams["FILTER_VIEW_MODE"]) == "HORIZONTAL") ? "HORIZONTAL" : "VERTICAL";
$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";

if(isset($arResult['ITEMS']) && is_array($arResult['ITEMS']))
{
    foreach ($arResult['ITEMS'] as $k => $items)
    {
        if($items['CODE'] == 'NALICHIE')
        {
            $arResult["JS_FILTER_PARAMS"]['VALUES_PARAMS'] = [];
            $arResult["JS_FILTER_PARAMS"]['VALUES_PARAMS']['VALUES'] = [];

            if(is_array($items['VALUES']))
            {
                foreach ($items['VALUES'] as $i => $item)
                {
                    if($item['VALUE'] == 'В наличии')
                    {
                        $arResult["JS_FILTER_PARAMS"]['VALUES_PARAMS']['CONTROL_ID_Y'] = $item['CONTROL_ID'];
                    }

                    if($item['VALUE'] == 'Под заказ, 1-2 дня' || $item['VALUE'] == 'Под заказ, 3-5 дней')
                    {
                        $arResult["JS_FILTER_PARAMS"]['VALUES_PARAMS']['VALUES'][$i] = $item;

                        unset($arResult['ITEMS'][$k]['VALUES'][$i]);
                    }

                    if($item['VALUE'] == 'Под заказ, до 45 дней') {
                        $arResult["JS_FILTER_PARAMS"]['VALUES_PARAMS']['CONTROL_ID_N'] = $item['CONTROL_ID'];
                        $arResult['ITEMS'][$k]['VALUES'][$i]['VALUE'] = 'Под заказ';
                        $arResult['ITEMS'][$k]['VALUES'][$i]['UPPER'] = strtoupper('Под заказ');
                    }
                }
            }
            break;
        }
    }
}