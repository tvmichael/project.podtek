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
    $BasinAreaForFilms = (($myLong + $myWidth) * 2 * $myDepth + $myLong * $myWidth) * 1.2;
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

function MakeInnerTrForTable($arr = null)
{
    if(!is_array($arr) && count($arr) < 3)
    {
        return '';
    }

    $tr = '<tr>'
        .'<td style="width:5%;">' . $arr[0] . '</td>'
        .'<td style="width:43%;">' . $arr[1] . '</td>'
        .'<td style="width:17%;text-align:center;">' . $arr[2] . '</td>'
        .'<td style="width:15%;text-align:right;">' . $arr[3] . '</td>'
        .'<td style="width:20%;text-align:right;">' . $arr[4] . '</td>'
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
    $priceSum = 0;
    $k = 1;

    foreach ($arProductSet['ITEMS'] as $myItems)
    {
        //товары
        $dbRes = CCatalogProduct::GetList(array(), array("ID" => $myItems['ITEM_ID']), false, array());
        while (($arRes = $dbRes->Fetch())) {
            $myNameItem = $arRes['ELEMENT_NAME'];
        }

        //цены
        $arPrice = CCatalogProduct::GetOptimalPrice($myItems['ITEM_ID'], 1, $USER->GetUserGroupArray(), 'N');

        if (!$arPrice || count($arPrice) <= 0) {
            // ця частина коду не перевірялася ...
            if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($intID, 1, $USER->GetUserGroupArray())) {
                $quantity = $nearestQuantity;
                $renewal = false;
                $arPrice = CCatalogProduct::GetOptimalPrice($intID, $quantity, $USER->GetUserGroupArray(), $renewal);
            }
            // ....
        }

        $myValueItem = $arPrice['RESULT_PRICE']['BASE_PRICE'] * $myItems['QUANTITY'];
        $priceSum += $myValueItem;

        $arTrTable .= MakeInnerTrForTable([
            $k,
            $myNameItem,
            $myItems['QUANTITY'],
            $arPrice['RESULT_PRICE']['BASE_PRICE'],
            $myValueItem,
        ]);

        $k++;
    }

    return ['trList' => $arTrTable, 'priceSum' => $priceSum];
}

?>
