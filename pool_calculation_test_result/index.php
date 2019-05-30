<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
CModule::IncludeModule("form");
CModule::IncludeModule('iblock');
\Bitrix\Main\Loader::includeModule('catalog');
?>

<?
$myBasinAreaForTile = 20;


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
							$ID = 13329;
							$arIBlockElement = GetIBlockElement($ID, '');
							if ($myBasinAreaForTile < 24){
								//$arFilter = Array("IBLOCK_ID"=>8, "ID"=>13329);
								$ID = 13329;
							}
							elseif($myBasinAreaForTile < 41){
								//$arFilter = Array("IBLOCK_ID"=>8, "ID"=>13330);
								$ID = 13330;
							} 
							
							$resACCESSORIES = CIBlockElement::GetProperty($arIBlockElement['IBLOCK_ID'], $ID, array("sort" => "asc"), Array("CODE"=>"composition_catalog"));
										$i = 1;
											/*while ($obACCESSORIES = $resACCESSORIES->GetNext()) {
												$tegs[] = $obACCESSORIES['VALUE'];
												$resEl = CIBlockElement::GetByID($obACCESSORIES['VALUE']);
													if($ar_resEl = $resEl->GetNext())
														// $val - переменная где Вы указали ID элемента инфоблока 
														$resElement = CIBlockElement::GetByID($ar_resEl['ID']); 
														if($ar_resElement = $resElement->GetNext())
														//echo $ar_resElement['DETAIL_PAGE_URL']; <a href="/sredstva_dlya_ukhoda_za_vodoy/">
														echo $i.'. ';	echo '<a href="'.$ar_resElement['DETAIL_PAGE_URL'].'">'.$ar_resEl['NAME']; $i++;
													//if($USER->IsAdmin()) {echo '<pre>'; print_r($ar_resElement['DETAIL_PAGE_URL']); echo '</pre>';}
									?></br><?	}*/
while ($obACCESSORIES = $resACCESSORIES->GetNext()) {
	//$strID = $myOllFilterItems['ITEM_ID'];
							//}									
							echo '<pre>'; print_r($obACCESSORIES['VALUE']); echo '</pre>';}
							$arFilter = Array("IBLOCK_ID"=>"8", "VALUE"=>"13329");
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
							}

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

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>