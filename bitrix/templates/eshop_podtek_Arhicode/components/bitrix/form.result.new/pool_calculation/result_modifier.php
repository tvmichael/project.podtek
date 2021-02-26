<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $arResult
 * @var CBitrixComponentTemplate $arParams
 */

$arProductPrice = [];

foreach ($arResult['arAnswers'] as $fieldId => $resItems)
{
    foreach ($resItems as $item) // перебираємо всі 'інпути' з відповідями
    {
        if(is_numeric($item['VALUE'])) // якщо значення 'інпута' це число
        {
            $kitId = intval($item['VALUE']);

            if($kitId > 0 )
            {
                // якщо $item['VALUE'] комплект товару то отримаємо список товарів
                $arKit = CCatalogProductSet::getAllSetsByProduct($kitId, CCatalogProductSet::TYPE_SET);
                foreach ($arKit as $key => $product)
                {
                    $arProductPriceList = [];
                    foreach ($product['ITEMS'] as $price)
                    {
                        $arPrice = CCatalogProduct::GetOptimalPrice($price['ITEM_ID'], 1, $USER->GetUserGroupArray(), 'N');

                        if(empty($arPrice)) continue;

                        $dbRes= CCatalogProduct::GetList(array(), array("ID" => $price['ITEM_ID'] ),false, array());

                        if($ar = $dbRes->Fetch())
                            $arPrice['RESULT_PRICE']['ELEMENT_NAME'] = $ar['ELEMENT_NAME'];

                        $arPrice['RESULT_PRICE']['QUANTITY'] = $price['QUANTITY'];
                        $arPrice['RESULT_PRICE']['BLOCK_ID'] = CIBlockElement::GetIBlockByID($price['ITEM_ID']);
                        $arPrice['RESULT_PRICE']['FIELD_ID'] = $fieldId;

                        $arProductPriceList[$price['ITEM_ID']] = $arPrice['RESULT_PRICE'];
                    }
                }

                if(count($arProductPriceList) > 0)
                    $arProductPrice[$kitId] = $arProductPriceList;
            }
        }
        elseif ( is_string($item['VALUE']) )
        {
            $jsonStr = str_replace("'", '"', $item['VALUE']);
            $jsonOb = json_decode($jsonStr);

            if(is_object($jsonOb) || is_array($jsonOb))
            {
                foreach ($jsonOb as $js) // список ІД товаров з json строки
                {
                    $kitId = intval($js);
                    $arProductJsonList = [];

                    if(!$kitId) continue;

                    $arKit = CCatalogProductSet::getAllSetsByProduct($kitId, CCatalogProductSet::TYPE_SET);

                    foreach ($arKit as $key => $product)
                    {
                        foreach ($product['ITEMS'] as $price)
                        {
                            $arPrice = CCatalogProduct::GetOptimalPrice($price['ITEM_ID'], 1, $USER->GetUserGroupArray(), 'N');

                            $dbRes= CCatalogProduct::GetList(array(),array("ID" => $price['ITEM_ID'] ),false,array());
                            if($ar = $dbRes->Fetch())
                                $arPrice['RESULT_PRICE']['ELEMENT_NAME'] = $ar['ELEMENT_NAME'];

                            $arPrice['RESULT_PRICE']['QUANTITY'] = $price['QUANTITY'];
                            $arPrice['RESULT_PRICE']['BLOCK_ID'] = CIBlockElement::GetIBlockByID($price['ITEM_ID']);
                            $arPrice['RESULT_PRICE']['FIELD_ID'] = $fieldId;

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


// формируем масив для `template`
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
    'workCatalogID' => '11', // каталог цен за работу
    'isOpenPdf' => [],
];

?>