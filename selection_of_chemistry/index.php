<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetAdditionalCss($APPLICATION->GetCurPage() . "style.css");
$APPLICATION->AddHeadScript($APPLICATION->GetCurPage() . 'script.js');
$arParams['IBLOCK_ID'] = 13;
$APPLICATION->SetTitle("Подбор химии для бассейна");
?>
<h2>Подбор химии для бассейна</h2>
<?
$rs = CIBlockElement::GetList(
    Array('SORT' => 'ASC'),
    ['IBLOCK_ID' => '13', 'ACTIVE'=>'Y'],
    false, false,
    ['ID', 'IBLOCK_ID', 'NAME', 'CODE']
);
$tab = 0;
?>
<script>var acUrlAjax = '<?=$APPLICATION->GetCurPage() . 'basket_ajax.php';?>';</script>
<div class="product-item-detail-tabs-container">
    <ul class="product-item-detail-tabs-list">
        <?while ($ar = $rs->Fetch()) {?>
            <li class="product-item-detail-tab <?=($tab==0?'active':'');?>" data-entity="tab" data-value="<?= $ar['CODE'];?>">
                <a href="javascript:void(0);" class="product-item-detail-tab-link">
                    <span><?= $ar['NAME'] ?></span>
                </a>
            </li>
            <?$tab++;?>
        <?}?>
    </ul>
</div>
<?
$tab = 0;
$rs = CIBlockElement::GetList(
    Array('SORT' => 'ASC'),
    ['IBLOCK_ID' => '13', 'ACTIVE'=>'Y'],
    false, false,
    ['ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PREVIEW_TEXT']
);
?>
<div class="row" id="iblock-id-<?= $arParams['IBLOCK_ID'] ?>">
    <div class="col-xs-12">
        <?while ($ar = $rs->Fetch()):?>
            <div class="product-item-detail-tab-content" data-entity="tab-container" data-value="<?=$ar['CODE'] ?>" style="display:<?=($tab==0?'block':'none');?>;">
                <div class="col-xs-12 col-sm-12">
                    <?if ($ar['PREVIEW_TEXT'] != '') {
                        echo $ar['PREVIEW_TEXT_TYPE'] === 'html' ? $ar['PREVIEW_TEXT'] : '<p style = "font-size: 12px;">'.$ar['PREVIEW_TEXT'].'</p>';
                    }
                    $resGOODS = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $ar['ID'], array("sort" => "asc"), Array("CODE" => "GOODS_FOR_BOOKMARKS"));
                    $i = 1;?>
                    <div class="row">
                    <?while ($obGOODS = $resGOODS->GetNext()) {
                        $tegs[] = $obGOODS['VALUE'];
                        $resEl = CIBlockElement::GetByID($obGOODS['VALUE']);

                        if ($ar_resEl = $resEl->GetNext())
                            $resElement = CIBlockElement::GetByID($ar_resEl['ID']);

                        if ($ar_resElement = $resElement->GetNext()) $i++;
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
                    <?}?>
                    </div>
                </div>
            </div>
            <?$tab++;?>
        <?endwhile;?>
    </div>
</div>
<div class="panel panel-default"></div>
<div class="row">
    <div class="col-xs-12" id="select-result"></div>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
