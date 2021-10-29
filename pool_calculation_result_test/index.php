<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
CModule::IncludeModule("form");

\Bitrix\Main\Loader::includeModule('catalog');
$APPLICATION->SetTitle("Результат");
$GLOBALS['APPLICATION']->RestartBuffer();
include 'include.php';
?><!--h2>РЕЗУЛЬТАТ</h2-->
<table cellspacing="0" style="border-collapse: collapse; max-width:450px">
	<tbody style="background: #ffffff;">
<?
//$RESULT_ID = 14;
$myFormId = GetFormId($WEB_FORM_ID);
$myResultId = GetResultId($RESULT_ID);

$ItogSuma = 0;
//получим логотип (если файл есть выводим файл иначе берем логотип alexpools)
$IsImage = "N";		
$arAnswer = CFormResult::GetDataByID(
	$RESULT_ID, 
	array("new_field_0005"), 
	$arResult, 
	$arAnswer2);
// <img src="http://alexpools.ru/upload/iblock/8c3/8c3b07114590d31aa1ea9b244200cb08.jpg" style="max-width: 150px;"> ANSWER_VALUE - 33209
// <img src="http://alexpools.ru/upload/iblock/471/4717c1ebc459b9cc53a5c58fa3fb0dca.jpg" style="max-width: 150px;"> ANSWER_VALUE - 33768
foreach ($arAnswer as $myResults  => $arQuestions)
{
	foreach ($arQuestions as $myResult  => $arQuestion)
	{	
		$IsImage = "Y";
		$IdImage = $arQuestion["USER_FILE_ID"];
		//echo "<pre>"; print_r($arQuestion); echo "</pre>";
	}
}
$usrNoLogoSCR = '/include/logo.png';
if($IsImage == "Y"):
	$usrLogoSCR=CFile::GetPath($IdImage);
else:
	$usrLogoSCR = $usrNoLogoSCR; $usrNoLogoheight = 'style="height: 62px;"';
endif;
//echo CFile::GetPath($IdImage);
//echo "<pre>"; print_r($IdImage); echo "</pre>";
?>

		<tr>
			<img src="<?=$usrLogoSCR?>" <?=$usrNoLogoheight?> alt="">
		</tr>

		<tr>
			<h3>Коммерческое предложение № <?=$RESULT_ID;?></h3>
		</tr>


		<?
		//$RESULT_ID = $_REQUEST["RESULT_ID"];
		//$FORM_ID = 2;
		//$RESULT_ID = 12; // ID результата

		// получим данные по размерам бассейна
		$arAnswer = CFormResult::GetDataByID(
			$RESULT_ID, 
			array("new_field_0002"), 
			$arResult, 
			$arAnswer2);

				foreach ($arAnswer as $myResults  => $arQuestions)
				{
					foreach ($arQuestions as $myResult  => $arQuestion)
					{	
						if ($arQuestion['ANSWER_ID'] == 1){ //длинна
							$myLong = $arQuestion['USER_TEXT'];
						}
								
						elseif ($arQuestion['ANSWER_ID'] == 2){ //ширина
							$myWidth = $arQuestion['USER_TEXT'];
						}
						else{ //глубина
							$myDepth = $arQuestion['USER_TEXT'];
						}
					}
				}
				//Рассчитаем зависимые данные:
				//Площадь Зеркала Воды
				//$myAreaMirrorsOfWater =$myLong * $myWidth;
				$myAreaMirrorsOfWater = GetAreaMirrorsOfWater($myLong, $myWidth);

				//Площадь Бассейна
				//$myAreaOfTheBasin =($myLong + $myWidth)*2*$myDepth + $myAreaMirrorsOfWater;
				$myAreaOfTheBasin = GetAreaOfTheBasin($myLong, $myWidth, $myDepth);

				//Площадь Бассейна Для Пленки 
				//$BasinAreaForFilms =$myAreaOfTheBasin*1.2;
				
				//$myBasinAreaForFilms = ceil($BasinAreaForFilms);
				$myBasinAreaForFilms = GetBasinAreaForFilms($myLong, $myWidth, $myDepth);
				
				//Площадь Бассейна Для Плитки
				//$myBasinAreaForTile =$myAreaOfTheBasin*1.1;
				$myBasinAreaForTile = GetBasinAreaForTile($myLong, $myWidth, $myDepth);

				//Периметр Бассейна
				//$myPerimeterOfTheBasin =($myLong + $myWidth)*2;
				$myPerimeterOfTheBasin = GetPerimeterOfTheBasin($myLong, $myWidth, $myDepth);



		?>

		<tr>
			<tr>
				<td colspan="5" style="font-style: italic; font-weight: bold;">
					 Параметры Вашего бассейна:
				</td>
				<td>
				</td>
				<td>
				</td>
				
			</tr>
			<tr>
				<td   style="font-style: italic; font-weight: bold;">
				 длинна, м
				</td>
				<td>
				</td>
				<td   style="font-style: italic;">
					<?=$myLong;?> 
				</td>
				
			</tr>
			<tr>
				<td  style="font-style: italic; font-weight: bold;">
				 ширина, м
				</td>
				<td>
				</td>
				<td   style="font-style: italic;">
					<?=$myWidth;?> 
				</td>
				
			</tr>
			<tr>
				<td  style="font-style: italic; font-weight: bold;">
				 глубина, м
				</td>
				<td>
				</td>
				<td  style="font-style: italic;">
					<?=$myDepth;?> 
				</td>
				
			</tr>

		</tr>
	</tbody>
</table>
<table style="border-collapse: collapse;max-width:650px;width: 100%; margin: 15px;">
	<tbody style="background: #ffffff;">
		<!--tr>
			<td colspan="5" style="font-style: italic; font-weight: bold; height: 20px;">
			</td>
		</tr-->

		<?//таблица товаров?>
		<tr><td  style="font-weight: bold; width: 5%;">
				 
			</td>
			<td  style="font-weight: bold;width: 5%;">
				 №
			</td>
			<td  style="font-weight: bold; width: 50%;">
				 Наименование&nbsp;материалов
			</td>
			<td   style="font-weight: bold; width: 15%;">
				 Количество
			</td>
			<td  style="font-weight: bold; width: 10%;">
				 Цена
			</td>
			<td   style="font-weight: bold; width: 20%;">
				 Сумма
			</td>
		</tr>

		<?

		//тип бассейна
		$arAnswer = CFormResult::GetDataByID(
			$RESULT_ID, 
			array("new_field_0001"), 
			$arResult, 
			$arAnswer2);

				foreach ($arAnswer as $myResults  => $arQuestions)
				{
					foreach ($arQuestions as $myResult  => $arQuestion)
					{	
						$myTypeOfPool = $arQuestion['ANSWER_TEXT'];
					}
				}

		?>
		<tr>
			<!--td style="font-weight: bold;">
				 
			</td-->
			<td  colspan="5"   style="font-weight: bold;">
				<?=$myTypeOfPool;?>
			</td>
			<!--td  style="font-weight: bold;">
				 
			</td>
			<td style="font-weight: bold;">
				 
			</td>
			<td  style="font-weight: bold;">
				 
			</td-->
		</tr>


		<?

		//Пленка ПВХ
		$arAnswer = CFormResult::GetDataByID(
			$RESULT_ID, 
			array("new_field_0003"), 
			$arResult, 
			$arAnswer2);

				foreach ($arAnswer as $myResults  => $arQuestions)
				{
					foreach ($arQuestions as $myResult  => $arQuestion)
					{	
						$myColorOfTheFilm = $arQuestion['ANSWER_VALUE'];
//echo '<pre>'; print_r($myColorOfTheFilm); echo '</pre>';
							$arSets = CCatalogProductSet::getAllSetsByProduct($myColorOfTheFilm, CCatalogProductSet::TYPE_SET); // массив комплектов данного товара
							$arSet = array_shift($arSets); // комплект данного товара
							$i=0;

							foreach ($arSet['ITEMS'] as $myItems  => $myOllItems){

							$ID = $myOllItems['ITEM_ID'];
							$rest = substr($myOllItems['QUANTITY'], -5);

							$myQuantityItem = ($myOllItems['QUANTITY']-$rest)/100000;
							//товары
							$db_res  = CCatalogProduct::GetList(
									array(),
									array("ID" => $ID ),
									false,
									array()
								);

								while (($ar_res = $db_res->Fetch()))
								{
								$myNameItem = $ar_res['ELEMENT_NAME'];
								$i=$i+1;

								}

							//цены
							$arPrice = CCatalogProduct::GetOptimalPrice($ID, 1, $USER->GetUserGroupArray(), 'N');
								if (!$arPrice || count($arPrice) <= 0)
								{
									if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($productID, $quantity, $USER->GetUserGroupArray()))
									{
										$quantity = $nearestQuantity;
										$arPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $USER->GetUserGroupArray(), $renewal);
									}
								}

							$myPrice = $arPrice['PRICE']['PRICE'];
							if($rest == 99999){

								$myValueItem = $myPrice*$myBasinAreaForFilms;
								$myQuantityItem = $myBasinAreaForFilms;
							}
							elseif($rest == 88888){
								$drob = ($myPerimeterOfTheBasin / $myQuantityItem) - intval($myPerimeterOfTheBasin/$myQuantityItem);
								if ($drob > 0){
								$myPerimeterOfTwoMeters =intval($myPerimeterOfTheBasin/$myQuantityItem) + 1;
								}
								else{
								$myPerimeterOfTwoMeters =intval($myPerimeterOfTheBasin/$myQuantityItem);
								}
								$myQuantityItem = $myPerimeterOfTwoMeters;
								$myValueItem = $myPrice*$myPerimeterOfTwoMeters;
							}
							elseif($rest == 77777){
								$PerimeterOfTheBasin = ceil($myPerimeterOfTheBasin);
								
								$myQuantityItem = $myQuantityItem*$PerimeterOfTheBasin;
								$myValueItem = $myPrice*$myQuantityItem;
							}
							elseif($rest == 55555){
								$AreaMirrorsOfWater = ceil($myAreaMirrorsOfWater);
								
								$myQuantityItem = $myQuantityItem*$AreaMirrorsOfWater;
								$myValueItem = $myPrice*$myQuantityItem;
							}
							else{
								//$myQuantityItem = $myPerimeterOfTwoMeters;
								$myValueItem = $myPrice*$myQuantityItem;
							}
							$ItogSuma = $ItogSuma + $myValueItem;

		?>

		<tr>
			<td style="width: 5%;">
				 <?=$i;?>
			</td>
			<td style=" width: 50%;">
				 <?=$myNameItem;?>
			</td>
			<td style=" width: 15%;">
				 <?=$myQuantityItem;?>
			</td>
			<td style=" width: 10%;">
				 <?=$myPrice;?>
			</td>
			<td style="width: 20%;">
				 <?=$myValueItem;?>
			</td>
		</tr>
		
		<?
		
							}
		
					}
				}
		?>



		<?

		//Лестница
		$arAnswer = CFormResult::GetDataByID(
			$RESULT_ID, 
			array("new_field_0004"), 
			$arResult, 
			$arAnswer2);

				foreach ($arAnswer as $myResults  => $arQuestions)
				{
					foreach ($arQuestions as $myResult  => $arQuestion)
					{//echo "<pre>"; print_r($arQuestion); echo "</pre>";

						$myColorOfTheFilm = $arQuestion['ANSWER_VALUE'];

							$db_item  = CCatalogProduct::GetList(
										array(),
										array("ID" => $myColorOfTheFilm ),
										false,
										array()
									);

									while (($ar_item = $db_item->Fetch()))
									{
										$myTypeOfPool = $ar_item['ELEMENT_NAME'];

									
									}

?>

		<tr>
			<!--td style="font-weight: bold;">
				 
			</td-->
			<td colspan="5"   style="font-weight: bold;">
				<?=$myTypeOfPool;?>
			</td>
			<!--td  style="font-weight: bold;">
				 
			</td>
			<td style="font-weight: bold;">
				 
			</td>
			<td  style="font-weight: bold;">
				 
			</td-->
		</tr>
<?
							$arSets = CCatalogProductSet::getAllSetsByProduct($myColorOfTheFilm, CCatalogProductSet::TYPE_SET); // массив комплектов данного товара
							$arSet = array_shift($arSets); // комплект данного товара
							$i=0;

							foreach ($arSet['ITEMS'] as $myItems  => $myOllItems){
							
								$ID = $myOllItems['ITEM_ID'];
								$myQuantityItem = $myOllItems['QUANTITY'];
								//товары
								$db_res  = CCatalogProduct::GetList(
										array(),
										array("ID" => $ID ),
										false,
										array()
									);

									while (($ar_res = $db_res->Fetch()))
									{
										$myNameItem = $ar_res['ELEMENT_NAME'];
										$i=$i+1;
									
									}

								//цены
								$arPrice = CCatalogProduct::GetOptimalPrice($ID, 1, $USER->GetUserGroupArray(), 'N');
									if (!$arPrice || count($arPrice) <= 0)
									{
										if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($productID, $quantity, $USER->GetUserGroupArray()))
										{
											$quantity = $nearestQuantity;
											$arPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $USER->GetUserGroupArray(), $renewal);
										}
									}
								//echo "<pre>"; print_r($arPrice['PRICE']['PRICE'] ); echo "</pre>";
								$myPrice = $arPrice['PRICE']['PRICE'];
								$myValueItem = $myPrice*$myQuantityItem;
								$ItogSuma = $ItogSuma + $myValueItem;

		?>

		<tr>
			<td style=" width: 5%;">
				 <?=$i;?>
			</td>
			<td style=" width: 50%;">
				 <?=$myNameItem;?>
			</td>
			<td style=" width: 15%;">
				 <?=$myQuantityItem;?>
			</td>
			<td style=" width: 10%;">
				 <?=$myPrice;?>
			</td>
			<td style="width: 20%;">
				<?=$myValueItem;?>
			</td>
		</tr>
		<?
		
							}
		
					}
				}
		?>
		
		
		<?

		//Фильтровальная уставнока
		$arAnswer = CFormResult::GetDataByID(
			$RESULT_ID, 
			array("new_field_0006"), 
			$arResult, 
			$arAnswer2);

				foreach ($arAnswer as $myResults  => $arQuestions)
				{
					foreach ($arQuestions as $myResult  => $arQuestion)
					{//echo "<pre>"; print_r($arQuestion); echo "</pre>";

						$myColorOfTheFilm = $arQuestion['ANSWER_VALUE'];

							$db_item  = CCatalogProduct::GetList(
										array(),
										array("ID" => $myColorOfTheFilm ),
										false,
										array()
									);

									while (($ar_item = $db_item->Fetch()))
									{
										$myTypeOfPool = $ar_item['ELEMENT_NAME'];

									
									}

?>

		<tr>
			<!--td style="font-weight: bold;">
				 
			</td-->
			<td colspan="5"   style="font-weight: bold;">
				Фильтровальная уставнока
			</td>
			<!--td  style="font-weight: bold;">
				 
			</td>
			<td style="font-weight: bold;">
				 
			</td>
			<td  style="font-weight: bold;">
				 
			</td-->
		</tr>
<?
							/*$arSets = CCatalogProductSet::getAllSetsByProduct($myColorOfTheFilm, CCatalogProductSet::TYPE_SET); // массив комплектов данного товара
							$arSet = array_shift($arSets); // комплект данного товара*/
							if ($myBasinAreaForTile < 24){
								$arFilter = Array("IBLOCK_ID"=>1, "ID"=>array(12128,12461,12867,12455,13327));
							}
							elseif($myBasinAreaForTile < 41){
								$arFilter = Array("IBLOCK_ID"=>1, "ID"=>array(12151,12461,12867,12455,13328));
							} 
							
							
							$arFilter = Array("IBLOCK_ID"=>1, "ID"=>array(12151,12461,12867,13327));
							$arSet = CIBlockElement::GetList(Array(), $arFilter, false, false, array());
							$i=0;

							foreach ($arSet['ITEMS'] as $myItems  => $myOllItems){
							
								$ID = $myOllItems['ITEM_ID'];
								/*$myQuantityItem = $myOllItems['QUANTITY'];*/
								if(i==0){$myQuantityItem = 1;}
								elseif(i==1){$myQuantityItem = 6;}
								elseif(i==2){if($myBasinAreaForTile < 24){$myQuantityItem = 2;}elseif($myBasinAreaForTile < 41){$myQuantityItem = 4;}}
								elseif(i==3){$myQuantityItem = 3;}
								elseif(i==4){$myQuantityItem = 1;}
								//товары
								$db_res  = CCatalogProduct::GetList(
										array(),
										array("ID" => $ID ),
										false,
										array()
									);

									while (($ar_res = $db_res->Fetch()))
									{
										$myNameItem = $ar_res['ELEMENT_NAME'];
										$i=$i+1;
									
									}

								//цены
								$arPrice = CCatalogProduct::GetOptimalPrice($ID, 1, $USER->GetUserGroupArray(), 'N');
									if (!$arPrice || count($arPrice) <= 0)
									{
										if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($productID, $quantity, $USER->GetUserGroupArray()))
										{
											$quantity = $nearestQuantity;
											$arPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $USER->GetUserGroupArray(), $renewal);
										}
									}
								//echo "<pre>"; print_r($arPrice['PRICE']['PRICE'] ); echo "</pre>";
								$myPrice = $arPrice['PRICE']['PRICE'];
								$myValueItem = $myPrice*$myQuantityItem;
								$ItogSuma = $ItogSuma + $myValueItem;

		?>

		<tr>
			<td style=" width: 5%;">
				 <?=$i;?>
			</td>
			<td style=" width: 50%;">
				 <?=$myNameItem;?>
			</td>
			<td style=" width: 15%;">
				 <?=$myQuantityItem;?>
			</td>
			<td style=" width: 10%;">
				 <?=$myPrice;?>
			</td>
			<td style="width: 20%;">
				<?=$myValueItem;?>
			</td>
		</tr>
		<?
		
							}
		
					}
				}
		?>
		
		<tr>
			<td  style="">
				 
			</td>
			<td  style="">
				 
			</td>
			<td  style="">
				 
			</td>
			<td  style="">
				<p>Итого: </p> 
			</td>
			<td  style="">
				 <?=$ItogSuma;?>
			</td>

		</tr>

	</tbody>
</table>
 <br><?
die();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>