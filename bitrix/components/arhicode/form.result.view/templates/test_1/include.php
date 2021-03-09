<?
function GetFormId($WEB_FORM_ID)
{
    $myFormId = $WEB_FORM_ID;
    return $myFormId;
}

function GetResultId($RESULT_ID)
{
    $myResultId = $RESULT_ID;
    return $myResultId;
}

function GetAreaMirrorsOfWater($myLong, $myWidth)
{
    $myAreaMirrorsOfWater = $myLong * $myWidth;
    return $myAreaMirrorsOfWater;
}

function GetAreaOfTheBasin($myLong, $myWidth, $myDepth)
{
    $myAreaOfTheBasin = ($myLong + $myWidth) * 2 * $myDepth + $myLong * $myWidth;
    return $myAreaOfTheBasin;
}

function GetBasinAreaForFilms($myLong, $myWidth, $myDepth)
{
    $BasinAreaForFilms = (($myLong + $myWidth) * 2 * $myDepth + $myLong * $myWidth) * 1.1; // 1.2
    $myBasinAreaForFilms = ceil($BasinAreaForFilms);
    return $myBasinAreaForFilms;
}

function GetBasinAreaForTile($myLong, $myWidth, $myDepth)
{
    $BasinAreaForTile = (($myLong + $myWidth) * 2 * $myDepth + $myLong * $myWidth) * 1.1;
    $myBasinAreaForTile = ceil($BasinAreaForTile);
    return $myBasinAreaForTile;
}

function GetPerimeterOfTheBasin($myLong, $myWidth)
{

    $myPerimeterOfTheBasin = ($myLong + $myWidth) * 2;
    return $myPerimeterOfTheBasin;
}

function GetIdProductByVolume($string = '', $volume = 0)
{
    $id = 0;
    try
    {
        if(empty($string))
        {
            return $id;
        }

        if(is_int($string))
        {
            return intval($string);
        }

        $string = str_replace("&#39;", '"', $string);
        $arr = json_decode($string, true);

        if(is_array($arr))
        {
            ksort($arr);
            foreach ($arr as $k => $i)
            {
                if($volume <= $k)
                {
                    $id = $i;
                    break;
                }
            }
        }
    }
    catch (\Exception $e)
    {}

    return $id;
}

function MakeProductTable($innerTr = '', $title = '', $params = null)
{
    if(empty($innerTr))
    {
        return '';
    }

    $table_header = '<thead><tr>'
        .'<th style="width:5%;"></th>'
        .'<th style="width:95%;" colspan="4"><b>' . $title . '</b></th>'
        .'</tr>'
        .'<thead>';

    $table = '<table border="0" cellpadding="3" style="width:100%;">'
        . $table_header
        . '<tbody>'
        . $innerTr
        . '</tbody></table>';

    return $table;
}

function MakeInnerTrForTable($arr = null, $params = [])
{
    if(!is_array($arr) || !isset($arr[4]))
    {
        return '';
    }

    $style = '';
    if(isset($params['iblockId']) && $params['iblockId'] == 11) // блок работ
    {
        $style = 'color:darkslategrey;';
    }

    $tr = '<tr>'
        .'<td style="width:5%;'.$style.'">' . $arr[0] . '</td>'
        .'<td style="width:43%;'.$style.'">' . $arr[1] . '</td>'
        .'<td style="width:17%;text-align:center;'.$style.'">' . $arr[2] . '</td>'
        .'<td style="width:15%;text-align:right;'.$style.'">' . $arr[3] . '</td>'
        .'<td style="width:20%;text-align:right;'.$style.'">' . $arr[4] . '</td>'
        .'</tr>';

    return $tr;
}

function getTrTableListAndPriceSum($intID)
{
    global $USER;

    if(!is_int($intID) || $intID == 0)
    {
        return ['trList' => '', 'priceSum' => 0];
    }

    $arTrTable = '';
    $arProductSet = CCatalogProductSet::getAllSetsByProduct($intID, CCatalogProductSet::TYPE_SET); // массив комплектов данного товара
    $arProductSet = array_shift($arProductSet);
    $itogWorkSuma = 0;
    $itogProductSuma = 0;
    $k = 1;

    foreach ($arProductSet['ITEMS'] as $myItems)
    {
        // product
        $dbRes = CCatalogProduct::GetList(array(), array("ID" => $myItems['ITEM_ID']), false, array());
        while ($arRes = $dbRes->Fetch()) {
            $myNameItem = $arRes['ELEMENT_NAME'];
            $iblockId = $arRes['ELEMENT_IBLOCK_ID'];
        }

        // price
        $arPrice = CCatalogProduct::GetOptimalPrice($myItems['ITEM_ID'], 1, $USER->GetUserGroupArray(), 'N');

        // количество товара, доступное для покупки
        //if (!$arPrice || count($arPrice) <= 0) {
        //    if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($intID, 1, $USER->GetUserGroupArray())) {
        //        $arPrice = CCatalogProduct::GetOptimalPrice($intID, $nearestQuantity, $USER->GetUserGroupArray(), false);
        //    }
        //}

        $resultPrice = $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'] ?? $arPrice['RESULT_PRICE']['BASE_PRICE'];

        if($iblockId == 11) { // калог работ
            $itogWorkSuma += $resultPrice * $myItems['QUANTITY'];
        } else {
            $itogProductSuma += $resultPrice * $myItems['QUANTITY'];
        }

        $arTrTable .= MakeInnerTrForTable([
            $k,
            $myNameItem,
            $myItems['QUANTITY'],
            $resultPrice,
            $resultPrice * $myItems['QUANTITY'],
        ],['iblockId' => $iblockId]);

        $k++;
    }
    $arTrTable .= MakeInnerTrForTable(['','','','','']);

    return ['trList' => $arTrTable, 'itogWorkSuma' => $itogWorkSuma, 'itogProductSuma' => $itogProductSuma];
}

?>
