<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
global $defaultBlockWorkId;
$defaultBlockWorkId = 702; // калог работ

\Bitrix\Main\Loader::includeModule('catalog');
include 'include.php';

// https://podtek.ru/pool_calculation_result_test/result.php?WEB_FORM_ID=1&RESULT_ID=86&formresult=addok
// if($USER->IsAdmin()) {echo '<pre>'; print_r($arResult); echo '</pre>';}; die();

$usrLogoSCR = "";
$usrNoLogoSCR = 'https://podtek.ru/include/podtek_logo.jpg';
$myFormID = $arResult["RESULT_ID"];

foreach ($arResult["RESULT"] as $FIELD_SID => $arQuestion) {
    if (!empty($arQuestion['ANSWER_VALUE']) && is_array($arQuestion['ANSWER_VALUE'])) {
        foreach ($arQuestion['ANSWER_VALUE'] as $key => $arAnswer) {
            if (isset($arAnswer["ANSWER_IMAGE"])) { // LOGO
                $usrLogoSCR = $arAnswer["ANSWER_IMAGE"]["URL"];
            } else {
                $usrLogoSCR = $usrNoLogoSCR;
            }

            if ($FIELD_SID == "new_field_0002") { // Размеры бассейна
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

            if ($FIELD_SID == "new_field_0001"): // Тип бассейна
                $myTypeOfPool = $arAnswer['ANSWER_TEXT'];
            endif;
            if ($FIELD_SID == "new_field_0001_0"): // Пленка ПВХ
                $myColorOfTheFilmId = $arAnswer['ANSWER_VALUE'];
            endif;
            if ($FIELD_SID == "new_field_0004"): // Лестница
                $intStairsID = $arAnswer['ANSWER_VALUE'];
            endif;
            if ($FIELD_SID == "new_field_0006"): // Фильтровальная установка
                $intFilterID = $arAnswer['ANSWER_VALUE'];
            endif;
            if ($FIELD_SID == "new_field_0007"): // Сервисный набор
                $intServiceID = $arAnswer['ANSWER_VALUE'];
            endif;
            if ($FIELD_SID == "new_field_0008"): // Закладные
                $intMortgagesID = $arAnswer['ANSWER_VALUE'];
            endif;
            if ($FIELD_SID == "new_field_0009"): // Подогрев
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

// Общая сума
$allWorkSum = 0;
$allProductSum = 0;

// --- Пленка ПВХ ---
$arSets = CCatalogProductSet::getAllSetsByProduct($myColorOfTheFilmId, CCatalogProductSet::TYPE_SET); // массив комплектов данного товара
$arSet = array_shift($arSets); // комплект данного товара
usort($arSet['ITEMS'], function($a, $b) {
    return $a['SORT'] > $b['SORT'];
});
$i = 0;
$arColTab = '';
$itogWorkSuma = 0;
$itogProductSuma = 0;
foreach ($arSet['ITEMS'] as $myItems => $myOllItems)
{
    $i++;
    $rest = substr($myOllItems['QUANTITY'], -5);
    $myQuantityItem = ($myOllItems['QUANTITY'] - $rest) / 100000;

    // product
    $db_res = CIBlockElement::GetList(array(), array("ID" => $myOllItems['ITEM_ID']), false, false, array('IBLOCK_SECTION_ID', 'NAME'))->GetNext();
    $myNameItem = $db_res['NAME'];
    $blockWorkId = $db_res['IBLOCK_SECTION_ID'];

    // price
    $arPrice = CCatalogProduct::GetOptimalPrice($myOllItems['ITEM_ID'], 1, $USER->GetUserGroupArray(), 'N');
    if (empty($arPrice)) continue;

    $myPrice = $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'] ?? $arPrice['RESULT_PRICE']['BASE_PRICE'];

    if ($rest == 99999) {
        $myQuantityItem = $myBasinAreaForFilms;
    } elseif ($rest == 88888) {
        $drob = ($myPerimeterOfTheBasin / $myQuantityItem) - intval($myPerimeterOfTheBasin / $myQuantityItem);
        if ($drob > 0) {
            $myPerimeterOfTwoMeters = intval($myPerimeterOfTheBasin / $myQuantityItem) + 1;
        } else {
            $myPerimeterOfTwoMeters = intval($myPerimeterOfTheBasin / $myQuantityItem);
        }
        $myQuantityItem = $myPerimeterOfTwoMeters;

    } elseif ($rest == 77777) {
        $PerimeterOfTheBasin = ceil($myPerimeterOfTheBasin);
        $myQuantityItem = $myQuantityItem * $PerimeterOfTheBasin;

    } elseif ($rest == 55555) {
        $AreaMirrorsOfWater = ceil($myAreaMirrorsOfWater);
        $myQuantityItem = $myQuantityItem * $AreaMirrorsOfWater;
	} elseif ($rest == 33333) {
        $AreaOfTheBasin = ceil($myAreaOfTheBasin);
        $myQuantityItem = $myQuantityItem * $AreaOfTheBasin;
    }

    $style = '';
    if($blockWorkId == $defaultBlockWorkId) {
        $style = 'color:darkslategrey;';
        $itogWorkSuma += $myPrice * $myQuantityItem;
    } else {
        $itogProductSuma += $myPrice * $myQuantityItem;
    }

    // add table `tr`
    $arColTab = $arColTab . '<tr>'
        .'<td style="width:5%;padding-bottom:50px;'.$style.'">' . $i . '</td>'
        .'<td style="width:43%;'.$style.'">' . $myNameItem . '</td>'
        .'<td style="width:17%;text-align:center;'.$style.'">' . $myQuantityItem . '</td>'
        .'<td style="width:15%;text-align:right;'.$style.'">'. $myPrice . '</td>'
        .'<td style="width:20%;text-align:right;'.$style.'">' . ($myPrice * $myQuantityItem) . '</td>'
        .'</tr>';
}
$arColTab .= '<tr><td colspan="5"><br></td></tr>';
$arColTab = MakeProductTable($arColTab, $myTypeOfPool);
$allWorkSum += $itogWorkSuma;
$allProductSum += $itogProductSuma;

// --- Лестница ---
$intStairsID = GetIdProductByVolume($intStairsID, $myVolumePool);
$currentData = getTrTableListAndPriceSum($intStairsID);
$arStairColTab = MakeProductTable($currentData['trList'], 'Лестница');
//$allProdSum = $allProdSum + $currentData['priceSum'];
$allWorkSum += $currentData['itogWorkSuma'];
$allProductSum += $currentData['itogProductSuma'];

// --- Фильтровальная уставнока ---
$intFilterID = GetIdProductByVolume($intFilterID, $myVolumePool);
$currentData = getTrTableListAndPriceSum($intFilterID);
$arFilterColTab = MakeProductTable($currentData['trList'], 'Фильтровальная уставнока');
//$allProdSum = $allProdSum + $currentData['priceSum'];
$allWorkSum += $currentData['itogWorkSuma'];
$allProductSum += $currentData['itogProductSuma'];

// --- Сервисной набор ---
$intServiceID = GetIdProductByVolume($intServiceID, $myVolumePool);
$currentData = getTrTableListAndPriceSum($intServiceID);
$arServiceColTab = MakeProductTable($currentData['trList'], 'Сервисный набор');
//$allProdSum = $allProdSum + $currentData['priceSum'];
$allWorkSum += $currentData['itogWorkSuma'];
$allProductSum += $currentData['itogProductSuma'];

// --- Закладные ---
$intMortgagesID = GetIdProductByVolume($intMortgagesID, $myVolumePool);
$currentData = getTrTableListAndPriceSum($intMortgagesID);
$arMortgagesColTab = MakeProductTable($currentData['trList'], 'Закладные');
//$allProdSum = $allProdSum + $currentData['priceSum'];
$allWorkSum += $currentData['itogWorkSuma'];
$allProductSum += $currentData['itogProductSuma'];

// --- Подогрев ---
$intHeatingID = GetIdProductByVolume($intHeatingID, $myVolumePool);
$currentData = getTrTableListAndPriceSum($intHeatingID);
$arHeatingColTab = MakeProductTable($currentData['trList'], 'Подогрев');
//$allProdSum = $allProdSum + $currentData['priceSum'];
$allWorkSum += $currentData['itogWorkSuma'];
$allProductSum += $currentData['itogProductSuma'];

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
    <tr>
        <td colspan="5"><br></td>        
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
            <td colspan="2" align="right">' . CCurrencyLang::CurrencyFormat($allProductSum, 'RUB') . '</td>
        </tr>'
    . '<tr>
            <td colspan="3" style="color:darkslategrey;">Работы:</td>
            <td colspan="2" style="color:darkslategrey;" align="right">' . CCurrencyLang::CurrencyFormat($allWorkSum, 'RUB') . '</td>
        </tr>'
    .'<tr>      
            <td colspan="3"><b>Итого:</b></td>
            <td colspan="2" align="right"><b>' . CCurrencyLang::CurrencyFormat($allWorkSum + $allProductSum, 'RUB') . '</b></td>
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