<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
\Bitrix\Main\Loader::includeModule('catalog');
//$GLOBALS['APPLICATION']->RestartBuffer();
include 'include.php';
?>
<?//if($USER->IsAdmin()) {echo '<pre>'; print_r($arResult); echo '</pre>';};?>

<?
$usrLogoSCR = "";
$usrNoLogoSCR = "https://podtek.ru/include/logo.png";
$myFormID = $_REQUEST["RESULT_ID"];
?>
<?
foreach ($arResult["RESULT"] as $FIELD_SID => $arQuestion)
{
?>

	<?
	if (is_array($arQuestion['ANSWER_VALUE'])):
		foreach ($arQuestion['ANSWER_VALUE'] as $key => $arAnswer)
		{?>
			<?if ($arAnswer["ANSWER_IMAGE"]):// LOGO?>
				<?$usrLogoSCR=$arAnswer["ANSWER_IMAGE"]["URL"]; $usrNoLogoheight = '';?>
			<?else:?>
				<?$usrLogoSCR = $usrNoLogoSCR; $usrNoLogoheight = 'style="height: 62px;"';?>
			<?endif;?>	
			<?if ($FIELD_SID == "new_field_0002")://Размеры бассейна?>
				<?switch ($arAnswer['ANSWER_TEXT']) {
					case "Длинна":
						$myLong = $arAnswer['USER_TEXT'];
						break;
					case "Ширина":
						$myWidth = $arAnswer['USER_TEXT'];
						break;
					case "Глубина":
						$myDepth = $arAnswer['USER_TEXT'];
						break;	}	?>
			<?endif;?>
			<?if ($FIELD_SID == "new_field_0001")://тип бассейна?>			
			<?$myTypeOfPool = $arAnswer['ANSWER_TEXT'];//echo '<pre>'; print_r($arAnswer); echo '</pre>';?><?endif;?>
			<?if ($FIELD_SID == "new_field_0001_0")://Пленка ПВХ?>			
			<?$myColorOfTheFilm = $arAnswer['ANSWER_VALUE'];?><?endif;?>
			<?if ($FIELD_SID == "new_field_0004"): //Лестница?>	
			<?//echo '<pre>'; print_r($arAnswer); echo '</pre>';$myNameofStairs = $arAnswer['ANSWER_TEXT'];?><?$intStairsID = $arAnswer['ANSWER_VALUE'];?><?endif;?>
			
			<?if ($FIELD_SID == "new_field_0007"): //Сервисный набор?><?$intServiceID = $arAnswer['ANSWER_VALUE'];?><?endif;?>
		
			<?if ($FIELD_SID == "new_field_0008"): //Закладные?><?$intMortgagesID = $arAnswer['ANSWER_VALUE'];?><?endif;?>
				
			<?if ($FIELD_SID == "new_field_0009"): //Подогрев?><?$intHeatingID = $arAnswer['ANSWER_VALUE'];?><?endif;?>
		<?} //foreach ($arQuestions)
		
	endif;?>
	
		
<?} //echo '<pre>'; print_r($myTypeOfStairs); echo '</pre>'; // foreach ($arResult["RESULT"])
?>
<?	//Рассчитаем зависимые данные: //Площадь Зеркала Воды //$myAreaMirrorsOfWater =$myLong * $myWidth;
$myAreaMirrorsOfWater = GetAreaMirrorsOfWater($myLong, $myWidth);
//Площадь Бассейна	//$myAreaOfTheBasin =($myLong + $myWidth)*2*$myDepth + $myAreaMirrorsOfWater;
$myAreaOfTheBasin = GetAreaOfTheBasin($myLong, $myWidth, $myDepth);
//Площадь Бассейна Для Пленки 	//$BasinAreaForFilms =$myAreaOfTheBasin*1.2; //$myBasinAreaForFilms = ceil($BasinAreaForFilms);
$myBasinAreaForFilms = GetBasinAreaForFilms($myLong, $myWidth, $myDepth);
//Площадь Бассейна Для Плитки	//$myBasinAreaForTile =$myAreaOfTheBasin*1.1;
$myBasinAreaForTile = GetBasinAreaForTile($myLong, $myWidth, $myDepth);
//Периметр Бассейна	//$myPerimeterOfTheBasin =($myLong + $myWidth)*2;
$myPerimeterOfTheBasin = GetPerimeterOfTheBasin($myLong, $myWidth, $myDepth);
?>
<?//Пленка ПВХ
$arSets = CCatalogProductSet::getAllSetsByProduct($myColorOfTheFilm, CCatalogProductSet::TYPE_SET); // массив комплектов данного товара

//if($USER->IsAdmin()) {echo '<pre>'; print_r($myColorOfTheFilm); print_r($arSets); echo '</pre>'; die();};

$arSet = array_shift($arSets); // комплект данного товара
$i=0;
$ItogSuma = 0;
$allProdSum = 0;
$arColTab = '';

/**
 *
 *
 *
**/
foreach ($arSet['ITEMS'] as $myItems => $myOllItems)
{
    $i++;
    $ID = $myOllItems['ITEM_ID'];
    $rest = substr($myOllItems['QUANTITY'], -5);
    $myQuantityItem = ($myOllItems['QUANTITY'] - $rest) / 100000;
    //товары
    $db_res = CCatalogProduct::GetList(array(), array("ID" => $ID), false, array());
    while (($ar_res = $db_res->Fetch())) {
        $myNameItem = $ar_res['ELEMENT_NAME']; /* $i=$i+1; */
    }
    //цены
    $arPrice = CCatalogProduct::GetOptimalPrice($ID, 1, $USER->GetUserGroupArray(), 'N');
    if (!$arPrice || count($arPrice) <= 0) {
        if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($productID, $quantity, $USER->GetUserGroupArray())) {
            $quantity = $nearestQuantity;
            $arPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $USER->GetUserGroupArray(), $renewal);
        }
    }
    $myPrice = $arPrice['PRICE']['PRICE'];
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
    $arColTab = $arColTab + "'<tr><td>'" . $i . "'</td><td>'" . $myNameItem . "'</td><td  align=center>'" . $myQuantityItem . "'</td><td>'" . $myPrice . "'</td><td>'" . $myValueItem . "'</td></tr><br>'";

}
$allProdSum = $allProdSum+$ItogSuma;

//Лестница
$ItogStairSuma = 0;
$arStairSets = CCatalogProductSet::getAllSetsByProduct($intStairsID, CCatalogProductSet::TYPE_SET); // массив комплектов данного товара
$arStairSet = array_shift($arStairSets); // комплект данного товара
$j=0; 
$arStairColTab = '';
foreach ($arStairSet['ITEMS'] as $myStairItems  => $myOllStairItems){
	$strID = $myOllStairItems['ITEM_ID'];
	$myQuantityStairItem = $myOllStairItems['QUANTITY'];
//товары
	$dbStairRes  = CCatalogProduct::GetList(array(),array("ID" => $strID ),false,array());
	while (($arStairRes = $dbStairRes->Fetch()))
	{ $myNameStairItem = $arStairRes['ELEMENT_NAME']; $j=$j+1; }


//цены
$arStairPrice = CCatalogProduct::GetOptimalPrice($strID, 1, $USER->GetUserGroupArray(), 'N');
	//if($USER->IsAdmin()) {echo '<pre>TEST: '; print_r($productID); print_r($quantity); echo '</pre>'; die();};
	if (!$arStairPrice || count($arStairPrice) <= 0)
	{
		if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($productID, $quantity, $USER->GetUserGroupArray()))
		{
			$quantity = $nearestQuantity;
			$arStairPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $USER->GetUserGroupArray(), $renewal);
		}
	}
$myStairPrice = $arStairPrice['PRICE']['PRICE'];
$myStairValueItem = $myStairPrice*$myQuantityStairItem;
$ItogStairSuma = $ItogStairSuma + $myStairValueItem;

$arStairColNum[] = $j; $arMyNameStairItem[] = $myNameStairItem; $arMyQuantityStairItem[] = $myQuantityItem; $arMyStairPrice[] = $myStairPrice; $arMyStairValueItem[] = $myStairValueItem;
$arStairColTab = $arStairColTab + '<tr><td>'.$j.'</td><td>'.$myNameStairItem.'</td><td  align="center">'.$myQuantityItem.'</td><td>'.$myStairPrice.'</td><td>'.$myStairValueItem.'</td></tr><br>';

}
$allProdSum = $allProdSum+$ItogStairSuma;

//Фильтровальная уставнока
$ItogFilterSuma = 0;
$arFilterColTab = '';
//$arFilterSets = CCatalogProductSet::getAllSetsByProduct($intStairsID, CCatalogProductSet::TYPE_SET); // массив комплектов данного товара

/*if ($myBasinAreaForTile < 24){
		$arFilter = Array("IBLOCK_ID"=>1, "ID"=>array(12128,12461,12867,12455,13327));
	}
	elseif($myBasinAreaForTile < 41){
		$arFilter = Array("IBLOCK_ID"=>1, "ID"=>array(12151,12461,12867,12455,13328));
	} 
$arFilterSets = CIBlockElement::GetList(Array(), $arFilter, false, false, array());
$arFilterSet = array_shift($arFilterSets); // комплект данного товара*/
/*if ($myBasinAreaForTile < 24){$ID = 13529;}elseif($myBasinAreaForTile < 41){$ID = 13329;} */
/*$arFilterSets = CCatalogProductSet::getAllSetsByProduct($ID, CCatalogProductSet::TYPE_SET); // массив комплектов данного товара*/
if ($myBasinAreaForTile < 24){
$arFilterSets = CCatalogProductSet::getAllSetsByProduct('13529', CCatalogProductSet::TYPE_SET); // массив комплектов данного товара
}else{
$arFilterSets = CCatalogProductSet::getAllSetsByProduct('13530', CCatalogProductSet::TYPE_SET); // массив комплектов данного товара)
}
$arFilterSet = array_shift($arFilterSets); // комплект данного товара
$k=0; 

foreach ($arFilterSet['ITEMS'] as $myFilterItems  => $myOllFilterItems){
	$strID = $myOllFilterItems['ITEM_ID'];
	$myQuantityFilterItem = $myOllFilterItems['QUANTITY'];
//товары
	$dbFilterRes  = CCatalogProduct::GetList(array(),array("ID" => $strID ),false,array());
	while (($arFilterRes = $dbFilterRes->Fetch()))
	{ $myNameFilterItem = $arFilterRes['ELEMENT_NAME']; $k=$k+1; }


//цены
$arFilterPrice = CCatalogProduct::GetOptimalPrice($strID, 1, $USER->GetUserGroupArray(), 'N');
	if (!$arFilterPrice || count($arFilterPrice) <= 0)
	{
		if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($productID, $quantity, $USER->GetUserGroupArray()))
		{
			$quantity = $nearestQuantity;
			$arFilterPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $USER->GetUserGroupArray(), $renewal);
		}
	}
$myFilterPrice = $arFilterPrice['PRICE']['PRICE'];


$myFilterValueItem = $myFilterPrice*$myQuantityFilterItem;
$ItogFilterSuma = $ItogFilterSuma + $myFilterValueItem;

$arFilterColNum[] = $k; $arMyNameFilterItem[] = $myNameFilterItem; $arMyQuantityFilterItem[] = $myQuantityFilterItem; $arMyFilterPrice[] = $myFilterPrice; $arMyFilterValueItem[] = $myFilterValueItem;
$arFilterColTab = $arFilterColTab + '<tr><td>'.$k.'</td><td>'.$myNameFilterItem.'</td><td  align="center">'.$myQuantityFilterItem.'</td><td>'.$myFilterPrice.'</td><td>'.$myFilterValueItem.'</td></tr><br>';

}

$allProdSum = $allProdSum+$ItogFilterSuma;

//Сервисной набор
$ItogServiceSuma = 0;
$arServiceColTab = '';

$arServiceSets = CCatalogProductSet::getAllSetsByProduct($intServiceID, CCatalogProductSet::TYPE_SET); // массив комплектов данного товара

$arServiceSet = array_shift($arServiceSets); // комплект данного товара
$s=0; 

foreach ($arServiceSet['ITEMS'] as $myServiceItems  => $myOllServiceItems){
	$strID = $myOllServiceItems['ITEM_ID'];
	$myQuantityServiceItem = $myOllServiceItems['QUANTITY'];
//товары
	$dbServiceRes  = CCatalogProduct::GetList(array(),array("ID" => $strID ),false,array());
	while (($arServiceRes = $dbServiceRes->Fetch()))
	{ $myNameServiceItem = $arServiceRes['ELEMENT_NAME']; $s=$s+1; }


//цены
$arServicePrice = CCatalogProduct::GetOptimalPrice($strID, 1, $USER->GetUserGroupArray(), 'N');
	if (!$arServicePrice || count($arServicePrice) <= 0)
	{
		if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($productID, $quantity, $USER->GetUserGroupArray()))
		{
			$quantity = $nearestQuantity;
			$arFilterPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $USER->GetUserGroupArray(), $renewal);
		}
	}
$myServicePrice = $arServicePrice['PRICE']['PRICE'];

$myServiceValueItem = $myServicePrice*$myQuantityServiceItem;
$ItogServiceSuma = $ItogServiceSuma + $myServiceValueItem;

$arServiceColNum[] = $s; $arMyNameServiceItem[] = $myNameServiceItem; $arMyQuantityServiceItem[] = $myQuantityServiceItem; $arMyServicePrice[] = $myServicePrice; $arMyServiceValueItem[] = $myServiceValueItem;
$arServiceColTab = $arServiceColTab + '<tr><td>'.$s.'</td><td>'.$myNameServiceItem.'</td><td  align="center">'.$myQuantityServiceItem.'</td><td>'.$myServicePrice.'</td><td>'.$myServiceValueItem.'</td></tr><br>';

}

$allProdSum = $allProdSum+$ItogServiceSuma;

//Закладные
$ItogMortgagesSuma = 0;

$arMortgagesColTab = '';
$arMortgagesSets = CCatalogProductSet::getAllSetsByProduct($intMortgagesID, CCatalogProductSet::TYPE_SET); // массив комплектов данного товара
//$arMortgagesColTab = '<tr>';
$arMortgagesSet = array_shift($arMortgagesSets); // комплект данного товара
$m=0; 

foreach ($arMortgagesSet['ITEMS'] as $myMortgagesItems  => $myOllMortgagesItems){
	$strID = $myOllMortgagesItems['ITEM_ID'];
	$myQuantityMortgagesItem = $myOllMortgagesItems['QUANTITY'];
//товары
	$dbMortgagesRes  = CCatalogProduct::GetList(array(),array("ID" => $strID ),false,array());
	while (($arMortgagesRes = $dbMortgagesRes->Fetch()))
	{ $myNameMortgagesItem = $arMortgagesRes['ELEMENT_NAME']; $m=$m+1; }


//цены
$arMortgagesPrice = CCatalogProduct::GetOptimalPrice($strID, 1, $USER->GetUserGroupArray(), 'N');
	if (!$arMortgagesPrice || count($arMortgagesPrice) <= 0)
	{
		if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($productID, $quantity, $USER->GetUserGroupArray()))
		{
			$quantity = $nearestQuantity;
			$arMortgagesPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $USER->GetUserGroupArray(), $renewal);
		}
	}
$myMortgagesPrice = $arMortgagesPrice['PRICE']['PRICE'];

$myMortgagesValueItem = $myMortgagesPrice*$myQuantityMortgagesItem;
$ItogMortgagesSuma = $ItogMortgagesSuma + $myMortgagesValueItem;

$arMortgagesColNum[] = $m; $arMyNameMortgagesItem[] = $myNameMortgagesItem; $arMyQuantityMortgagesItem[] = $myQuantityMortgagesItem; $arMyMortgagesPrice[] = $myMortgagesPrice; $arMyMortgagesValueItem[] = $myMortgagesValueItem;
$arMortgagesColTab = $arMortgagesColTab + '<tr><td>'.$m.'</td><td>'.$myNameMortgagesItem.'</td><td  align="center">'.$myQuantityMortgagesItem.'</td><td>'.$myMortgagesPrice.'</td><td>'.$myMortgagesValueItem.'</td></tr><br>';
}

$allProdSum = $allProdSum+$ItogMortgagesSuma;

//Подогрев
if ($intHeatingID!='00000'){
$ItogHeatingSuma = 0;
$ItogHeatingSuma = '';
$arHeatingSets = CCatalogProductSet::getAllSetsByProduct($intHeatingID, CCatalogProductSet::TYPE_SET); // массив комплектов данного товара

$arHeatingSet = array_shift($arHeatingSets); // комплект данного товара
$h=0; 

foreach ($arHeatingSet['ITEMS'] as $myHeatingItems  => $myOllHeatingItems){
	$strID = $myOllHeatingItems['ITEM_ID'];
	$myQuantityHeatingItem = $myOllHeatingItems['QUANTITY'];
//товары
	$dbHeatingRes  = CCatalogProduct::GetList(array(),array("ID" => $strID ),false,array());
	while (($arHeatingRes = $dbHeatingRes->Fetch()))
	{ $myNameHeatingItem = $arHeatingRes['ELEMENT_NAME']; $h=$h+1; }


//цены
$arHeatingPrice = CCatalogProduct::GetOptimalPrice($strID, 1, $USER->GetUserGroupArray(), 'N');
	if (!$arHeatingPrice || count($arHeatingPrice) <= 0)
	{
		if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($productID, $quantity, $USER->GetUserGroupArray()))
		{
			$quantity = $nearestQuantity;
			$arHeatingPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $USER->GetUserGroupArray(), $renewal);
		}
	}
$myHeatingPrice = $arHeatingPrice['PRICE']['PRICE'];

$myHeatingValueItem = $myHeatingPrice*$myQuantityHeatingItem;
$ItogHeatingSuma = $ItogHeatingSuma + $myHeatingValueItem;

$arHeatingColNum[] = $h; $arMyNameHeatingItem[] = $myNameHeatingItem; $arMyQuantityHeatingItem[] = $myQuantityHeatingItem; $arMyHeatingPrice[] = $myHeatingPrice; $arMyHeatingValueItem[] = $myHeatingValueItem;
$arHeatingColTab = $arHeatingColTab + '<tr><td>'.$h.'</td><td>'.$myNameHeatingItem.'</td><td  align="center">'.$myQuantityHeatingItem.'</td><td>'.$myHeatingPrice.'</td><td>'.$myHeatingValueItem.'</td></tr><br>';

}

$allProdSum = $allProdSum+$ItogHeatingSuma;
}
else{
	$arHeatingColTab = '<tr><td>1</td><td>отсутствует</td><td></td><td></td><td></td></tr><br>';
$arHeatingColNum[] = 1; $arMyNameHeatingItem[] = 'отсутствует'; $arMyQuantityHeatingItem[] = ''; $arMyHeatingPrice[] = ''; $arMyHeatingValueItem[] = '';
	
	
}
?>
<!--img src='{$usrLogoSCR}' style='height: 62px;' alt=''-->
<?
$GLOBALS['APPLICATION']->RestartBuffer();
ob_end_clean();
require_once $_SERVER['DOCUMENT_ROOT']."/tcpdf/tcpdf.php";

$html_text = <<<EOD
<table border="0" cellpadding="1" cellspacing="1" style="width: 75%; margin: 15px;">
    <tr>
        <td colspan="3"><img src="{$usrNoLogoSCR}" alt=""></td>
    </tr>
    <tr>
        <td colspan="3" align="right"><h3>Коммерческое предложение № {$myFormID}</h3></td>
    </tr>
    <tr>
        <td colspan="3"><b>Параметры Вашего бассейна:</b></td>
    </tr>
    <tr>
        <td>длина, м</td>
        <td>{$myLong}</td>
        <td></td>
    </tr>
    <tr>
        <td>ширина, м</td>
        <td>{$myWidth}</td>
        <td></td>
    </tr>
    <tr>
        <td>глубина, м</td>
        <td>{$myDepth}</td>
        <td></td>
    </tr>
</table>
EOD;

$html_text .= <<<EOD
<table border="0" cellpadding="2" cellspacing="1" style="margin: 15px; width: 100%">
    <tr>
        <td width="5%"><b>№</b></td>
        <td width="50%"><b>Наименование материалов</b></td>
        <td align="center"><b>Количество</b></td>
        <td><b>Цена</b></td>
        <td><b>Сумма</b></td>
    </tr>
    <tr>
		<td></td>
        <td colspan="4"><b>{$myTypeOfPool}</b></td>
    </tr>
    <tr>
        <td>{$arColNum[0]}</td>
        <td>{$arMyNameItem[0]}</td>
        <td align="center">{$arMyQuantityItem[0]}</td>
        <td>{$arMyPrice[0]}</td>
        <td>{$arMyValueItem[0]}</td>
    </tr>
    <tr>
        <td>{$arColNum[1]}</td>
        <td>{$arMyNameItem[1]}</td>
        <td align="center">{$arMyQuantityItem[1]}</td>
        <td>{$arMyPrice[1]}</td>
        <td>{$arMyValueItem[1]}</td>
    </tr>
    <tr>
        <td>{$arColNum[2]}</td>
        <td>{$arMyNameItem[2]}</td>
        <td align="center">{$arMyQuantityItem[2]}</td>
        <td>{$arMyPrice[2]}</td>
        <td>{$arMyValueItem[2]}</td>
    </tr>
    <tr>
        <td>{$arColNum[3]}</td>
        <td>{$arMyNameItem[3]}</td>
        <td align="center">{$arMyQuantityItem[3]}</td>
        <td>{$arMyPrice[3]}</td>
        <td>{$arMyValueItem[3]}</td>
    </tr>
    <tr>
        <td>{$arColNum[4]}</td>
        <td>{$arMyNameItem[4]}</td>
        <td align="center">{$arMyQuantityItem[4]}</td>
        <td>{$arMyPrice[4]}</td>
        <td>{$arMyValueItem[4]}</td>
    </tr>
	
	
    <tr>
		<td></td>
        <td colspan="4"><b>{$arMyNameStairItem[0]}</b></td>
    </tr>
    <tr>
        <td>{$arStairColNum[0]}</td>
        <td>{$arMyNameStairItem[0]}</td>
        <td align="center">{$arMyQuantityStairItem[0]}</td>
        <td>{$arMyStairPrice[0]}</td>
        <td>{$arMyStairValueItem[0]}</td>
    </tr>
    <tr>
        <td>{$arStairColNum[1]}</td>
        <td>{$arMyNameStairItem[1]}</td>
        <td align="center">{$arMyQuantityStairItem[1]}</td>
        <td>{$arMyStairPrice[1]}</td>
        <td>{$arMyStairValueItem[1]}</td>
    </tr>
	
	
    <tr>
		<td></td>
        <td colspan="4"><b>Фильтровальная уставнока</b></td>
    </tr>
    <tr>
        <td>{$arFilterColNum[0]}</td>
        <td>{$arMyNameFilterItem[0]}</td>
        <td align="center">{$arMyQuantityFilterItem[0]}</td>
        <td>{$arMyFilterPrice[0]}</td>
        <td>{$arMyFilterValueItem[0]}</td>
    </tr>
    <tr>
        <td>{$arFilterColNum[1]}</td>
        <td>{$arMyNameFilterItem[1]}</td>
        <td align="center">{$arMyQuantityFilterItem[1]}</td>
        <td>{$arMyFilterPrice[1]}</td>
        <td>{$arMyFilterValueItem[1]}</td>
    </tr>
    <tr>
        <td>{$arFilterColNum[2]}</td>
        <td>{$arMyNameFilterItem[2]}</td>
        <td align="center">{$arMyQuantityFilterItem[2]}</td>
        <td>{$arMyFilterPrice[2]}</td>
        <td>{$arMyFilterValueItem[2]}</td>
    </tr>
    <tr>
        <td>{$arFilterColNum[3]}</td>
        <td>{$arMyNameFilterItem[3]}</td>
        <td align="center">{$arMyQuantityFilterItem[3]}</td>
        <td>{$arMyFilterPrice[3]}</td>
        <td>{$arMyFilterValueItem[3]}</td>
    </tr>
    <tr>
        <td>{$arFilterColNum[4]}</td>
        <td>{$arMyNameFilterItem[4]}</td>
        <td align="center">{$arMyQuantityFilterItem[4]}</td>
        <td>{$arMyFilterPrice[4]}</td>
        <td>{$arMyFilterValueItem[4]}</td>
    </tr>
	
	<tr>
		<td></td>
        <td colspan="4"><b>Сервисный набор</b></td>
    </tr>
    <tr>
        <td>{$arServiceColNum[0]}</td>
        <td>{$arMyNameServiceItem[0]}</td>
        <td align="center">{$arMyQuantityServiceItem[0]}</td>
        <td>{$arMyServicePrice[0]}</td>
        <td>{$arMyServiceValueItem[0]}</td>
    </tr>
    
	
	<tr>
		<td></td>
        <td colspan="4"><b>Закладные</b></td>
    </tr>
    <tr>
        <td>{$arMortgagesColNum[0]}</td>
        <td>{$arMyNameMortgagesItem[0]}</td>
        <td align="center">{$arMyQuantityMortgagesItem[0]}</td>
        <td>{$arMyMortgagesPrice[0]}</td>
        <td>{$arMyMortgagesValueItem[0]}</td>
    </tr>
    
	
	<tr>
		<td></td>
        <td colspan="4"><b>Подогрев</b></td>
    </tr>
    <tr>
        <td>{$arHeatingColNum[0]}</td>
        <td>{$arMyNameHeatingItem[0]}</td>
        <td align="center">{$arMyQuantityHeatingItem[0]}</td>
        <td>{$arMyHeatingPrice[0]}</td>
        <td>{$arMyHeatingValueItem[0]}</td>
    </tr>
    
	<tr>
        <td></td>
        <td></td>
        <td><b>Итого:</b></td>
        <td colspan="2" align="center"><b>{$allProdSum}</b></td>
    </tr>
</table>
EOD;
/**/

/*
$html_text = "<table border='0' cellpadding='1' cellspacing='1' style='width: 75%; margin: 15px;'>
    <tr>
        <td colspan='3'><img src='$usrNoLogoSCR' alt=''></td>
    </tr>
    <tr>
        <td colspan='3' align='right'><h3>Коммерческое предложение № $myFormID</h3></td>
    </tr>
    <tr>
        <td colspan='3'><b>Параметры Вашего бассейна:</b></td>
    </tr>
    <tr>
        <td>длина, м</td>
        <td>$myLong</td>
        <td></td>
    </tr>
    <tr>
        <td>ширина, м</td>
        <td>$myWidth</td>
        <td></td>
    </tr>
    <tr>
        <td>глубина, м</td>
        <td>$myDepth</td>
        <td></td>
    </tr>
</table>";
/**/

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setPrintHeader(false); 
$pdf->setPrintFooter(false);
$pdf->SetMargins(20, 25, 25);
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 9);
$pdf->writeHTMLCell(0, 0, '', '', $html_text, 0, 1, 0, true, '', true);
$pdf->Output('doc.pdf', 'I');
die();

//print_r($html_text);
?>