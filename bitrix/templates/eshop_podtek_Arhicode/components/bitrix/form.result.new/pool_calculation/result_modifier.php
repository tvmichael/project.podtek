<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $arResult
 * @var CBitrixComponentTemplate $arParams
 */

$arProductPrice = [];

foreach ($arResult['arAnswers'] as $resItems)
{
    foreach ($resItems as $item) // перебираємо всі 'інпути' з відповідями
    {
        $arProductJsonList = [];
        $arProductPriceList = [];

        if(is_numeric($item['VALUE'])) // якщо значення 'інпута' це число
        {
            $kitId = intval($item['VALUE']);
            if($kitId > 0 )
            {
                // якщо $item['VALUE'] комплект товару то отримаємо список товарів
                $arKit = CCatalogProductSet::getAllSetsByProduct($kitId, CCatalogProductSet::TYPE_SET);
                foreach ($arKit as $key => $i)
                {
                    foreach ($i['ITEMS'] as $price)
                    {
                        $arPrice = CCatalogProduct::GetOptimalPrice($price['ITEM_ID'], 1, $USER->GetUserGroupArray(), 'N');

                        $dbRes= CCatalogProduct::GetList(array(),array("ID" => $price['ITEM_ID'] ),false,array());
                        if($ar = $dbRes->Fetch())
                            $arPrice['RESULT_PRICE']['ELEMENT_NAME'] = $ar['ELEMENT_NAME'];

                        $arPrice['RESULT_PRICE']['QUANTITY'] = $price['QUANTITY'];
                        $arPrice['RESULT_PRICE']['BLOCK_ID'] = CIBlockElement::GetIBlockByID($price['ITEM_ID']);
                        $arProductPriceList[$price['ITEM_ID']] = $arPrice['RESULT_PRICE'];
                    }
                }

                if(count($arProductPriceList) > 0)
                    $arProductPrice[$item['VALUE']] = $arProductPriceList;
            }
        }
        elseif ( is_string($item['VALUE']) )
        {
            $jsonOb = str_replace("'", '"', $item['VALUE']);

            if(is_object(json_decode($jsonOb)) || is_array(json_decode($jsonOb)))
            {
                $jsonOb = json_decode($jsonOb);
                foreach ($jsonOb as $js) // список ІД товаров з json строки
                {
                    $kitId = intval($js);
                    $arKit = CCatalogProductSet::getAllSetsByProduct($kitId, CCatalogProductSet::TYPE_SET);

                    foreach ($arKit as $key => $i)
                    {
                        foreach ($i['ITEMS'] as $price)
                        {
                            $arPrice = CCatalogProduct::GetOptimalPrice($price['ITEM_ID'], 1, $USER->GetUserGroupArray(), 'N');

                            $dbRes= CCatalogProduct::GetList(array(),array("ID" => $price['ITEM_ID'] ),false,array());
                            if($ar = $dbRes->Fetch())
                                $arPrice['RESULT_PRICE']['ELEMENT_NAME'] = $ar['ELEMENT_NAME'];

                            $arPrice['RESULT_PRICE']['QUANTITY'] = $price['QUANTITY'];
                            $arPrice['RESULT_PRICE']['BLOCK_ID'] = CIBlockElement::GetIBlockByID($price['ITEM_ID']);
                            //$arProductPriceList[$price['ITEM_ID']] = $arPrice['RESULT_PRICE'];
                            $arProductJsonList[$price['ITEM_ID']] = $arPrice['RESULT_PRICE'];
                        }
                    }

                    if(count($arProductJsonList) > 0)
                        $arProductPrice[$kitId] = $arProductJsonList;
                }
            }
        }
    }
}


// форматуємо масив для `template`
$arName = ['arQuestions','arAnswers','QUESTIONS',];
foreach ($arName as $name)
{
    $arResultMod = [];

    foreach ($arResult[$name] as $key=>$item)
    {
        $arr = explode('_', $key);

        if(count($arr) == 4)
        {
            if(!is_array($arResultMod[$arr[0].'_'.$arr[1].'_'.$arr[2]]['Q_DATA']))
                $arResultMod[$arr[0].'_'.$arr[1].'_'.$arr[2]]['Q_DATA'] = [];

            if($name == 'QUESTIONS') $item['FIELD_NAME'] = $key;

            $arResultMod[$arr[0].'_'.$arr[1].'_'.$arr[2]]['Q_DATA'][$arr[3]] = $item;
        }
        elseif (count($arr) == 3)
        {
            $arResultMod[$key] = $item;

        }
    }

    $arResult[$name] = $arResultMod;
    unset($arResultMod, $arr);
}

$arResult["POOL_PARAMS"] = [
    'dpId' => 'data-product-id', // name for data parameters
    'arProductPrice' => $arProductPrice,
    'workCatalogID' => '11', // каталог цін за роботу
    'isOpenPdf' => [],
];

?>