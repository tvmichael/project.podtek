<?php
/**
 * User: Michael
 * Date: 26.03.2019
 */

use Bitrix\Main\Diag;

define('STOP_STATISTICS', true);
define('NOT_CHECK_PERMISSIONS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$request = $context->getRequest();
$valueAction = $request->getPost("action");

if($valueAction=='equipment')
{
    CModule::IncludeModule("catalog");
    $volume = floatval($request->getPost("volume"));

    if ($volume > 0)
    {
        $acText = false;
        $rem_volume = 0;
        $arSelect = Array();
        $arFilter = Array("IBLOCK_ID"=>"11", "SECTION_ID"=>"703", "ACTIVE"=>"Y");
        $res_product = CIBlockElement::GetList( Array(),$arFilter,false,Array(),$arSelect);

        $arrProductList = [];
        while($ob = $res_product->GetNextElement())
        {
            $arProps = $ob->GetProperties();

            if(is_array($arProps))
            {
                foreach ($arProps as $prop => $arProp)
                {
                    if(strpos($prop, 'composition_catalog') !== false)
                    {
                        if(!empty($arProps['max_volume']['VALUE']) && !empty($arProp['VALUE']))
                        {
                            $max_volume = floatval($arProps['max_volume']['VALUE']);
                            if(empty($arrProductList[$max_volume])) $arrProductList[$max_volume] = [];

                            $arrProductList[$max_volume][] = [
                                'NAME' => $arProp['NAME'],
                                'IDS' => $arProp['VALUE'],
                            ];;
                        }
                    }
                }
            }
        }

        if(krsort($arrProductList))
        {
            $volumeKey = 0;
            foreach ($arrProductList as $v => $product)
                if($v >= $volume) $volumeKey = $v;

            if(!empty($arrProductList[$volumeKey])):?>
                <div class="row">
                    <div class="col-md-12 panel-heading h3">
                        Подобранное оборудование для Вашего бассейна согласно габаритным параметрам
                    </div>
                    <div class="col-md-12">
                        <?foreach ($arrProductList[$volumeKey] as $productIds):?>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><?=$productIds['NAME'];?></h4>
                                </div>
                                <?foreach ($productIds['IDS'] as $id):?>
                                    <div class="col-xs-6 col-sm-4 col-md-2" style="padding-bottom: 30px;">
                                        <div class="product-item-container">
                                            <?
                                            $acText = true;
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
                                                <?$CURRENCYarPrice = CCurrency::GetByID("RUB");?>
                                                <? if($arPrice['RESULT_PRICE']['BASE_PRICE'] > $arPrice['RESULT_PRICE']['DISCOUNT_PRICE']):?>
                                                    <div class="old-price"><? echo CurrencyFormat($arPrice['RESULT_PRICE']['BASE_PRICE'], $CURRENCYarPrice['CURRENCY']);?></div>
                                                    <div class="new-price"><? echo CurrencyFormat($arPrice['RESULT_PRICE']['DISCOUNT_PRICE'], $CURRENCYarPrice['CURRENCY']);?></div>
                                                <? else:?>
                                                    <div class="base-price"><? echo CurrencyFormat($arPrice['RESULT_PRICE']['BASE_PRICE'], $CURRENCYarPrice['CURRENCY']);?></div>
                                                <? endif;?>
                                            </div>
                                            <div class="product-item-button">
                                                <button data-id="<?=$ar_res['ID'];?>">В корзину</button>
                                            </div>
                                        </div>
                                    </div>
                                <?endforeach;?>
                            </div>
                        <?endforeach;?>
                    </div>
                </div>
            <?endif;
        }
        if (!$acText) echo "<div class='alert alert-danger' role='alert'>Оборудованиe не найдено.</div>";
    }
}
if($valueAction=='Add2Basket')
{
    $PRODUCT_ID = intval($request->getPost("productId"));
    $QUANTITY = 1;
    $addProduct = false;
    if($PRODUCT_ID) $addProduct = Add2BasketByProductID($PRODUCT_ID, $QUANTITY, array(), array());
    echo json_encode(['action'=>'add2basket', 'result'=>$addProduct]);
}
?>