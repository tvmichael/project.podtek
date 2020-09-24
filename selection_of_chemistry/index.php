<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
/*CJSCore::Init(array("jquery"));*/
$APPLICATION->SetAdditionalCss($APPLICATION->GetCurPage() . "style.css");
$APPLICATION->AddHeadScript($APPLICATION->GetCurPage() . 'script.js');
$APPLICATION->AddHeadScript($APPLICATION->GetCurPage() . 'script.map.js');
$APPLICATION->AddHeadScript($APPLICATION->GetCurPage() . 'script.min.js');
$arParams['IBLOCK_ID'] = 13;
$APPLICATION->SetTitle("Подбор химии для бассейна");
?><h2>Подбор химии для бассейна</h2>
    <br>
<?
$rs = CIBlockElement::GetList(
   Array('SORT' => 'ASC'),
   ['IBLOCK_ID' => '13', 'ACTIVE'=>'Y'],
   false, false,
   ['ID', 'IBLOCK_ID', 'NAME', 'CODE']
);
?>
<div class="product-item-detail-tabs-container">
	<ul class="product-item-detail-tabs-list">
		<?
		while ($ar = $rs->Fetch()) {
		   /*echo $ar['CODE'] . ' ';*/
		?>   
			<li class="product-item-detail-tab active" data-entity="tab"
				data-value= "<?= $ar['CODE'] ?>">
				<a href="javascript:void(0);" class="product-item-detail-tab-link">
					<span><?= $ar['NAME'] ?></span>
				</a>
			</li>
		<?                               
		}
		?> 
	</ul>
</div>
<?
$rs = CIBlockElement::GetList(
   Array('SORT' => 'ASC'),
   ['IBLOCK_ID' => '13', 'ACTIVE'=>'Y'],
   false, false,
   ['ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PREVIEW_TEXT']
);
?>
<div class="row" id="<?= $arParams['IBLOCK_ID'] ?>">
                        <div class="col-xs-12">
<?
while ($ar = $rs->Fetch()) {
   /*echo $ar['PREVIEW_TEXT'] . ' ';*/
?>   
	<div class="product-item-detail-tab-content" data-entity="tab-container"
                                     data-value="<?= $ar['CODE'] ?>">
		<div class="col-xs-12 col-sm-12">
		<?
		if (
				$ar['PREVIEW_TEXT'] != ''
			)
			{
				echo $ar['PREVIEW_TEXT_TYPE'] === 'html' ? $ar['PREVIEW_TEXT'] : '<p style = "font-size: 12px;">'.$ar['PREVIEW_TEXT'].'</p>';
			}
			$resGOODS = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $ar['ID'], array("sort" => "asc"), Array("CODE" => "GOODS_FOR_BOOKMARKS"));

			$i = 1;
			while ($obGOODS = $resGOODS->GetNext()) {
			$tegs[] = $obGOODS['VALUE'];
			$resEl = CIBlockElement::GetByID($obGOODS['VALUE']);
			if ($ar_resEl = $resEl->GetNext())
				// $val - переменная где Вы указали ID элемента инфоблока
				$resElement = CIBlockElement::GetByID($ar_resEl['ID']);
			if ($ar_resElement = $resElement->GetNext())
				
			//echo $ar_resElement['DETAIL_PAGE_URL']; <a href="/sredstva_dlya_ukhoda_za_vodoy/">
			/*echo '<a href="' . $ar_resElement['DETAIL_PAGE_URL'] . '"target="_blank" style="color: #256aa3;text-decoration: underline;">' . $ar_resEl['NAME'] . '</a>';*/
			$i++;
			/*if($USER->IsAdmin()) {echo '<pre>'; print_r($ar_resElement['DETAIL_PAGE_URL']); echo '</pre>';}*/
			?>
			<div class="col-xs-6 col-sm-4 col-md-2" style="padding-bottom: 30px;">
                                <div class="product-item-container">
                                    <?
									
                                    $acText = true;
									$id = $ar_resElement['ID'];
                                    $arPrice = CCatalogProduct::GetOptimalPrice($id, 1, $USER->GetUserGroupArray(), 'N');
                                    $res = CIBlockElement::GetByID($id);
                                    if($ar_res = $res->GetNext())
                                    {
                                        $background_image = CFile::GetPath($ar_res["DETAIL_PICTURE"]);
                                        if(!$background_image) $background_image = CFile::GetPath($ar_res["PREVIEW_PICTURE"]);
                                    }
                                    ?>
                                    <a class="product-item-wrapper" href="<?=$ar_res['DETAIL_PAGE_URL'];?>">
                                        <div class="product-item-image">
                                            <span style="background-image: url('<?=$background_image;?>');"></span>
                                            <? if($arPrice['RESULT_PRICE']['PERCENT']>0):?>
                                                <div class="product-item-discount">-<?=$arPrice['RESULT_PRICE']['PERCENT'];?>%</div>
                                            <? endif;?>
                                        </div>
                                        <div class="product-item-title"  style="color: black;text-align: center;">
                                            <?=$ar_res['NAME'];?>
                                        </div>
                                    </a>
                                    <div class="product-item-price">
                                        <? if($arPrice['RESULT_PRICE']['BASE_PRICE'] > $arPrice['RESULT_PRICE']['DISCOUNT_PRICE']):?>
                                            <div class="old-price"><? echo CurrencyFormat($arPrice['RESULT_PRICE']['BASE_PRICE'], $arPrice['PRICE']['CURRENCY']);?></div>
                                            <div class="new-price"><? echo CurrencyFormat($arPrice['RESULT_PRICE']['DISCOUNT_PRICE'], $arPrice['PRICE']['CURRENCY']);?></div>
                                        <? else:?>
                                            <div class="base-price"><? echo CurrencyFormat($arPrice['RESULT_PRICE']['BASE_PRICE'], $arPrice['PRICE']['CURRENCY']);?></div>
                                        <? endif;?>
                                    </div>
                                    <div class="product-item-button">
                                        <button data-id="<?=$ar_res['ID'];?>">В корзину</button>
                                    </div>
                                </div>
                            </div>
			<?
			?></br><? } ?>
		</div>
	</div>
<?                               
}
?>
</div>
</div>
 
    <div class="panel panel-default">
        
    </div>

    <div class="row">
        <div class="col-xs-12" id="select-result"></div>
    </div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>