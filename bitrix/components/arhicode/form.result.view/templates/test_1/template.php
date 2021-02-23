<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
\Bitrix\Main\Loader::includeModule('catalog');
include 'include.php';

// https://podtek.ru/pool_calculation_result_test/result.php?WEB_FORM_ID=1&RESULT_ID=86&formresult=addok
//if($USER->IsAdmin()) {echo '<pre>'; print_r($arResult); echo '</pre>';}; die();

$usrLogoSCR = "";
$usrNoLogoSCR = 'https://podtek.ru/include/podtek_logo.jpg';
$myFormID = $_REQUEST["RESULT_ID"];

foreach ($arResult["RESULT"] as $FIELD_SID => $arQuestion) {
    if (is_array($arQuestion['ANSWER_VALUE'])) {
        foreach ($arQuestion['ANSWER_VALUE'] as $key => $arAnswer) {
            if (isset($arAnswer["ANSWER_IMAGE"])) { // LOGO
                $usrLogoSCR = $arAnswer["ANSWER_IMAGE"]["URL"];
            } else {
                $usrLogoSCR = $usrNoLogoSCR;
            }

            if ($FIELD_SID == "new_field_0002") { //Размеры бассейна
                switch ($arAnswer['ANSWER_VALUE']) {
                    case "length":
                        $myLong = floatval($arAnswer['USER_TEXT']);
                        break;
                    case "width":
                        $myWidth = floatval($arAnswer['USER_TEXT']);
                        break;
                    case "deep":
                        $myDepth = floatval($arAnswer['USER_TEXT']);
                        break;
                }
            }

            if ($FIELD_SID == "new_field_0001"): //тип бассейна
                $myTypeOfPool = $arAnswer['ANSWER_TEXT'];
            endif;
            if ($FIELD_SID == "new_field_0001_0"): //Пленка ПВХ
                $myColorOfTheFilm = $arAnswer['ANSWER_VALUE'];
            endif;
            if ($FIELD_SID == "new_field_0004"): //Лестница
                $intStairsID = $arAnswer['ANSWER_VALUE'];
            endif;
            if ($FIELD_SID == "new_field_0006"): //Фильтровальная установка
                $intFilterID = $arAnswer['ANSWER_VALUE'];
            endif;
            if ($FIELD_SID == "new_field_0007"): //Сервисный набор
                $intServiceID = $arAnswer['ANSWER_VALUE'];
            endif;
            if ($FIELD_SID == "new_field_0008"): //Закладные
                $intMortgagesID = $arAnswer['ANSWER_VALUE'];
            endif;
            if ($FIELD_SID == "new_field_0009"): //Подогрев
                $intHeatingID = $arAnswer['ANSWER_VALUE'];
            endif;
        }
    }
}

//Рассчитаем зависимые данные: //Площадь Зеркала Воды //$myAreaMirrorsOfWater =$myLong * $myWidth;
$myAreaMirrorsOfWater = GetAreaMirrorsOfWater($myLong, $myWidth);
//Площадь Бассейна	//$myAreaOfTheBasin =($myLong + $myWidth)*2*$myDepth + $myAreaMirrorsOfWater;
$myAreaOfTheBasin = GetAreaOfTheBasin($myLong, $myWidth, $myDepth);
//Площадь Бассейна Для Пленки 	//$BasinAreaForFilms =$myAreaOfTheBasin*1.2; //$myBasinAreaForFilms = ceil($BasinAreaForFilms);
$myBasinAreaForFilms = GetBasinAreaForFilms($myLong, $myWidth, $myDepth);
//Площадь Бассейна Для Плитки	//$myBasinAreaForTile =$myAreaOfTheBasin*1.1;
$myBasinAreaForTile = GetBasinAreaForTile($myLong, $myWidth, $myDepth);
//Периметр Бассейна	//$myPerimeterOfTheBasin =($myLong + $myWidth)*2;
$myPerimeterOfTheBasin = GetPerimeterOfTheBasin($myLong, $myWidth, $myDepth);
//Обем басейна
$myVolumePool = $myLong * $myWidth * $myDepth;


// --- Пленка ПВХ ---
$arSets = CCatalogProductSet::getAllSetsByProduct($myColorOfTheFilm, CCatalogProductSet::TYPE_SET); // массив комплектов данного товара
$arSet = array_shift($arSets); // комплект данного товара
usort($arSet['ITEMS'], function($a, $b) {
    return $a['SORT'] > $b['SORT'];
});
$i = 0;
$ItogSuma = 0;
$allProdSum = 0;
$arColTab = '';
foreach ($arSet['ITEMS'] as $myItems => $myOllItems)
{
    $i++;
    $ID = $myOllItems['ITEM_ID'];
    $rest = substr($myOllItems['QUANTITY'], -5);
    $myQuantityItem = ($myOllItems['QUANTITY'] - $rest) / 100000;

    // product
    $db_res = CCatalogProduct::GetList(array(), array("ID" => $ID), false, array());
    while (($ar_res = $db_res->Fetch())) {
        $myNameItem = $ar_res['ELEMENT_NAME'];
    }

    // price
    $arPrice = CCatalogProduct::GetOptimalPrice($ID, 1, $USER->GetUserGroupArray(), 'N');

    if (!$arPrice || count($arPrice) <= 0) {
        if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($productID, $quantity, $USER->GetUserGroupArray())) {
            $quantity = $nearestQuantity;
            $arPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $USER->GetUserGroupArray(), $renewal);
        }
    }

    $myPrice = $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'] ?? $arPrice['RESULT_PRICE']['BASE_PRICE'];

    if ($rest == 99999) {
        $myValueItem = $myPrice * $myBasinAreaForFilms;
        $myQuantityItem = $myBasinAreaForFilms;
    } elseif ($rest == 88888) {
        $drob = ($myPerimeterOfTheBasin / $myQuantityItem) - intval($myPerimeterOfTheBasin / $myQuantityItem);
        if ($drob > 0) {
            $myPerimeterOfTwoMeters = intval($myPerimeterOfTheBasin / $myQuantityItem) + 1;
        } else {
            $myPerimeterOfTwoMeters = intval($myPerimeterOfTheBasin / $myQuantityItem);
        }
        $myQuantityItem = $myPerimeterOfTwoMeters;
        $myValueItem = $myPrice * $myPerimeterOfTwoMeters;
    } elseif ($rest == 77777) {
        $PerimeterOfTheBasin = ceil($myPerimeterOfTheBasin);
        $myQuantityItem = $myQuantityItem * $PerimeterOfTheBasin;
        $myValueItem = $myPrice * $myQuantityItem;
    } elseif ($rest == 55555) {
        $AreaMirrorsOfWater = ceil($myAreaMirrorsOfWater);
        $myQuantityItem = $myQuantityItem * $AreaMirrorsOfWater;
        $myValueItem = $myPrice * $myQuantityItem;
    } else {
        $myValueItem = $myPrice * $myQuantityItem;
    }
    $ItogSuma = $ItogSuma + $myValueItem;
    $arMyNameItem[] = $myNameItem;
    $arMyQuantityItem[] = $myQuantityItem;
    $arMyPrice[] = $myPrice;
    $arColNum[] = $i;
    $arMyValueItem[] = $myValueItem;

    // add table `tr`
    $arColTab = $arColTab . '<tr>'
        .'<td style="width:5%;padding-bottom:50px;">' . $i . '</td>'
        .'<td style="width:43%;">' . $myNameItem . '</td>'
        .'<td style="width:17%;text-align:center;">' . $myQuantityItem . '</td>'
        .'<td style="width:15%;text-align:right;">'. $myPrice . '</td>'
        .'<td style="width:20%;text-align:right;">' . $myValueItem . '</td>'
        .'</tr>';
}
$arColTab = MakeProductTable($arColTab, $myTypeOfPool);
$allProdSum = $allProdSum + $ItogSuma;

// --- Лестница ---
$intStairsID = GetIdProductByVolume($intStairsID, $myVolumePool);
$currentData = getTrTableListAndPriceSum($intStairsID);
$arStairColTab = MakeProductTable($currentData['trList'], 'Лестница');
$allProdSum = $allProdSum + $currentData['priceSum'];

// --- Фильтровальная уставнока ---
$intFilterID = GetIdProductByVolume($intFilterID, $myVolumePool);
$currentData = getTrTableListAndPriceSum($intFilterID);
$arFilterColTab = MakeProductTable($currentData['trList'], 'Фильтровальная уставнока');
$allProdSum = $allProdSum + $currentData['priceSum'];

// --- Сервисной набор ---
$intServiceID = GetIdProductByVolume($intServiceID, $myVolumePool);
$currentData = getTrTableListAndPriceSum($intServiceID);
$arServiceColTab = MakeProductTable($currentData['trList'], 'Сервисный набор');
$allProdSum = $allProdSum + $currentData['priceSum'];

// --- Закладные ---
$intMortgagesID = GetIdProductByVolume($intMortgagesID, $myVolumePool);
$currentData = getTrTableListAndPriceSum($intMortgagesID);
$arMortgagesColTab = MakeProductTable($currentData['trList'], 'Закладные');
$allProdSum = $allProdSum + $currentData['priceSum'];

// --- Подогрев ---
$intHeatingID = GetIdProductByVolume($intHeatingID, $myVolumePool);
$currentData = getTrTableListAndPriceSum($intHeatingID);
$arHeatingColTab = MakeProductTable($currentData['trList'], 'Подогрев');
$allProdSum = $allProdSum + $currentData['priceSum'];

// --- PREPEA TABLE ----------------------------------------------------------------------------------------------------
$top_table = '<table border="0" cellpadding="2" style="width:100%;">    
    <tr>
        <td colspan="3" align="right">
            <h3>Коммерческое предложение № ' . $myFormID . '</h3><br>
        </td>
    </tr>
    <tr>
        <td colspan="3" align="left"><br><b>Параметры Вашего бассейна:</b></td>
    </tr>
    <tr>
        <td>длина, м</td>
        <td>' . $myLong . '</td>
        <td></td>
    </tr>
    <tr>
        <td>ширина, м</td>
        <td>' . $myWidth . '</td>
        <td></td>
    </tr>
    <tr>
        <td>глубина, м</td>
        <td>' . $myDepth . '</td>
        <td></td>
    </tr>
</table><br><br>';

$product_tables = '<table style="width:100%;"><thead><tr>'
        .'<th style="width:5%;"><b>№</b><br></th>'
        .'<th style="width:43%;"><b>Наименование материалов</b></th>'
        .'<th style="width:17%;text-align:center;"><b>Количество</b></th>'
        .'<th style="width:15%;text-align:right;"><b>Цена</b></th>'
        .'<th style="width:20%;text-align:right;"><b>Сумма</b></th>'
        .'</tr>'
    .'<thead><tbody><tr><td colspan="5"><hr></td></tr></tbody></table>'
        .$arColTab
        .$arStairColTab
        .$arFilterColTab
        .$arServiceColTab
        .$arMortgagesColTab
        .$arHeatingColTab;

$result_table = '<table style="width:100%;"><thead>'
    . '<tr><td colspan="5"><br></td></tr>'
    . '<tr><td colspan="5" style="border-top: 1px solid #e6e6e6;"></td></tr>'
    . '<tr>
            <td colspan="3">Oборудование и материалы:</td>
            <td colspan="2"></td>
        </tr>'
    . '<tr>
            <td colspan="3">Работы:</td>
            <td colspan="2"></td>
        </tr>'
    .'<tr>      
            <td colspan="3"><b>Итого:</b></td>
            <td colspan="2" align="right"><b>' . CCurrencyLang::CurrencyFormat($allProdSum, 'RUB') . '</b></td>
        </tr>'
    .'</thead></table>';

$html_document_pdf = $top_table . $product_tables . $result_table;

// --- PDF -------------------------------------------------------------------------------------------------------------
$GLOBALS['APPLICATION']->RestartBuffer();
ob_end_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . "/tcpdf/tcpdf.php";

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator('podtek');
$pdf->SetAuthor('podtek.ru');
$pdf->SetTitle('Коммерческое предложение №' . $myFormID);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(20, 10, 20);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_FOOTER);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->SetFont('dejavusans', '', 10);

$lg = Array();
$lg['a_meta_charset'] = 'UTF-8';
$lg['a_meta_dir'] = 'ltr';
$lg['a_meta_language'] = 'ru';
$lg['w_page'] = 'страница';
$pdf->setLanguageArray($lg);

// -- page ---

$pdf->AddPage();

$pdf->setJPEGQuality(75);
$pdf->Image($usrLogoSCR, 15, 14, 50, 0, 'jpg', 'https://podtek.ru/', '', true, 150, '', false, false, 0, false, false, false);

$pdf->SetFont('dejavusans', '', 10);

$pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(55, 100, 100)));

$tbl = <<<EOD
{$html_document_pdf}
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

$pdf->Output('Commercial_offer_№' . $myFormID .'.pdf', 'I');
die();
?>