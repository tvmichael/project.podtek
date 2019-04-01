<?php
/**
 * User: Michael
 * Date: 26.03.2019
 */
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
        $rem_volume = 0;
        $arSelect = Array();
        $arFilter = Array("IBLOCK_ID"=>"11", "SECTION_ID"=>"703", "ACTIVE"=>"Y");
        $res_product = CIBlockElement::GetList( Array(),$arFilter,false,Array(),$arSelect);
        while($ob = $res_product->GetNextElement())
        {
            $arProps = $ob->GetProperties();
            $arFields = $ob->GetFields();
            $max_volume = floatval($arProps['max_volume']['VALUE']);
            if(isset($arProps['max_volume']) && isset($arProps['composition_catalog']) && $max_volume > 0)
            {
                if($volume <= $max_volume && ($max_volume <= $rem_volume || $rem_volume == 0))
                {
                    $rem_volume = $max_volume;
                    $acText = false;
                    if(is_array($arProps['composition_catalog']['VALUE']) && count($arProps['composition_catalog']['VALUE']) > 0):?>
                        <div class="row">
                            <?
                            foreach ($arProps['composition_catalog']['VALUE'] as $id):
                                ?>
                                <div class="col-xs-6 col-sm-4 col-md-2">
                                    <div class="product-item-container">
                                        <?
                                        $arPrice = CCatalogProduct::GetOptimalPrice($id, 1, $USER->GetUserGroupArray(), 'N');
                                        $res = CIBlockElement::GetByID($id);
                                        if($ar_res = $res->GetNext()) {
                                            $acText = true;
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
                                            <div class="product-item-title">
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
                            endforeach;?>
                        </div>
                    <? endif;
                }
            }
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