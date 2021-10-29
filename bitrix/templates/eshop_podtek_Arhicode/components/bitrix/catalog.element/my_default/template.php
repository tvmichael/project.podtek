<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);
$this->addExternalCss('/bitrix/css/main/bootstrap.css');

$templateLibrary = array('popup', 'fx');
$currencyList = '';

if (!empty($arResult['CURRENCIES'])) {
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$templateData = array(
    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList,
    'ITEM' => array(
        'ID' => $arResult['ID'],
        'IBLOCK_ID' => $arResult['IBLOCK_ID'],
        'OFFERS_SELECTED' => $arResult['OFFERS_SELECTED'],
        'JS_OFFERS' => $arResult['JS_OFFERS']
    )
);
unset($currencyList, $templateLibrary);

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
    'ID' => $mainId,
    'DISCOUNT_PERCENT_ID' => $mainId . '_dsc_pict',
    'STICKER_ID' => $mainId . '_sticker',
    'BIG_SLIDER_ID' => $mainId . '_big_slider',
    'BIG_IMG_CONT_ID' => $mainId . '_bigimg_cont',
    'SLIDER_CONT_ID' => $mainId . '_slider_cont',
    'OLD_PRICE_ID' => $mainId . '_old_price',
    'PRICE_ID' => $mainId . '_price',
    'DISCOUNT_PRICE_ID' => $mainId . '_price_discount',
    'PRICE_TOTAL' => $mainId . '_price_total',
    'SLIDER_CONT_OF_ID' => $mainId . '_slider_cont_',
    'QUANTITY_ID' => $mainId . '_quantity',
    'QUANTITY_DOWN_ID' => $mainId . '_quant_down',
    'QUANTITY_UP_ID' => $mainId . '_quant_up',
    'QUANTITY_MEASURE' => $mainId . '_quant_measure',
    'QUANTITY_LIMIT' => $mainId . '_quant_limit',
    'BUY_LINK' => $mainId . '_buy_link',
    'ADD_BASKET_LINK' => $mainId . '_add_basket_link',
    'BASKET_ACTIONS_ID' => $mainId . '_basket_actions',
    'NOT_AVAILABLE_MESS' => $mainId . '_not_avail',
    'COMPARE_LINK' => $mainId . '_compare_link',
    'TREE_ID' => $mainId . '_skudiv',
    'DISPLAY_PROP_DIV' => $mainId . '_sku_prop',
    'DISPLAY_MAIN_PROP_DIV' => $mainId . '_main_sku_prop',
    'OFFER_GROUP' => $mainId . '_set_group_',
    'BASKET_PROP_DIV' => $mainId . '_basket_prop',
    'SUBSCRIBE_LINK' => $mainId . '_subscribe',
    'TABS_ID' => $mainId . '_tabs',
    'TAB_CONTAINERS_ID' => $mainId . '_tab_containers',
    'SMALL_CARD_PANEL_ID' => $mainId . '_small_card_panel',
    'TABS_PANEL_ID' => $mainId . '_tabs_panel'
);
$obName = $templateData['JS_OBJ'] = 'ob' . preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
$name = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
    : $arResult['NAME'];
$title = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
    : $arResult['NAME'];
$alt = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
    : $arResult['NAME'];


$haveOffers = !empty($arResult['OFFERS']);
if ($haveOffers) {
    $actualItem = isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']])
        ? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]
        : reset($arResult['OFFERS']);
    $showSliderControls = false;

    foreach ($arResult['OFFERS'] as $offer) {
        if ($offer['MORE_PHOTO_COUNT'] > 1) {
            $showSliderControls = true;
            break;
        }
    }
} else {
    $actualItem = $arResult;
    $showSliderControls = $arResult['MORE_PHOTO_COUNT'] > 1;
}

$skuProps = array();
$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
$measureRatio = $actualItem['ITEM_MEASURE_RATIOS'][$actualItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
$showDiscount = $price['PERCENT'] > 0;

$showDescription = !empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']);
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';
$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($arResult['CATALOG_SUBSCRIBE'] === 'Y' || $haveOffers);

$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCE_CATALOG_BUY');
$arParams['MESS_BTN_ADD_TO_BASKET'] = $arParams['MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCE_CATALOG_ADD');
$arParams['MESS_NOT_AVAILABLE'] = $arParams['MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE');
$arParams['MESS_BTN_COMPARE'] = $arParams['MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCE_CATALOG_COMPARE');
$arParams['MESS_PRICE_RANGES_TITLE'] = $arParams['MESS_PRICE_RANGES_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_PRICE_RANGES_TITLE');
$arParams['MESS_DESCRIPTION_TAB'] = $arParams['MESS_DESCRIPTION_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_DESCRIPTION_TAB');
$arParams['MESS_PROPERTIES_TAB'] = $arParams['MESS_PROPERTIES_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_PROPERTIES_TAB');
$arParams['MESS_COMMENTS_TAB'] = $arParams['MESS_COMMENTS_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_COMMENTS_TAB');
$arParams['MESS_SHOW_MAX_QUANTITY'] = $arParams['MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCE_CATALOG_SHOW_MAX_QUANTITY');
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW');

/****************************************/
$arParams['MESS_ACCESSORIES_TAB'] = $arParams['MESS_ACCESSORIES_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_ACCESSORIES_TAB');
$arParams['MESS_VIDEO_YOUTUBE_TAB'] = $arParams['MESS_VIDEO_YOUTUBE_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_VIDEO_YOUTUBE_TAB');
$arParams['MESS_INSTRUCTIONS_TAB'] = $arParams['MESS_INSTRUCTIONS_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_INSTRUCTIONS_TAB');
$arParams['MESS_CERTIFICATES_TAB'] = $arParams['MESS_CERTIFICATES_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_CERTIFICATES_TAB');
$arParams['MESS_DIMENSIONS_TAB'] = $arParams['MESS_DIMENSIONS_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_DIMENSIONS_TAB');
$arParams['MESS_TEMPPANEL_TAB'] = $arParams['MESS_TEMPPANEL_TAB'] ?: Loc::getMessage('CP_BCE_TEMPPANEL_TAB');


$isACCESSORIES = false;
$isVIDEOYOUTUBE = false;
$isINSTRUCTIONS = false;
$isCERTIFICATES = false;
$isDIMENSIONS = false;
$isTempPanel = false;

$resPHOTO_ACCESSORIES = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "PHOTO_ACCESSORIES"));
$resACCESSORIES = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "ACCESSORIES"));
$i = 1;
while ($obPHOTO_ACCESSORIES = $resPHOTO_ACCESSORIES->GetNext()) {
    if (!empty($obPHOTO_ACCESSORIES['VALUE'])) {
        $isACCESSORIES = true;
    }
}
while ($obACCESSORIES = $resACCESSORIES->GetNext()) {
    if (!empty($obACCESSORIES['VALUE'])) {
        $isACCESSORIES = true;
    }
}

$resVIDEOYOUTUBE = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "VIDEO_YOUTUBE"));
while ($obVideoYoutube = $resVIDEOYOUTUBE->GetNext()) {
    if (!empty($obVideoYoutube['VALUE'])) {
        $isVIDEOYOUTUBE = true;
    }
}

$resINSTRUCTIONS = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "INSTRUCTIONS"));
while ($obINSTRUCTIONS = $resINSTRUCTIONS->GetNext()) {
    if (!empty($obINSTRUCTIONS['VALUE'])) {
        $isINSTRUCTIONS = true;
    }
}

$resCERTIFICATES = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "CERTIFICATES"));
while ($obCERTIFICATES = $resCERTIFICATES->GetNext()) {
    if (!empty($obCERTIFICATES['VALUE'])) {
        $isCERTIFICATES = true;
    };
    //if($USER->IsAdmin()) {echo '<pre>'; print_r($obCERTIFICATES['VALUE']); echo '</pre>';}
}
$resDIMENSIONS = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "DIMENSIONS"));
while ($obDIMENSIONS = $resDIMENSIONS->GetNext()) {
    if (!empty($obDIMENSIONS['VALUE'])) {
        $isDIMENSIONS = true;
    };
}

//if($USER->IsAdmin()) {$isTempPanel = true;}
/***************************************/

/***************20_08_2019***************/
$isRELATEDPRODUCTS = false;
$isSIMILARPRODUCTS = false;
//if($USER->IsAdmin()) {echo '<pre>'; print_r($arResult['ID']); echo '</pre>';}
$resRELATEDPRODUCTS = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "RELATED_PRODUCTS"));
$resSIMILARPRODUCTS = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SIMILAR_PRODUCTS"));
while ($obRELATEDPRODUCTS = $resRELATEDPRODUCTS->GetNext()) {
    if (!empty($obRELATEDPRODUCTS['VALUE'])) {
        $isRELATEDPRODUCTS = true;
		//if($USER->IsAdmin()) {echo '<pre>'; print_r($obRELATEDPRODUCTS['VALUE']); echo '</pre>';}
    };
}
while ($obSIMILARPRODUCTS = $resSIMILARPRODUCTS->GetNext()) {
    if (!empty($obSIMILARPRODUCTS['VALUE'])) {
        $isSIMILARPRODUCTS = true;

    };
}
$isH2ATTRIBUTES = false;
$isH2DESCRIPTION = false;
$isH2DIMENSIONS = false;
$isH2ACCESSORIES = false;
$isH2RELATEDPRODUCTS = false;
$isH2SIMILARPRODUCTS = false;
$isH2SECTIONVIDEO = false;
$isH2INSTRUCTIONS = false;
$isH2CERTIFICATES = false;

$resH2ATTRIBUTES = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_SECTION_ATTRIBUTES"));
while ($obH2ATTRIBUTES = $resH2ATTRIBUTES->GetNext()) {
    if (!empty($obH2ATTRIBUTES['VALUE'])) {
        $isH2ATTRIBUTES = true;
    }
}

$resH2DESCRIPTION = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_SECTION_DESCRIPTION"));
while ($obH2DESCRIPTION = $resH2DESCRIPTION->GetNext()) {
    if (!empty($obH2DESCRIPTION['VALUE'])) {
        $isH2DESCRIPTION = true;
    }
}

$resH2DIMENSIONS = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_SECTION_DIMENSIONS"));
while ($obH2DIMENSIONS = $resH2DIMENSIONS->GetNext()) {
    if (!empty($obH2DIMENSIONS['VALUE'])) {
        $isH2DIMENSIONS = true;
    }
}

$resH2ACCESSORIES = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_SECTION_ACCESSORIES"));
while ($obH2ACCESSORIES = $resH2ACCESSORIES->GetNext()) {
    if (!empty($obH2ACCESSORIES['VALUE'])) {
        $isH2ACCESSORIES = true;
    }
}

$resH2RELATEDPRODUCTS = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_SECTION_RELATED_PRODUCTS"));
while ($obH2RELATEDPRODUCTS = $resH2RELATEDPRODUCTS->GetNext()) {
    if (!empty($obH2RELATEDPRODUCTS['VALUE'])) {
        $isH2RELATEDPRODUCTS = true;
    }
}
$resH2SIMILARPRODUCTS = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_SECTION_SIMILAR_PRODUCTS"));
while ($obH2SIMILARPRODUCTS = $resH2SIMILARPRODUCTS->GetNext()) {
    if (!empty($obH2SIMILARPRODUCTS['VALUE'])) {
        $isH2SIMILARPRODUCTS = true;
    }
}

$resH2SECTIONVIDEO = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_SECTION_VIDEO"));
while ($obH2SECTIONVIDEO = $resH2SECTIONVIDEO->GetNext()) {
    if (!empty($obH2SECTIONVIDEO['VALUE'])) {
        $isH2SECTIONVIDEO = true;
    }
}

$resH2INSTRUCTIONS = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_INSTRUCTIONS"));
while ($obH2INSTRUCTIONS = $resH2INSTRUCTIONS->GetNext()) {
    if (!empty($obH2INSTRUCTIONS['VALUE'])) {
        $isH2INSTRUCTIONS = true;
    }
}


$resH2CERTIFICATES = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_CERTIFICATES"));
while ($obH2CERTIFICATES = $resH2CERTIFICATES->GetNext()) {
    if (!empty($obH2CERTIFICATES['VALUE'])) {
        $isH2CERTIFICATES = true;
    }
}

/***************************************/
$positionClassMap = array(
    'left' => 'product-item-label-left',
    'center' => 'product-item-label-center',
    'right' => 'product-item-label-right',
    'bottom' => 'product-item-label-bottom',
    'middle' => 'product-item-label-middle',
    'top' => 'product-item-label-top'
);

$discountPositionClass = 'product-item-label-big';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION'])) {
    foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos) {
        $discountPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
    }
}

$labelPositionClass = 'product-item-label-big';
if (!empty($arParams['LABEL_PROP_POSITION'])) {
    foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos) {
        $labelPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
    }
}
?>

<?php
$showDiscountList = false;
$arFileDiscount = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/include/discount_config.php");
if(isset($arFileDiscount['type']))
{
    if($arFileDiscount['type'] == 'text/x-php')
    {
        $arFileDiscount = require($_SERVER["DOCUMENT_ROOT"]."/include/discount_config.php");
        $getDiscountArray = $arFileDiscount['discount'];
        $getDeliveriId = $arFileDiscount['delivery'];
        $maxPrice = $arFileDiscount['maxPrice'];

        $discountList = [];
        $arProductPrice = CCatalogProduct::GetOptimalPrice($arResult['ID'], 1, $USER->GetUserGroupArray());

        if (isset($arProductPrice['DISCOUNT_LIST'])) {
            $percentOn = 0;
            $percentOff = 0;
            foreach ($arProductPrice['DISCOUNT_LIST'] as $discount)
            {
                if (in_array($discount['ID'], $getDiscountArray))
                {
                    $showDiscountList = true;
                    $discountList[$discount['ID']] = $discount;
                    $percentOff += $discount['VALUE'];
                } else $percentOn += $discount['VALUE'];
            }

            if ($showDiscountList) {
                if ($percentOn == 0) {
                    $arParams['SHOW_DISCOUNT_PERCENT'] = 'N';
                    $showDiscount = false;
                }

                $reDiscount = $arProductPrice['PRICE']['PRICE'] * ($percentOn / 100);
                $reCalculatePrice = $arProductPrice['PRICE']['PRICE'] - $reDiscount;

                $price['PRINT_RATIO_PRICE'] = CCurrencyLang::CurrencyFormat($reCalculatePrice, 'RUB');
                $price['PRINT_RATIO_DISCOUNT'] = CCurrencyLang::CurrencyFormat($reDiscount, 'RUB');
                $price['PERCENT'] = $percentOn;
            }
        }

        if(isset($arProductPrice['PRICE']['PRICE']))
            if($arProductPrice['PRICE']['PRICE'] > $maxPrice)
            {
                if(!isset($discountList[$getDeliveriId]))
                {
                    $showDiscountList = true;
                    $discountList[$getDeliveriId] = [
                        'ID'=>$getDeliveriId,
                        'NAME'=>'Бесплатная доставка',
                    ];
                }
            }
    }
}

if(!empty($price['PRINT_RATIO_PRICE']) && !empty($price['RATIO_PRICE']))
{
    $arResult['META_TAGS']['DESCRIPTION'] = preg_replace("/([\d\s\.,]+руб. )|(((€)|(&euro;))[\d\s\.,]+)/", ' ' . $price['PRINT_RATIO_PRICE'] . ' ', $arResult['META_TAGS']['DESCRIPTION']);
}
//if($USER->IsAdmin() && $USER->GetID() == 8) {echo '<pre data-mv="0" style="display:none;">'; print_r($arResult); echo '</pre>';}
?>

    <div class="bx-catalog-element bx-<?= $arParams['TEMPLATE_THEME'] ?>" id="<?= $itemIds['ID'] ?>"
         itemscope itemtype="http://schema.org/Product">
        <div class="container-fluid">
            <?
            if ($arParams['DISPLAY_NAME'] === 'Y') {
                ?>
                <div class="row">
                    <div class="col-xs-12">
                        <h1 class="bx-title"><?= $name ?></h1>
                    </div>
                </div>
                <?
            }
            ?>
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="product-item-detail-slider-container" id="<?= $itemIds['BIG_SLIDER_ID'] ?>">
                        <div class="product-item-discount">
                            <? if($showDiscountList):
                                foreach ($discountList as $discountItem):
                                    if(isset($discountItem['NAME'])):?>
									
                                        <div class="product-item-discount-text"><?=$discountItem['NAME'];?>
                                            <div class="product-item-discount-file">
                                                <? $APPLICATION->IncludeFile(
                                                    "/include/discounts_".$discountItem['ID'].".php",
                                                    Array(),
                                                    Array("MODE"=>"php")
                                                );?>
                                            </div>
                                        </div>
                                    <? 
									//if($USER->IsAdmin()) {echo '<pre>'; print_r($discountItem); echo '</pre>';}
									endif;?>
                                <? endforeach;?>
                            <? endif;?>
                            <?if(isset($arResult['PROPERTIES']['BESPLATNAYA_USTANOVKA']) && $arResult['PROPERTIES']['BESPLATNAYA_USTANOVKA']['VALUE']):?>
                                <div class="product-item-discount-text">
                                    <?=$arResult['PROPERTIES']['BESPLATNAYA_USTANOVKA']['NAME'];?>
                                    <? $fileName = $_SERVER["DOCUMENT_ROOT"]."/include/discounts_BESPLATNAYA_USTANOVKA.php";
                                    if(file_exists($fileName)):
                                        if(filesize($fileName) > 1):?>
                                            <div class="product-item-discount-file">
                                                <?$APPLICATION->IncludeFile(
                                                    "/include/discounts_BESPLATNAYA_USTANOVKA.php",
                                                    Array(),
                                                    Array("MODE" => "php")
                                                );?>
                                            </div>
                                        <? endif;
                                    endif;?>
                                </div>
                            <?endif;?>
                            <?if(isset($arResult['PROPERTIES']['FREE_SHIPPING']) && !empty($arResult['PROPERTIES']['FREE_SHIPPING']['VALUE'])):?>
                                <div class="product-item-discount-text">
                                    <?=$arResult['PROPERTIES']['FREE_SHIPPING']['NAME'];?>
                                    <? $fileName = $_SERVER["DOCUMENT_ROOT"]."/include/discounts_FREE_SHIPPING.php";
                                    if(file_exists($fileName)):
                                        if(filesize($fileName) > 1):?>
                                            <div class="product-item-discount-file">
                                                <?$APPLICATION->IncludeFile(
                                                    "/include/discounts_FREE_SHIPPING.php",
                                                    Array(),
                                                    Array("MODE" => "php")
                                                );?>
                                            </div>
                                        <? endif;
                                    endif;?>
                                </div>
                            <?endif;?>
                        </div>
                        <span class="product-item-detail-slider-close" data-entity="close-popup"></span>
                        <div class="product-item-detail-slider-block
						<?= ($arParams['IMAGE_RESOLUTION'] === '1by1' ? 'product-item-detail-slider-block-square' : '') ?>"
                             data-entity="images-slider-block">
                            <span class="product-item-detail-slider-left" data-entity="slider-control-left"
                                  style="display: none;"></span>
                            <span class="product-item-detail-slider-right" data-entity="slider-control-right"
                                  style="display: none;"></span>
                            <div class="product-item-label-text <?= $labelPositionClass ?>"
                                 id="<?= $itemIds['STICKER_ID'] ?>"
                                <?= (!$arResult['LABEL'] ? 'style="display: none;"' : '') ?>>
                                <?
                                if ($arResult['LABEL'] && !empty($arResult['LABEL_ARRAY_VALUE'])) {
                                    foreach ($arResult['LABEL_ARRAY_VALUE'] as $code => $value) {
                                        ?>
                                        <div<?= (!isset($arParams['LABEL_PROP_MOBILE'][$code]) ? ' class="hidden-xs"' : '') ?>>
                                            <span title="<?= $value ?>"><?= $value ?></span>
                                        </div>
                                        <?
                                    }
                                }
                                ?>
                            </div>
                            <?
                            if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y') {
                                if ($haveOffers) {
                                    ?>
                                    <div class="product-item-label-ring <?= $discountPositionClass ?>"
                                         id="<?= $itemIds['DISCOUNT_PERCENT_ID'] ?>"
                                         style="display: none;">
                                    </div>
                                    <?
                                } else {
                                    if ($price['DISCOUNT'] > 0) {
                                        ?>
                                        <div class="product-item-label-ring <?= $discountPositionClass ?>"
                                             id="<?= $itemIds['DISCOUNT_PERCENT_ID'] ?>"
                                             title="<?= -$price['PERCENT'] ?>%">
                                            <span><?= -$price['PERCENT'] ?>%</span>
                                        </div>
                                        <?
                                    }
                                }
                            }
                            ?>
                            <div class="product-item-detail-slider-images-container" data-entity="images-container">
                                <?
								
                                if (!empty($actualItem['MORE_PHOTO'])) {
                                    foreach ($actualItem['MORE_PHOTO'] as $key => $photo) {
                                        ?>
                                        <div class="product-item-detail-slider-image<?= ($key == 0 ? ' active' : '') ?>"
                                             data-entity="image" data-id="<?= $photo['ID'] ?>">
                                            <img src="<?= $photo['SRC'] ?>" alt="<?= $alt ?>"
                                                 title="<?= $title ?>"<?= ($key == 0 ? ' itemprop="image"' : '') ?>>
                                        </div>
                                        <?
                                    }
                                }

                                if ($arParams['SLIDER_PROGRESS'] === 'Y') {
                                    ?>
                                    <div class="product-item-detail-slider-progress-bar"
                                         data-entity="slider-progress-bar" style="width: 0;"></div>
                                    <?
                                }
                                ?>
                            </div>
                        </div>
                        <?
                        if ($showSliderControls) {
                            if ($haveOffers) {
                                foreach ($arResult['OFFERS'] as $keyOffer => $offer) {
                                    if (!isset($offer['MORE_PHOTO_COUNT']) || $offer['MORE_PHOTO_COUNT'] <= 0)
                                        continue;

                                    $strVisible = $arResult['OFFERS_SELECTED'] == $keyOffer ? '' : 'none';
                                    ?>
                                    <div class="product-item-detail-slider-controls-block"
                                         id="<?= $itemIds['SLIDER_CONT_OF_ID'] . $offer['ID'] ?>"
                                         style="display: <?= $strVisible ?>;">
                                        <?
                                        foreach ($offer['MORE_PHOTO'] as $keyPhoto => $photo) {
                                            ?>
                                            <div class="product-item-detail-slider-controls-image<?= ($keyPhoto == 0 ? ' active' : '') ?>"
                                                 data-entity="slider-control"
                                                 data-value="<?= $offer['ID'] . '_' . $photo['ID'] ?>">
                                                <img src="<?= $photo['SRC'] ?>" alt="<?= $alt ?>">
                                            </div>
                                            <?
                                        }
                                        ?>
                                    </div>
                                    <?
                                }
                            } else {
                                ?>
                                <div class="product-item-detail-slider-controls-block"
                                     id="<?= $itemIds['SLIDER_CONT_ID'] ?>">
                                    <?
                                    if (!empty($actualItem['MORE_PHOTO'])) {
                                        foreach ($actualItem['MORE_PHOTO'] as $key => $photo) {
                                            ?>
                                            <div class="product-item-detail-slider-controls-image<?= ($key == 0 ? ' active' : '') ?>"
                                                 data-entity="slider-control" data-value="<?= $photo['ID'] ?>">
                                                <img src="<?= $photo['SRC'] ?>" alt="<?= $alt ?>">
                                            </div>
                                            <?
                                        }
                                    }
                                    ?>
                                </div>
                                <?
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-8 col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-xs-12">
                                <h1 class="bx-title"><?= $name ?></h1>
                                <!--?$rest = substr($arResult['PROPERTIES']['CML2_TRAITS']['VALUE']['2'], 3);?-->
                                <? $rest = $arResult['ID']; ?>
                                <div class="bx-code"><span>код товара: </span><?= $rest; ?></div>
                                <!--?
								if (
									$arResult['PREVIEW_TEXT'] != ''
								)
								{
									echo $arResult['PREVIEW_TEXT_TYPE'] === 'html' ? $arResult['PREVIEW_TEXT'] : '<p style = "font-size: 12px;">'.$arResult['PREVIEW_TEXT'].'</p>';
								}
							?-->
                                <!--?if($USER->IsAdmin()) {echo '<pre>'; print_r($arResult['PROPERTIES']['CML2_TRAITS']['VALUE']['2']); echo '</pre>';}?-->
                            </div>
                            <div class="col-xs-12  product-item-detail-info-section">
                                <?
                                foreach ($arParams['PRODUCT_INFO_BLOCK_ORDER'] as $blockName) {
                                    switch ($blockName) {
                                        case 'sku':
                                            if ($haveOffers && !empty($arResult['OFFERS_PROP'])) {
                                                ?>
                                                <div id="<?= $itemIds['TREE_ID'] ?>">
                                                    <?
                                                    foreach ($arResult['SKU_PROPS'] as $skuProperty) {
                                                        if (!isset($arResult['OFFERS_PROP'][$skuProperty['CODE']]))
                                                            continue;

                                                        $propertyId = $skuProperty['ID'];
                                                        $skuProps[] = array(
                                                            'ID' => $propertyId,
                                                            'SHOW_MODE' => $skuProperty['SHOW_MODE'],
                                                            'VALUES' => $skuProperty['VALUES'],
                                                            'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
                                                        );
                                                        ?>
                                                        <div class="product-item-detail-info-container 1"
                                                             data-entity="sku-line-block">
                                                            <div class="product-item-detail-info-container-title"><?= htmlspecialcharsEx($skuProperty['NAME']) ?></div>
                                                            <div class="product-item-scu-container">
                                                                <div class="product-item-scu-block">
                                                                    <div class="product-item-scu-list">
                                                                        <ul class="product-item-scu-item-list">
                                                                            <?
                                                                            foreach ($skuProperty['VALUES'] as &$value) {
                                                                                $value['NAME'] = htmlspecialcharsbx($value['NAME']);

                                                                                if ($skuProperty['SHOW_MODE'] === 'PICT') {
                                                                                    ?>
                                                                                    <li class="product-item-scu-item-color-container"
                                                                                        title="<?= $value['NAME'] ?>"
                                                                                        data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
                                                                                        data-onevalue="<?= $value['ID'] ?>">
                                                                                        <div class="product-item-scu-item-color-block">
                                                                                            <div class="product-item-scu-item-color"
                                                                                                 title="<?= $value['NAME'] ?>"
                                                                                                 style="background-image: url('<?= $value['PICT']['SRC'] ?>');">
                                                                                            </div>
                                                                                        </div>
                                                                                    </li>
                                                                                    <?
                                                                                } else {
                                                                                    ?>
                                                                                    <li class="product-item-scu-item-text-container"
                                                                                        title="<?= $value['NAME'] ?>"
                                                                                        data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
                                                                                        data-onevalue="<?= $value['ID'] ?>">
                                                                                        <div class="product-item-scu-item-text-block">
                                                                                            <div class="product-item-scu-item-text"><?= $value['NAME'] ?></div>
                                                                                        </div>
                                                                                    </li>
                                                                                    <?
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </ul>
                                                                        <div style="clear: both;"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?
                                                    }
                                                    ?>
                                                </div>
                                                <?
                                            }

                                            break;

                                        case 'props':
                                            if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS']) {
                                                ?>
                                                <div class="product-item-detail-info-container 2">
                                                    <?
                                                    if (!empty($arResult['DISPLAY_PROPERTIES'])) {
                                                        ?>
                                                        <dl class="product-item-detail-properties">
                                                            <?
                                                            foreach ($arResult['DISPLAY_PROPERTIES'] as $property) {
                                                                if (isset($arParams['MAIN_BLOCK_PROPERTY_CODE'][$property['CODE']])) {
                                                                    ?>
                                                                    <dt><?= $property['NAME'] ?></dt>
                                                                    <dd><?= (is_array($property['DISPLAY_VALUE'])
                                                                            ? implode(' / ', $property['DISPLAY_VALUE'])
                                                                            : $property['DISPLAY_VALUE']) ?>
                                                                    </dd>
                                                                    <?
                                                                }
                                                            }
                                                            unset($property);
                                                            ?>
                                                        </dl>
                                                        <?
                                                    }

                                                    if ($arResult['SHOW_OFFERS_PROPS']) {
                                                        ?>
                                                        <dl class="product-item-detail-properties"
                                                            id="<?= $itemIds['DISPLAY_MAIN_PROP_DIV'] ?>"></dl>
                                                        <?
                                                    }
                                                    ?>
                                                </div>
                                                <?
                                            }

                                            break;
                                    }
                                }
                                ?>
                            </div>
                            <!-------------price--------------->
                            <div class="col-xs-12 col-md-7 product-item-detail-pay-block">
                                <?
                                foreach ($arParams['PRODUCT_PAY_BLOCK_ORDER'] as $blockName) {
                                    switch ($blockName) {
                                        case 'rating':
                                            if ($arParams['USE_VOTE_RATING'] === 'Y') {
                                                ?>
                                                <div class="product-item-detail-info-container 3">
                                                    <?
                                                    $APPLICATION->IncludeComponent(
                                                        'bitrix:iblock.vote',
                                                        'stars',
                                                        array(
                                                            'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
                                                            'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                                                            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                                                            'ELEMENT_ID' => $arResult['ID'],
                                                            'ELEMENT_CODE' => '',
                                                            'MAX_VOTE' => '5',
                                                            'VOTE_NAMES' => array('1', '2', '3', '4', '5'),
                                                            'SET_STATUS_404' => 'N',
                                                            'DISPLAY_AS_RATING' => $arParams['VOTE_DISPLAY_AS_RATING'],
                                                            'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                                            'CACHE_TIME' => $arParams['CACHE_TIME']
                                                        ),
                                                        $component,
                                                        array('HIDE_ICONS' => 'Y')
                                                    );
                                                    ?>
                                                </div>
                                                <?
                                            }

                                            break;

                                        case 'price':
                                            ?>
                                            <div class="product-item-detail-info-container 4 col-xs-12 col-sm-5">
                                                <?
                                                if ($arParams['SHOW_OLD_PRICE'] === 'Y') {
                                                    ?>
                                                    <div class="product-item-detail-price-old"
                                                         id="<?= $itemIds['OLD_PRICE_ID'] ?>"
                                                         style="display: <?= ($showDiscount ? '' : 'none') ?>;">
                                                        <?= ($showDiscount ? $price['PRINT_RATIO_BASE_PRICE'] : '') ?>
                                                    </div>
                                                    <?
                                                }
                                                ?>
                                                <div class="product-item-detail-price-current" id="<?= $itemIds['PRICE_ID'] ?>">
                                                    <?= $price['PRINT_RATIO_PRICE'] ?>
                                                </div>
                                                <?
                                                if ($arParams['SHOW_OLD_PRICE'] === 'Y') {
                                                    ?>
                                                    <div class="item_economy_price"
                                                         id="<?= $itemIds['DISCOUNT_PRICE_ID'] ?>"
                                                         style="display: <?= ($showDiscount ? '' : 'none') ?>;">
                                                        <?
                                                        if ($showDiscount) {
                                                            echo Loc::getMessage('CT_BCE_CATALOG_ECONOMY_INFO2', array('#ECONOMY#' => $price['PRINT_RATIO_DISCOUNT']));
                                                        }
                                                        ?>
                                                    </div>
                                                    <?
                                                }
                                                ?>

                                            </div>
                                            <?
                                            break;

                                        case 'priceRanges':
                                            if ($arParams['USE_PRICE_COUNT']) {
                                                $showRanges = !$haveOffers && count($actualItem['ITEM_QUANTITY_RANGES']) > 1;
                                                $useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';
                                                ?>
                                                <div class="product-item-detail-info-container 5"
                                                    <?= $showRanges ? '' : 'style="display: none;"' ?>
                                                     data-entity="price-ranges-block">
                                                    <div class="product-item-detail-info-container-title">
                                                        <?= $arParams['MESS_PRICE_RANGES_TITLE'] ?>
                                                        <span data-entity="price-ranges-ratio-header">
														(<?= (Loc::getMessage(
                                                                'CT_BCE_CATALOG_RATIO_PRICE',
                                                                array('#RATIO#' => ($useRatio ? $measureRatio : '1') . ' ' . $actualItem['ITEM_MEASURE']['TITLE'])
                                                            )) ?>)
													</span>
                                                    </div>
                                                    <dl class="product-item-detail-properties"
                                                        data-entity="price-ranges-body">
                                                        <?
                                                        if ($showRanges) {
                                                            foreach ($actualItem['ITEM_QUANTITY_RANGES'] as $range) {
                                                                if ($range['HASH'] !== 'ZERO-INF') {
                                                                    $itemPrice = false;

                                                                    foreach ($arResult['ITEM_PRICES'] as $itemPrice) {
                                                                        if ($itemPrice['QUANTITY_HASH'] === $range['HASH']) {
                                                                            break;
                                                                        }
                                                                    }

                                                                    if ($itemPrice) {
                                                                        ?>
                                                                        <dt>
                                                                            <?
                                                                            echo Loc::getMessage(
                                                                                    'CT_BCE_CATALOG_RANGE_FROM',
                                                                                    array('#FROM#' => $range['SORT_FROM'] . ' ' . $actualItem['ITEM_MEASURE']['TITLE'])
                                                                                ) . ' ';

                                                                            if (is_infinite($range['SORT_TO'])) {
                                                                                echo Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
                                                                            } else {
                                                                                echo Loc::getMessage(
                                                                                    'CT_BCE_CATALOG_RANGE_TO',
                                                                                    array('#TO#' => $range['SORT_TO'] . ' ' . $actualItem['ITEM_MEASURE']['TITLE'])
                                                                                );
                                                                            }
                                                                            ?>
                                                                        </dt>
                                                                        <dd><?= ($useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE']) ?></dd>
                                                                        <?
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </dl>
                                                </div>
                                                <?
                                                unset($showRanges, $useRatio, $itemPrice, $range);
                                            }

                                            break;

                                    }
                                }

                                if ($arParams['DISPLAY_COMPARE']) {
                                    ?>
                                    <div class="product-item-detail-compare-container">
                                        <div class="product-item-detail-compare">
                                            <div class="checkbox">
                                                <label id="<?= $itemIds['COMPARE_LINK'] ?>">
                                                    <input type="checkbox" data-entity="compare-checkbox">
                                                    <span data-entity="compare-title"><?= $arParams['MESS_BTN_COMPARE'] ?></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                }
                                ?>

                                <!----------------------------------->

                                <!-------------quantity--------------->
                                <div class="col-xs-4 col-sm-4 product-item-detail-pay-block_1">
                                    <?
                                    foreach ($arParams['PRODUCT_PAY_BLOCK_ORDER'] as $blockName) {
                                        switch ($blockName) {
                                            case 'quantityLimit':
                                                if ($arParams['SHOW_MAX_QUANTITY'] !== 'N') {
                                                    if ($haveOffers) {
                                                        ?>
                                                        <div class="product-item-detail-info-container 6"
                                                             id="<?= $itemIds['QUANTITY_LIMIT'] ?>"
                                                             style="display: none;">
                                                            <div class="product-item-detail-info-container-title">
                                                                <?= $arParams['MESS_SHOW_MAX_QUANTITY'] ?>:
                                                                <span class="product-item-quantity"
                                                                      data-entity="quantity-limit-value"></span>
                                                            </div>
                                                        </div>
                                                        <?
                                                    } else {
                                                        if (
                                                            $measureRatio
                                                            && (float)$actualItem['CATALOG_QUANTITY'] > 0
                                                            && $actualItem['CATALOG_QUANTITY_TRACE'] === 'Y'
                                                            && $actualItem['CATALOG_CAN_BUY_ZERO'] === 'N'
                                                        ) {
                                                            ?>
                                                            <div class="product-item-detail-info-container 7"
                                                                 id="<?= $itemIds['QUANTITY_LIMIT'] ?>">
                                                                <div class="product-item-detail-info-container-title">
                                                                    <?= $arParams['MESS_SHOW_MAX_QUANTITY'] ?>:
                                                                    <span class="product-item-quantity"
                                                                          data-entity="quantity-limit-value">
																	<?
                                                                    if ($arParams['SHOW_MAX_QUANTITY'] === 'M') {
                                                                        if ((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR']) {
                                                                            echo $arParams['MESS_RELATIVE_QUANTITY_MANY'];
                                                                        } else {
                                                                            echo $arParams['MESS_RELATIVE_QUANTITY_FEW'];
                                                                        }
                                                                    } else {
                                                                        echo $actualItem['CATALOG_QUANTITY'] . ' ' . $actualItem['ITEM_MEASURE']['TITLE'];
                                                                    }
                                                                    ?>
																</span>
                                                                </div>
                                                            </div>
                                                            <?
                                                        }
                                                    }
                                                }

                                                break;

                                            case 'quantity':
                                                if ($arParams['USE_PRODUCT_QUANTITY']) {
                                                    ?>
                                                    <div class="product-item-detail-info-container 8"
                                                         style="<?= (!$actualItem['CAN_BUY'] ? 'display: none;' : '') ?>"
                                                         data-entity="quantity-block">
                                                        <div class="product-item-detail-info-container-title"><?= Loc::getMessage('CATALOG_QUANTITY') ?></div>
                                                        <div class="product-item-amount">
                                                            <div class="product-item-amount-field-container">
															<span class="col-xs-6 product-item-amount-description-container">
																<span id="<?= $itemIds['QUANTITY_MEASURE'] ?>">
																	<?= Loc::getMessage('CT_BCE_QUANTITY') ?>
																</span>
																<span id="<?= $itemIds['PRICE_TOTAL'] ?>"></span>
															</span>
                                                                <!--span class="product-item-amount-field-btn-minus no-select" id="<!--?=$itemIds['QUANTITY_DOWN_ID']?>"></span-->
                                                                <input class="col-xs-6 product-item-amount-field"
                                                                       id="<?= $itemIds['QUANTITY_ID'] ?>" type="number"
                                                                       value="<?= $price['MIN_QUANTITY'] ?>">
                                                                <!--span class="product-item-amount-field-btn-plus no-select" id="<!--?=$itemIds['QUANTITY_UP_ID']?>"></span-->

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?
                                                }

                                                break;

                                        }
                                    }

                                    if ($arParams['DISPLAY_COMPARE']) {
                                        ?>
                                        <div class="product-item-detail-compare-container">
                                            <div class="product-item-detail-compare">
                                                <div class="checkbox">
                                                    <label id="<?= $itemIds['COMPARE_LINK'] ?>">
                                                        <input type="checkbox" data-entity="compare-checkbox">
                                                        <span data-entity="compare-title"><?= $arParams['MESS_BTN_COMPARE'] ?></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <?
                                    }
                                    ?>
                                </div>
                                <!-------------buttons--------------->
                                <div class="col-xs-12 col-sm-3 product-item-detail-pay-block_1">
                                    <?
                                    foreach ($arParams['PRODUCT_PAY_BLOCK_ORDER'] as $blockName) {
                                        switch ($blockName) {
                                            case 'buttons':
                                                ?>
												<?
												$textstyle = '';
												if (($arResult['PROPERTIES']['NALICHIE']['VALUE'] == 'Нет в наличии')) {
													$textstyle = 'none';
												}?>
												<?
														 /*if($USER->IsAdmin()) {echo '<pre>'; print_r($arResult['ID']); echo '</pre>';}*/
														 $mNALICHIE = "";
														 $arFilter = Array("PRODUCT_ID"=>$arResult['ID'],"ID"=>3);
														$arSelectFields = Array("PRODUCT_AMOUNT");
														$res = CCatalogStore::GetList(Array(),$arFilter,false,false,$arSelectFields);
														if ($arRes = $res->GetNext()) 
															$mNALICHIE = $arRes['PRODUCT_AMOUNT'];
														/*if($USER->IsAdmin()) {echo '<pre>'; print_r($arRes ); echo '</pre>';}*/?>
														 <?
														 /////////////////////2020_12_19//////////////////////////
														if (($arResult['PROPERTIES']['NALICHIE']['VALUE'] == 'В наличии') || ($arResult['PROPERTIES']['NALICHIE']['VALUE'] == '')) {
															?>
															<div class="nalichie_y"><div class="col-xs-6 col-sm-7 nalichie_y_e">В наличии</div> <div class="col-xs-6 col-sm-5 nalichie_y_e"><p><?=$mNALICHIE?> шт.</p></div></div>
															<?
																$isSIMILARPRODUCTS = false;
																$isH2SIMILARPRODUCTS = false;
														} else {
															if($arResult['PROPERTIES']['NALICHIE']['VALUE'] == 'Нет в наличии'){
															?>
															<div class="nalichie_n"><b>Нет в наличии</b></div>
															<?	
															}else {
																if($arResult['PROPERTIES']['NALICHIE']['VALUE'] == 'В наличии (мало)'){
																$isSIMILARPRODUCTS = false;
																$isH2SIMILARPRODUCTS = false;
																	?>
																	<div class="nalichie_y"><div class="col-xs-6 col-sm-7 nalichie_y_m">В наличии</div> <div class="col-xs-6 col-sm-5 nalichie_y_m"><p>мало</p></div></div>
																	
																	<?	
																	}
																else {
																	?>
																	<div class="nalichie_n"><b><?= $arResult['PROPERTIES']['NALICHIE']['VALUE']; ?></b>
																	</div><?
																}
															}
														}
														/////////////////////2020_12_19//////////////////////////
														?>
                                                <div data-entity="main-button-container" style="display: <?= ($textstyle) ?>;">
												
                                                    <div id="<?= $itemIds['BASKET_ACTIONS_ID'] ?>"
                                                         style="display: <?= ($actualItem['CAN_BUY'] ? '' : 'none') ?>;">
														 
                                                        <?
                                                        if ($showAddBtn) {
                                                            ?>
                                                            <div class="product-item-detail-info-container 9">

                                                                <a class="btn <?= $showButtonClassName ?> product-item-detail-buy-button"
                                                                   id="<?= $itemIds['ADD_BASKET_LINK'] ?>"
                                                                   href="javascript:void(0);">
                                                                    <span><?= $arParams['MESS_BTN_ADD_TO_BASKET'] ?></span>
                                                                </a>
                                                            </div>
                                                            <?
                                                        }

                                                        if ($showBuyBtn) {
                                                            ?>
															<div class="product-item-detail-info-container 110">
                                                                <a class="btn <?= $buyButtonClassName ?> product-item-detail-buy-button"
                                                                   id="<?= $itemIds['BUY_LINK'] ?>"
                                                                   href="javascript:void(0);">
                                                                    <span><?= $arParams['MESS_BTN_BUY'] ?></span>
                                                                </a>
                                                            </div>
															<?
                                                        }
                                                        ?>
															<!--?if (($arResult['PROPERTIES']['NALICHIE']['VALUE'] == 'В наличии') || ($arResult['PROPERTIES']['NALICHIE']['VALUE'] == '')) {?>
                                                            <div class="product-item-detail-info-container 110">
                                                                <a class="btn <!--?= $buyButtonClassName ?> product-item-detail-buy-button"
                                                                   id="<!--?= $itemIds['BUY_LINK'] ?>"
                                                                   href="javascript:void(0);">
                                                                    <span><!--?= $arParams['MESS_BTN_BUY'] ?></span>
                                                                </a>
                                                            </div>
                                                            <!--?
															} else {
															?>
															<div class="product-item-detail-info-container 112">
                                                        <a class="btn btn-link product-item-detail-buy-button"
                                                           id="<!--?= $itemIds['NOT_AVAILABLE_MESS'] ?>"
                                                           href="javascript:void(0)"
                                                           rel="nofollow"
                                                           style="display: <!--?= (!$actualItem['CAN_BUY'] ? '' : ';padding: 6px 12px 6px 1px;"') ?>;">
                                                            <!--?= $arParams['MESS_NOT_AVAILABLE'] ?>
                                                        </a>
                                                    </div>
															<!--?
															}
															
                                                        }
                                                        ?-->
                                                    </div>
                                                    <?
                                                    if ($showSubscribe) {
                                                        ?>
                                                        <div class="product-item-detail-info-container 11">
                                                            <?
                                                            $APPLICATION->IncludeComponent(
                                                                'bitrix:catalog.product.subscribe',
                                                                '',
                                                                array(
                                                                    'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
                                                                    'PRODUCT_ID' => $arResult['ID'],
                                                                    'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
                                                                    'BUTTON_CLASS' => 'btn btn-default product-item-detail-buy-button',
                                                                    'DEFAULT_DISPLAY' => !$actualItem['CAN_BUY'],
                                                                    'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
                                                                ),
                                                                $component,
                                                                array('HIDE_ICONS' => 'Y')
                                                            );
                                                            ?>
                                                        </div>
                                                        <?
                                                    }
                                                    ?>
                                                    <div class="product-item-detail-info-container 12">
                                                        <a class="btn btn-link product-item-detail-buy-button"
                                                           id="<?= $itemIds['NOT_AVAILABLE_MESS'] ?>"
                                                           href="javascript:void(0)"
                                                           rel="nofollow"
                                                           style="display: <?= (!$actualItem['CAN_BUY'] ? '' : 'none') ?>;">
                                                            <?= $arParams['MESS_NOT_AVAILABLE'] ?>
                                                        </a>
                                                    </div>
                                                </div>
                                                <?
                                                break;
                                        }
                                    }

                                    if ($arParams['DISPLAY_COMPARE']) {
                                        ?>
                                        <div class="product-item-detail-compare-container">
                                            <div class="product-item-detail-compare">
                                                <div class="checkbox">
                                                    <label id="<?= $itemIds['COMPARE_LINK'] ?>">
                                                        <input type="checkbox" data-entity="compare-checkbox">
                                                        <span data-entity="compare-title"><?= $arParams['MESS_BTN_COMPARE'] ?></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <?
                                    }
                                    ?>
                                    <!--?
                                    if (($arResult['PROPERTIES']['NALICHIE']['VALUE'] == 'В наличии') || ($arResult['PROPERTIES']['NALICHIE']['VALUE'] == '')) {
                                        ?>
                                        <div class="nalichie"><img src="/upload/iblock/icons/checkmark_green.png" alt="В наличии">В
                                            наличии
                                        </div><!--?
                                    } else {
										if($arResult['PROPERTIES']['NALICHIE']['VALUE'] == 'Нет в наличии'){
										?>
                                        <div class="nalichie"><img src="/upload/iblock/icons/checkmark_gray.png" alt="Нет в наличии">Нет в наличии
                                        </div><!--?	
										}else {
                                        ?>
                                        <!--div class="nalichie"><img
                                                src="/upload/iblock/icons/checkmark_gray.png"><!--?= $arResult['PROPERTIES']['NALICHIE']['VALUE']; ?>
                                        </div--><!--?
										/////////////////////2020_07_14//////////////////////////
											if($arResult['PROPERTIES']['NALICHIE']['VALUE'] == 'Под заказ, 1-2 дня'){
											?>
											<div class="nalichie"><img src="/upload/iblock/icons/checkmark_green_2.png" alt="Под заказ">Под заказ, 1-2 дня
											</div><!--?	
											}else {
											?>
											<div class="nalichie"><img
													src="/upload/iblock/icons/checkmark_gray.png" alt="Под заказ"><!--?= $arResult['PROPERTIES']['NALICHIE']['VALUE']; ?>
											</div><!--?
										}
									/////////////////////2020_07_14//////////////////////////
                                    }
									}
                                    ?-->
                                </div>

                                <!----------------------------------->
                            </div>
                            <!----------------------------------->

                            <div class="col-xs-12 col-md-5">
                                <!--div class="bx-worktime-prop">
								<? $APPLICATION->IncludeComponent("bitrix:main.include", "", array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_DIR . "include/element_info.php"
                                ),
                                    false,
                                    array(
                                        "ACTIVE_COMPONENT" => "N"
                                    )
                                ); ?>
							</div-->
                                <div class="bx-worktime-prop">
                                    <noindex>

                                        <?


                                        $deliveryFree = '';

                                        if ($arResult['ITEM_PRICES'][0]['PRICE'] > 35000) {
                                            $deliveryFree = '<span class="color_green"> бесплатно</span>';
                                        }
                                        if (($arResult['PROPERTIES']['NALICHIE']['VALUE'] === 'В наличии') || ($arResult['PROPERTIES']['NALICHIE']['VALUE'] == '')) {
                                            //$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/element_info.php"), false);
                                            ?>
                                            <?
                                            /* Установка русской локали */
                                            //$date = date("d F Y.",strtotime("2016-01-25"));
                                            //    $strEng = array("January","February","March","April","May","June","July","August","September","October","November","December");
                                            //    $strRu=array("Января","Февраля","Марта","Апреля","Мая","Июня","Июля","Августа","Сентября","Октября","Ноября","Декабря");
                                            //    echo str_replace($strEng,$strRu,$date);
                                            $dw = date("w", time());
                                            $needData = 'Y';
                                            if ($dw == 6) {//суббота
                                                $myDay = "+3 day";
                                            } elseif ($dw == 5)//пятница
                                            {
                                                $myDay = "+4 day";
                                            } else {
                                                $myDay = "+2 day";
                                                $needData = 'N';
                                            }
                                            if ($needData == 'Y') {
                                                $shares = date("d.m.Y", strtotime($myDay, time()));
                                                $date = date("d F", strtotime($shares));
                                                $strEng = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                                                $strRu = array("января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
                                                $share = str_replace($strEng, $strRu, $date);
                                            } else {
                                                $share = 'завтра';
                                            }

                                            ?>


                                            <div class="feature feature-icon-hover indent first">
                                                <span class="icon"><img
                                                            src="/upload/iblock/icons/delivery_1.png" alt="delivery"></span>
                                                <p class="no-margin ">
                                                    <!--a href="/about/delivery/" target="_blank"><!--?
                                                        echo "Доставим " . $share;
                                                        echo $deliveryFree; ?></a-->
                                                    <a href="/about/delivery/" target="_blank">Максимально быстрая доставка</a>
                                                </p>
                                            </div>
											<!--?if($USER->IsAdmin()) {echo '<pre>'; print_r($arResult['PROPERTIES']['NALICHIE']['VALUE']); echo '</pre>';}?-->
                                            <div class="feature feature-icon-hover indent indent">
                                                <span class="icon">
                                                    <img src="/upload/iblock/icons/credit-card-payment.png" alt="paying">
                                                </span>
                                                <p class="no-margin ">
                                                    <a href="/paying/" target="_blank">Удобные способы оплаты</a>
                                                </p>
                                            </div>
                                            <?if($arResult["COUNT_PRODUCT"] > 0):?>
                                                <div class="feature feature-icon-hover indent indent">
                                                    <span class="icon">
                                                        <img src="/upload/iblock/icons/buy-1.png" alt="buy">
                                                    </span>
                                                    <p class="no-margin">
                                                        Купили более <?=$arResult["COUNT_PRODUCT"];?> раз<?=($arResult["COUNT_PRODUCT"]==1?'а':'')?>
                                                    </p>
                                                </div>
                                            <?endif;?>
                                            <?
                                            if ($arResult['PROPERTIES']['description_action']['VALUE'] != '') {
                                                ?>
                                                <div class="feature feature-icon-hover indent indent">
                                                    <span class="icon"></span>
                                                    <p class="no-margin ">
                                                        <a href=""
                                                           target="_blank"><?= $arResult['PROPERTIES']['description_action']['VALUE'] ?></a>
                                                    </p>
                                                </div>
                                            <?
                                            } ?>
                                            <div class="feature-wrapper addto-border">
                                                &nbsp;
                                            </div>

                                        <?
                                        } elseif (($arResult['PROPERTIES']['NALICHIE']['VALUE'] === 'В наличии (мало)')) {
                                            //$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/element_info_pod_zakaz_1_2.php"), false);
                                            ?>
                                            <?
                                            /* Установка русской локали */
                                            //$date = date("d F Y.",strtotime("2016-01-25"));
                                            //    $strEng = array("January","February","March","April","May","June","July","August","September","October","November","December");
                                            //    $strRu=array("Января","Февраля","Марта","Апреля","Мая","Июня","Июля","Августа","Сентября","Октября","Ноября","Декабря");
                                            //    echo str_replace($strEng,$strRu,$date);

                                            /*для Под заказ 1-2 дня:
                                                если понедельник - доставка в пятницу
                                                если вторник - доставка в понедельник следующий
                                                если среда - доставка во вторник следующий
                                                если четверг - доставка в среду следующую
                                                пятница/суббота/воскресенье - доставка в четверг*/

                                            $dw = date("w", time());
                                            if ($dw == 6) {//суббота
                                                $myDay = "+5 day";
                                            } elseif ($dw == 5)//пятница
                                            {
                                                $myDay = "+6 day";
                                            } else {
                                                $myDay = "+4 day";
                                            }
                                            $shares = date("d.m.Y", strtotime($myDay, time()));
                                            $date = date("d F", strtotime($shares));
                                            $strEng = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                                            $strRu = array("января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
                                            $share = str_replace($strEng, $strRu, $date);

                                            ?>


                                            <div class="feature feature-icon-hover indent first">
                                                <span class="icon"><img
                                                            src="/upload/iblock/icons/delivery_1.png" alt="delivery"></span>
                                                <p class="no-margin ">
                                                    <!--a href="/about/delivery/" target="_blank"><!--?
                                                        echo "Доставим " . $share;
                                                        echo $deliveryFree; ?></a-->
													<a href="/about/delivery/" target="_blank">Условия доставки</a>

                                                </p>
                                            </div>
                                            <div class="feature feature-icon-hover indent indent">
                                                <span class="icon"><img
                                                            src="/upload/iblock/icons/credit-card-payment.png" alt="paying"></span>
                                                <p class="no-margin ">
                                                    <a href="/paying/" target="_blank">Удобные способы оплаты</a>
                                                </p>
                                            </div>
                                            <?
                                            if ($arResult['PROPERTIES']['description_action']['VALUE'] != '') {
                                                ?>
                                                <div class="feature feature-icon-hover indent indent">
                                                    <span class="icon"></span>
                                                    <p class="no-margin ">
                                                        <a href=""
                                                           target="_blank"><?= $arResult['PROPERTIES']['description_action']['VALUE'] ?></a>
                                                    </p>
                                                </div>
                                            <?
                                            } ?>
                                            <div class="feature-wrapper addto-border">
                                                &nbsp;
                                            </div>

                                            <?
                                        } elseif (($arResult['PROPERTIES']['NALICHIE']['VALUE'] == 'Под заказ, 3-5 дней')) {
                                            //$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/element_info_pod_zakaz_3_5.php"), false);
                                            ?>
                                            <?
                                            /* Установка русской локали */
                                            //$date = date("d F Y.",strtotime("2016-01-25"));
                                            //    $strEng = array("January","February","March","April","May","June","July","August","September","October","November","December");
                                            //    $strRu=array("Января","Февраля","Марта","Апреля","Мая","Июня","Июля","Августа","Сентября","Октября","Ноября","Декабря");
                                            //    echo str_replace($strEng,$strRu,$date);

                                            /*для Под заказ 3-5 дня:
                                                если понедельник - доставка в понедельник
                                                если вторник - доставка во вторник
                                                если среда - доставка в среду
                                                если четверг - доставка в четверг
                                                если пятница - доставка в пятницу
                                                если суббота/воскресенье - доставка в понедельник через неделю*/
                                            $dw = date("w", time());
                                            if ($dw == 6) {//суббота
                                                $myDay = "+9 day";
                                            } elseif ($dw == 0)//воскресенье
                                            {
                                                $myDay = "+8 day";
                                            } else {
                                                $myDay = "+7 day";
                                            }
                                            $shares = date("d.m.Y", strtotime($myDay, time()));
                                            $date = date("d F", strtotime($shares));
                                            $strEng = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                                            $strRu = array("января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
                                            $share = str_replace($strEng, $strRu, $date);

                                            ?>

                                            <div class="feature feature-icon-hover indent first">
                                                <span class="icon"><img
                                                            src="/upload/iblock/icons/delivery_1.png" alt="delivery"></span>
                                                <p class="no-margin ">
                                                    <!--a href="/about/delivery/" target="_blank"><!--?
                                                        echo "Доставим " . $share;
                                                        echo $deliveryFree; ?></a-->
													<a href="/about/delivery/" target="_blank">Условия доставки</a>
                                                    <!--a href="/about/delivery/" target="_blank">Максимально быстрая доставка</a-->
                                                </p>
                                            </div>
                                            <div class="feature feature-icon-hover indent indent">
                                                <span class="icon"><img
                                                            src="/upload/iblock/icons/credit-card-payment.png" alt="paying"></span>
                                                <p class="no-margin ">
                                                    <a href="/paying/" target="_blank">Удобные способы оплаты</a>
                                                </p>
                                            </div>
                                            <?
                                            if ($arResult['PROPERTIES']['description_action']['VALUE'] != '') {
                                                ?>
                                                <div class="feature feature-icon-hover indent indent">
                                                    <span class="icon"></span>
                                                    <p class="no-margin ">
                                                        <a href=""
                                                           target="_blank"><?= $arResult['PROPERTIES']['description_action']['VALUE'] ?></a>
                                                    </p>
                                                </div>
                                            <?
                                            } ?>
                                            <div class="feature-wrapper addto-border">
                                                &nbsp;
                                            </div>

                                            <?
                                        } elseif (($arResult['PROPERTIES']['NALICHIE']['VALUE'] == 'Нет в наличии')) {
                                            //$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/element_info_pod_zakaz.php"), false);
                                            ?>


                                            <div class="feature feature-icon-hover indent first">
                                                <span class="icon"><img
                                                            src="/upload/iblock/icons/delivery_1.png" alt="delivery"></span>
                                                <p class="no-margin ">
                                                    <!--a href="/about/delivery/"
                                                       target="_blank"><!--? echo "Срок поставки уточняйте."; ?></a-->
													<a href="/about/delivery/" target="_blank">Условия доставки</a>
                                                </p>
                                            </div>
                                            <div class="feature feature-icon-hover indent indent">
                                                <span class="icon"><img
                                                            src="/upload/iblock/icons/credit-card-payment.png" alt="paying"></span>
                                                <p class="no-margin ">
                                                    <a href="/paying/" target="_blank">Удобные способы оплаты</a>
                                                </p>
                                            </div>
                                            <? if ($arResult['PROPERTIES']['description_action']['VALUE'] != '') {
                                                ?>
                                                <div class="feature feature-icon-hover indent indent">
                                                    <span class="icon"></span>
                                                    <p class="no-margin ">
                                                        <a href=""
                                                           target="_blank"><?= $arResult['PROPERTIES']['description_action']['VALUE'] ?></a>
                                                    </p>
                                                </div>
                                            <? } ?>
                                            <div class="feature-wrapper addto-border">
                                                &nbsp;
                                            </div>

                                            <?
                                        } ?>
                                    </noindex>

                                </div>
                            </div>
                            <!--div class="col-xs-12 col-sm-5">
							<div class="bx-worktime-prop">
								<!--?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/info(1).php"), false);?>
						</div-->

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <?
                    if ($haveOffers) {
                        if ($arResult['OFFER_GROUP']) {
                            foreach ($arResult['OFFER_GROUP_VALUES'] as $offerId) {
                                ?>
                                <span id="<?= $itemIds['OFFER_GROUP'] . $offerId ?>" style="display: none;">
								<?
                                $APPLICATION->IncludeComponent(
                                    'bitrix:catalog.set.constructor',
                                    '.default',
                                    array(
                                        'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
                                        'IBLOCK_ID' => $arResult['OFFERS_IBLOCK'],
                                        'ELEMENT_ID' => $offerId,
                                        'PRICE_CODE' => $arParams['PRICE_CODE'],
                                        'BASKET_URL' => $arParams['BASKET_URL'],
                                        'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
                                        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                        'CACHE_TIME' => $arParams['CACHE_TIME'],
                                        'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                                        'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
                                        'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                                        'CURRENCY_ID' => $arParams['CURRENCY_ID']
                                    ),
                                    $component,
                                    array('HIDE_ICONS' => 'Y')
                                );
                                ?>
							</span>
                                <?
                            }
                        }
                    } else {
                        if ($arResult['MODULES']['catalog'] && $arResult['OFFER_GROUP']) {
                            $APPLICATION->IncludeComponent(
                                'bitrix:catalog.set.constructor',
                                '.default',
                                array(
                                    'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
                                    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                                    'ELEMENT_ID' => $arResult['ID'],
                                    'PRICE_CODE' => $arParams['PRICE_CODE'],
                                    'BASKET_URL' => $arParams['BASKET_URL'],
                                    'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                    'CACHE_TIME' => $arParams['CACHE_TIME'],
                                    'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                                    'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
                                    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                                    'CURRENCY_ID' => $arParams['CURRENCY_ID']
                                ),
                                $component,
                                array('HIDE_ICONS' => 'Y')
                            );
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="row" style="border-bottom: 1px solid #ededed;">
                <!--div class="col-sm-8 col-md-9"-->
                <div class="col-sm-10 col-md-12">
                    <div class="row" id="<?= $itemIds['TABS_ID'] ?>">
                        <div class="col-xs-12">
                            <div class="product-item-detail-tabs-container">
                                <ul class="product-item-detail-tabs-list">
                                    <? if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS']) { ?>
                                        <li class="product-item-detail-tab" data-entity="tab"
                                            data-value="properties">
                                            <a href="javascript:void(0);" class="product-item-detail-tab-link">
                                                <span><?= $arParams['MESS_PROPERTIES_TAB'] ?></span>
                                            </a>
                                        </li>
                                    <? } ?>
                                    <? if ($isSIMILARPRODUCTS) { ?>
                                        <li class="product-item-detail-tab active" data-entity="tab"
                                            data-value="similarproducts">
                                            <a href="javascript:void(0);" class="product-item-detail-tab-link">
                                                <span><?= GetMessage('CP_BCE_SIMILAR_PRODUCTS_TAB') ?></span>
                                            </a>
                                        </li>
                                    <? } ?>
                                    <? if ($showDescription) { ?>
                                        <li class="product-item-detail-tab" data-entity="tab" data-value="description">
                                            <a href="javascript:void(0);" class="product-item-detail-tab-link">
                                                <span><?= $arParams['MESS_DESCRIPTION_TAB'] ?></span>
                                            </a>
                                        </li>
                                    <? } ?>
                                    <!--/****************************************/-->

                                    <? if ($isVIDEOYOUTUBE) { ?>
                                        <li class="product-item-detail-tab" data-entity="tab" data-value="videoyoutube">
                                            <a href="javascript:void(0);" class="product-item-detail-tab-link">
                                                <span><?= GetMessage('CP_BCE_VIDEOYOUTUBE_TAB') ?></span>
                                            </a>
                                        </li>
                                    <? } ?>
                                    <? if ($isINSTRUCTIONS) { ?>
                                        <li class="product-item-detail-tab" data-entity="tab" data-value="instructions">
                                            <a href="javascript:void(0);" class="product-item-detail-tab-link">
                                                <span><?= $arParams['MESS_INSTRUCTIONS_TAB'] ?></span>
                                            </a>
                                        </li>
                                    <? } ?>
                                    <? if ($isCERTIFICATES) { ?>
                                        <li class="product-item-detail-tab" data-entity="tab" data-value="certificates">
                                            <a href="javascript:void(0);" class="product-item-detail-tab-link">
                                                <span><?= $arParams['MESS_CERTIFICATES_TAB'] ?></span>
                                            </a>
                                        </li>
                                    <? } ?>
                                    <? if ($isACCESSORIES) { ?>
                                        <li class="product-item-detail-tab" data-entity="tab" data-value="accessories">
                                            <a href="javascript:void(0);" class="product-item-detail-tab-link">
                                                <span><?= $arParams['MESS_ACCESSORIES_TAB'] ?></span>
                                            </a>
                                        </li>
                                    <? } ?>
                                    <? if ($isDIMENSIONS) { ?>
                                        <li class="product-item-detail-tab active" data-entity="tab"
                                            data-value="dimensions">
                                            <a href="javascript:void(0);" class="product-item-detail-tab-link">
                                                <span><?= GetMessage('CP_BCE_DIMENSIONS_TAB') ?></span>
                                            </a>
                                        </li>
                                    <? } ?>
                                    <? if ($isRELATEDPRODUCTS) { ?>
                                        <li class="product-item-detail-tab" data-entity="tab"
                                            data-value="relatedproducts">
                                            <a href="javascript:void(0);" class="product-item-detail-tab-link">
                                                <span><?= GetMessage('CP_BCE_RELATED_PRODUCTS_TAB') ?></span>
                                            </a>
                                        </li>
                                    <? } ?>
	<!--/****************************************/-->
                                    <? if ($arParams['USE_COMMENTS'] === 'Y') { ?>
                                        <li class="product-item-detail-tab" data-entity="tab" data-value="comments">
                                            <a href="javascript:void(0);" class="product-item-detail-tab-link">
                                                <span><?= $arParams['MESS_COMMENTS_TAB'] ?></span>
                                            </a>
                                        </li>
                                    <? } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="<?= $itemIds['TAB_CONTAINERS_ID'] ?>">
                        <div class="col-xs-12">
                            <? if ($isVIDEOYOUTUBE) { ?>
                                <div class="product-item-detail-tab-content" data-entity="tab-container"
                                     data-value="videoyoutube">
                                    <?
                                    /***************02_09_2019***************/
                                    if ($isH2SECTIONVIDEO) {
                                        $resH2SECTIONVIDEO = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_SECTION_VIDEO"));
                                        while ($obH2SECTIONVIDEO = $resH2SECTIONVIDEO->GetNext()) {
                                            if (!empty($obH2SECTIONVIDEO['VALUE'])) {
                                                echo '<h2>' . $obH2SECTIONVIDEO['VALUE'] . '</h2>';
                                            }
                                        }
                                    }
                                    /***************************************/
                                    $myresVIDEOYOUTUBE = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "VIDEO_YOUTUBE"));
                                    while ($obVIDEOYOUTUBE = $myresVIDEOYOUTUBE->GetNext()) {
                                        $tegs[] = $obVIDEOYOUTUBE['VALUE'];

                                        ?>
                                        <div class="col-xs-12 col-md-6">

                                            <?
                                            $APPLICATION->IncludeComponent(
                                                "bitrix:player",
                                                "",
                                                Array(
                                                    "ADVANCED_MODE_SETTINGS" => "N",
                                                    "AUTOSTART" => "N",
                                                    "AUTOSTART_ON_SCROLL" => "N",
                                                    "COMPOSITE_FRAME_MODE" => "A",
                                                    "COMPOSITE_FRAME_TYPE" => "AUTO",
                                                    "HEIGHT" => "300",
                                                    "MUTE" => "N",
                                                    "PATH" => $obVIDEOYOUTUBE['VALUE'],
                                                    "PLAYBACK_RATE" => "1",
                                                    "PLAYER_ID" => "",
                                                    "PLAYER_TYPE" => "auto",
                                                    "PRELOAD" => "N",
                                                    "REPEAT" => "none",
                                                    "SHOW_CONTROLS" => "Y",
                                                    "SIZE_TYPE" => "fluid",
                                                    "SKIN" => "",
                                                    "SKIN_PATH" => "/bitrix/components/bitrix/player/videojs/skins",
                                                    "START_TIME" => "0",
                                                    "VOLUME" => "80",
                                                    "WIDTH" => "400"
                                                )
                                            ); ?>
                                        </div>

                                        <?
                                        /*$pic=CFile::GetFileArray($obPHOTO_ACCESSORIES['VALUE']);
												echo'<img src="'.$pic['SRC'].'">';
												echo $arProperty["DISPLAY_VALUE"];*/
                                    }
                                    /*if($obVIDEOYOUTUBE = $myresVIDEOYOUTUBE->Fetch()) $arFile = CFile::GetFileArray($obVIDEOYOUTUBE["VALUE"]);*/


                                    //=$obVideoYoutube["VALUE"]?>

                                </div>
                            <? } ?>
                            <? if ($showDescription) { ?>
                                <div class="product-item-detail-tab-content active" data-entity="tab-container"
                                     data-value="description" itemprop="description">
                                    <?
                                    /***************20_08_2019***************/
                                    if ($isH2DESCRIPTION) {
                                        $resH2DESCRIPTION = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_SECTION_DESCRIPTION"));
                                        while ($obH2DESCRIPTION = $resH2DESCRIPTION->GetNext()) {
                                            if (!empty($obH2DESCRIPTION['VALUE'])) {
                                                echo '<h2>' . $obH2DESCRIPTION['VALUE'] . '</h2>';
                                            }
                                        }
                                    }
                                    /***************************************/
                                    if (
                                        $arResult['PREVIEW_TEXT'] != ''
                                        && (
                                            $arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'S'
                                            || ($arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'E' && $arResult['DETAIL_TEXT'] == '')
                                        )
                                    ) {
                                        echo $arResult['PREVIEW_TEXT_TYPE'] === 'html' ? $arResult['PREVIEW_TEXT'] : '<p>' . $arResult['PREVIEW_TEXT'] . '</p>';
                                    }

                                    if ($arResult['DETAIL_TEXT'] != '') {
                                        echo $arResult['DETAIL_TEXT_TYPE'] === 'html' ? $arResult['DETAIL_TEXT'] : '<p>' . $arResult['DETAIL_TEXT'] . '</p>';
                                    }
                                    ?>
                                </div>
                                <?
                            } ?>
                            <!-- ================================================================================================================= -->
                            <? if ($isSIMILARPRODUCTS) { ?>
                                <div class="product-item-detail-tab-content" data-entity="tab-container"
                                     data-value="similarproducts">
                                    <? /***************20_08_2019***************/
                                    if ($resH2SIMILARPRODUCTS) {
                                        $resH2SIMILARPRODUCTS = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_SECTION_SIMILAR_PRODUCTS"));
                                        while ($obH2SIMILARPRODUCTS = $resH2SIMILARPRODUCTS->GetNext()) {
                                            if (!empty($obH2SIMILARPRODUCTS['VALUE'])) {
                                                echo '<h2>' . $obH2SIMILARPRODUCTS['VALUE'] . '</h2>';
                                            }
                                        }
                                    }
                                    /***************************************/ ?>
                                    <? $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_*');
                                    $arFilter = array('IBLOCK_ID' => IntVal($arParams["IBLOCK_ID"]),'ID' => $arResult['ID'], 'ACTIVE' => 'Y', "!PROPERTY_SIMILAR_PRODUCTS" => false);
                                    $res = CIBlockElement::GetList(
                                        array("PROPERTY_SIMILAR_PRODUCTS" => "ASC"),
                                        $arFilter,
                                        false,
                                        array('nPageSize' => 1000),
                                        $arSelect
                                    );
                                    while ($ob = $res->GetNextElement()) {
                                        $arProps = $ob->GetProperties();
                                        $arRelatID = $arProps['SIMILAR_PRODUCTS']['VALUE'];
                                        $GLOBALS['arRelatFilter'] = array('ID' => $arRelatID);
                                    } ?>
                                    <? $APPLICATION->IncludeComponent(
                                        "bitrix:catalog.section",
                                        ".default",
                                        array(
                                            "ACTION_VARIABLE" => "action",
                                            "ADD_PICT_PROP" => "-",
                                            "ADD_PROPERTIES_TO_BASKET" => "Y",
                                            "ADD_SECTIONS_CHAIN" => "N",
                                            "ADD_TO_BASKET_ACTION" => "ADD",
                                            "AJAX_MODE" => "N",
                                            "AJAX_OPTION_ADDITIONAL" => "",
                                            "AJAX_OPTION_HISTORY" => "N",
                                            "AJAX_OPTION_JUMP" => "N",
                                            "AJAX_OPTION_STYLE" => "Y",
                                            "BACKGROUND_IMAGE" => "-",
                                            "BASKET_URL" => "/personal/basket.php",
                                            "BROWSER_TITLE" => "-",
                                            "CACHE_FILTER" => "N",
                                            "CACHE_GROUPS" => "Y",
                                            "CACHE_TIME" => "3600",
                                            "CACHE_TYPE" => "A",
                                            "COMPATIBLE_MODE" => "Y",
                                            "COMPOSITE_FRAME_MODE" => "A",
                                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                                            "CONVERT_CURRENCY" => "Y",
                                            "CURRENCY_ID" => "RUB",
                                            "CUSTOM_FILTER" => "",
                                            "DETAIL_URL" => "",
                                            "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                                            "DISPLAY_BOTTOM_PAGER" => "Y",
                                            "DISPLAY_COMPARE" => "N",
                                            "DISPLAY_TOP_PAGER" => "N",
                                            "ELEMENT_SORT_FIELD" => "sort",
                                            "ELEMENT_SORT_FIELD2" => "id",
                                            "ELEMENT_SORT_ORDER" => "asc",
                                            "ELEMENT_SORT_ORDER2" => "desc",
                                            "ENLARGE_PRODUCT" => "STRICT",
                                            "FILTER_NAME" => "arRelatFilter",
                                            "HIDE_NOT_AVAILABLE" => "N",
                                            "HIDE_NOT_AVAILABLE_OFFERS" => "N",
                                            "IBLOCK_ID" => "7",
                                            "IBLOCK_TYPE" => "1c_catalog",
                                            "INCLUDE_SUBSECTIONS" => "Y",
                                            "LABEL_PROP" => array(),
                                            "LAZY_LOAD" => "N",
                                            "LINE_ELEMENT_COUNT" => "3",
                                            "LOAD_ON_SCROLL" => "N",
                                            "MESSAGE_404" => "",
                                            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
                                            "MESS_BTN_BUY" => "Купить",
                                            "MESS_BTN_DETAIL" => "Подробнее",
                                            "MESS_BTN_SUBSCRIBE" => "Подписаться",
                                            "MESS_NOT_AVAILABLE" => "Нет в наличии",
                                            "META_DESCRIPTION" => "-",
                                            "META_KEYWORDS" => "-",
                                            "OFFERS_CART_PROPERTIES" => array(),
                                            "OFFERS_FIELD_CODE" => array(
                                                0 => "",
                                                1 => "",
                                            ),
                                            "OFFERS_LIMIT" => "5",
                                            "OFFERS_PROPERTY_CODE" => array(
                                                0 => "",
                                                1 => "",
                                            ),
                                            "OFFERS_SORT_FIELD" => "sort",
                                            "OFFERS_SORT_FIELD2" => "id",
                                            "OFFERS_SORT_ORDER" => "asc",
                                            "OFFERS_SORT_ORDER2" => "desc",
                                            "PAGER_BASE_LINK_ENABLE" => "N",
                                            "PAGER_DESC_NUMBERING" => "N",
                                            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                            "PAGER_SHOW_ALL" => "N",
                                            "PAGER_SHOW_ALWAYS" => "N",
                                            "PAGER_TEMPLATE" => ".default",
                                            "PAGER_TITLE" => "Товары",
                                            "PAGE_ELEMENT_COUNT" => "12",
                                            "PARTIAL_PRODUCT_PROPERTIES" => "N",
                                            "PRICE_CODE" => array(
                                                0 => "BASE",
                                                1 => "Интернет-магазин",
                                                2 => "Розничные продажи (EUR)",
                                            ),
                                            "PRICE_VAT_INCLUDE" => "Y",
                                            "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
                                            "PRODUCT_DISPLAY_MODE" => "N",
                                            "PRODUCT_ID_VARIABLE" => "id",
                                            "PRODUCT_PROPERTIES" => array(),
                                            "PRODUCT_PROPS_VARIABLE" => "prop",
                                            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                                            "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false}]",
                                            "PRODUCT_SUBSCRIPTION" => "Y",
                                            "PROPERTY_CODE" => array(
                                                0 => "",
                                                1 => "",
                                            ),
                                            "PROPERTY_CODE_MOBILE" => array(),
                                            "RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
                                            "RCM_TYPE" => "personal",
                                            "SECTION_CODE" => "",
                                            "SECTION_ID" => " ={\$_REQUEST[\"SECTION_ID\"]}",
                                            "SECTION_ID_VARIABLE" => "SECTION_ID",
                                            "SECTION_URL" => "",
                                            "SECTION_USER_FIELDS" => array(
                                                0 => "",
                                                1 => "",
                                            ),
                                            "SEF_MODE" => "N",
                                            "SET_BROWSER_TITLE" => "Y",
                                            "SET_LAST_MODIFIED" => "N",
                                            "SET_META_DESCRIPTION" => "Y",
                                            "SET_META_KEYWORDS" => "Y",
                                            "SET_STATUS_404" => "N",
                                            "SET_TITLE" => "Y",
                                            "SHOW_404" => "N",
                                            "SHOW_ALL_WO_SECTION" => "Y",
                                            "SHOW_CLOSE_POPUP" => "N",
                                            "SHOW_DISCOUNT_PERCENT" => "N",
                                            "SHOW_FROM_SECTION" => "N",
                                            "SHOW_MAX_QUANTITY" => "N",
                                            "SHOW_OLD_PRICE" => "N",
                                            "SHOW_PRICE_COUNT" => "1",
                                            "SHOW_SLIDER" => "Y",
                                            "SLIDER_INTERVAL" => "3000",
                                            "SLIDER_PROGRESS" => "N",
                                            "TEMPLATE_THEME" => "blue",
                                            "USE_ENHANCED_ECOMMERCE" => "N",
                                            "USE_MAIN_ELEMENT_SECTION" => "N",
                                            "USE_PRICE_COUNT" => "N",
                                            "USE_PRODUCT_QUANTITY" => "N",
                                            "COMPONENT_TEMPLATE" => ".default"
                                        ),
                                        false
                                    ); ?>
                                </div>
                            <? } ?>
                            <? if ($isACCESSORIES) { ?>
                                <div class="product-item-detail-tab-content" data-entity="tab-container"
                                     data-value="accessories">
                                    <div class="col-xs-12 col-sm-5">
                                        <? $myresPHOTO_ACCESSORIES = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "PHOTO_ACCESSORIES"));
                                        //$i = 1;
                                        while ($obPHOTO_ACCESSORIES = $myresPHOTO_ACCESSORIES->GetNext()) {
                                            $tegs[] = $obPHOTO_ACCESSORIES['VALUE'];
                                            //if($USER->IsAdmin()) {echo '<pre>'; print_r($obPHOTO_ACCESSORIES['VALUE']); echo '</pre>';}
                                            $pic = CFile::GetFileArray($obPHOTO_ACCESSORIES['VALUE']);
                                            echo '<img src="' . $pic['SRC'] . '"alt="'.$alt . '">';
                                            echo $arProperty["DISPLAY_VALUE"];
                                        }
                                        ?>
                                    </div>
                                    <div class="col-xs-12 col-sm-7">
                                        <?
                                        $resACCESSORIESNUM = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "ACCESSORIES_NUM"));
                                        while ($obACCESSORIESNUM = $resACCESSORIESNUM->GetNext()) {
                                            $tegsNum[] = $obACCESSORIESNUM['VALUE'];
                                        }
                                        $n = count($tegsNum);
                                        /***************20_08_2019***************/
                                        if ($isH2ACCESSORIES) {
                                            $resH2ACCESSORIES = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_SECTION_ACCESSORIES"));
                                            while ($obH2ACCESSORIES = $resH2ACCESSORIES->GetNext()) {
                                                if (!empty($obH2ACCESSORIES['VALUE'])) {
                                                    echo '<h2>' . $obH2ACCESSORIES['VALUE'] . '</h2>';
                                                }
                                            }
                                        }
                                        /***************************************/
                                        $resACCESSORIES = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "ACCESSORIES"));

                                        $i = 1;
                                        while ($obACCESSORIES = $resACCESSORIES->GetNext()) {
                                        $tegs[] = $obACCESSORIES['VALUE'];
                                        //if($USER->IsAdmin()) {echo '<pre>'; print_r($tegs); echo '</pre>';}
                                        $resEl = CIBlockElement::GetByID($obACCESSORIES['VALUE']);
                                        if ($ar_resEl = $resEl->GetNext())
                                            // $val - переменная где Вы указали ID элемента инфоблока
                                            $resElement = CIBlockElement::GetByID($ar_resEl['ID']);
                                        if ($ar_resElement = $resElement->GetNext())
                                            if ($i <= $n) {
                                                $j = $tegsNum[$i - 1];
                                            } else {
                                                $j = $i;
                                            }
                                        //echo $ar_resElement['DETAIL_PAGE_URL']; <a href="/sredstva_dlya_ukhoda_za_vodoy/">
                                        echo $j . '. ';
                                        echo '<a href="' . $ar_resElement['DETAIL_PAGE_URL'] . '"target="_blank" style="color: #256aa3;text-decoration: underline;">' . $ar_resEl['NAME'] . '</a>';
                                        $i++;
                                        //if($USER->IsAdmin()) {echo '<pre>'; print_r($ar_resElement['DETAIL_PAGE_URL']); echo '</pre>';}
                                        ?></br><? } ?>
                                    </div>
                                </div>
                            <? } ?>
                            <? if ($isINSTRUCTIONS) { ?>
                                <div class="product-item-detail-tab-content" data-entity="tab-container"
                                     data-value="instructions">
                                    <?
                                    /***************02_09_2019***************/
                                    if ($isH2INSTRUCTIONS) {
                                        $resH2INSTRUCTIONS = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_INSTRUCTIONS"));
                                        while ($obH2INSTRUCTIONS = $resH2INSTRUCTIONS->GetNext()) {
                                            if (!empty($obH2INSTRUCTIONS['VALUE'])) {
                                                echo '<h2>' . $obH2INSTRUCTIONS['VALUE'] . '</h2>';
                                            }
                                        }
                                    }
                                    /***************************************/
                                    $myresINSTRUCTIONS = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "INSTRUCTIONS"));
                                    if ($obINSTRUCTIONS = $myresINSTRUCTIONS->Fetch()) $arFile = CFile::GetFileArray($obINSTRUCTIONS["VALUE"]);
                                    //if($USER->IsAdmin()) {echo '<pre>'; print_r($arFile); echo '</pre>';}
                                    if ($arFile) { ?>
                                        <a
                                                href="<?= $arFile['SRC'] ?>"
                                                target="_blank"
                                                title="<?= $arFile['ORIGINAL_NAME'] ?>">
                                            <div class="row">
                                                <!--div class = "col-xs-2 col-sm-1 text-center"><i class="type-files ico-pdf"></i></div-->
                                                <div class="col-xs-10">
                                                    <strong style="color: #256aa3;text-decoration: underline;">
                                                        Инструкция для <?= $name ?>
                                                        <span>
																			(<?= round((CFile::FormatSize($arFile['FILE_SIZE']) / 1024), 2) ?> Мб)
																		</span>
                                                    </strong>
                                                </div>
                                            </div>
                                        </a>
                                    <? } ?>
                                </div>
                            <? } ?>
                            <? if ($isCERTIFICATES) { ?>
                                <div class="product-item-detail-tab-content" data-entity="tab-container"
                                     data-value="certificates">
                                    <?
                                    /***************02_09_2019***************/
                                    if ($isH2CERTIFICATES) {
                                        $resH2CERTIFICATES = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_CERTIFICATES"));
                                        while ($obH2CERTIFICATES = $resH2CERTIFICATES->GetNext()) {
                                            if (!empty($obH2CERTIFICATES['VALUE'])) {
                                                echo '<h2>' . $obH2CERTIFICATES['VALUE'] . '</h2>';
                                            }
                                        }
                                    }
                                    /***************************************/
                                    $myresCERTIFICATES = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "CERTIFICATES"));
                                    if ($obCERTIFICATES = $myresCERTIFICATES->Fetch()) $arFile = CFile::GetFileArray($obCERTIFICATES["VALUE"]);
                                    if ($arFile) { ?>
                                        <div class="row">
                                            <!--div class = "col-xs-2 col-sm-1 text-center"><i class="type-files ico-pdf"></i></div-->
                                            <div class="col-xs-10  col-sm-8">
                                                <? //if($USER->IsAdmin()) {echo '<pre>'; print_r($arFile); echo '</pre>';}
                                                ?>
                                                <a download="<?= $arFile['ORIGINAL_NAME'] ?>"
                                                   href="<?= $arFile['SRC'] ?>" target="_blank" title="Скачать"
                                                   style="color: #256aa3;text-decoration: underline;">
                                                    <strong>Скачать сертификат соответствия для <?= $name ?></strong>
                                                </a>
                                                <span>
																(<?= round((CFile::FormatSize($arFile['FILE_SIZE']) / 1024), 2) ?> Мб)
															</span>
                                            </div>
                                        </div>
                                    <? } ?>
                                </div>
                            <? } ?>
                            <? if ($isDIMENSIONS) { ?>
                                <div class="product-item-detail-tab-content" data-entity="tab-container"
                                     data-value="dimensions">
                                    <? /***************20_08_2019***************/
                                    if ($isH2DIMENSIONS) {
                                        $resH2DIMENSIONS = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_SECTION_DIMENSIONS"));
                                        while ($obH2DIMENSIONS = $resH2DIMENSIONS->GetNext()) {
                                            if (!empty($obH2DIMENSIONS['VALUE'])) {
                                                echo '<h2>' . $obH2DIMENSIONS['VALUE'] . '</h2>';
                                            }
                                        }
                                    }
                                    /***************************************/ ?>
                                    <? $resDIMENSIONS = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "DIMENSIONS"));
                                    while ($obDIMENSIONS = $resDIMENSIONS->GetNext()) {
                                        $picDIMENSIONS = CFile::GetFileArray($obDIMENSIONS['VALUE']);
                                        echo '<img src="' . $picDIMENSIONS['SRC'] . '" alt="'.$alt . '" style="margin-left: 10%; max-width: 75%;">';
                                    }
                                    ?>
                                </div>
                            <? } ?>
                            <? if ($isRELATEDPRODUCTS) { ?>
                                <div class="product-item-detail-tab-content" data-entity="tab-container"
                                     data-value="relatedproducts">
                                    <? /***************20_08_2019***************/
                                    if ($resH2RELATEDPRODUCTS) {
                                        $resH2RELATEDPRODUCTS = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_SECTION_RELATED_PRODUCTS"));
                                        while ($obH2RELATEDPRODUCTS = $resH2RELATEDPRODUCTS->GetNext()) {
                                            if (!empty($obH2RELATEDPRODUCTS['VALUE'])) {
                                                echo '<h2>' . $obH2RELATEDPRODUCTS['VALUE'] . '</h2>';
                                            }
                                        }
                                    }
                                    /***************************************/ ?>
                                    <? $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_*');
                                    $arFilter = array('IBLOCK_ID' => IntVal($arParams["IBLOCK_ID"]),'ID' => $arResult['ID'], 'ACTIVE' => 'Y', "!PROPERTY_RELATED_PRODUCTS" => false);
									//if($USER->IsAdmin()) {echo '<pre>'; print_r($arFilter); echo '</pre>';}
                                    $res = CIBlockElement::GetList(
                                        array("PROPERTY_RELATED_PRODUCTS" => "ASC"),
                                        $arFilter,
                                        false,
                                        array('nPageSize' => 1000),
                                        $arSelect
                                    );
                                    while ($ob = $res->GetNextElement()) {
                                        $arProps = $ob->GetProperties();
                                        $arRelatID = $arProps['RELATED_PRODUCTS']['VALUE'];
                                        $GLOBALS['arRelatFilter'] = array('ID' => $arRelatID);
										//if($USER->IsAdmin()) {echo '<pre>'; print_r($arProps['RELATED_PRODUCTS']['VALUE']); echo '</pre>';}
                                    } ?>
                                    <? $APPLICATION->IncludeComponent(
                                        "bitrix:catalog.section",
                                        ".default",
                                        array(
                                            "ACTION_VARIABLE" => "action",
                                            "ADD_PICT_PROP" => "-",
                                            "ADD_PROPERTIES_TO_BASKET" => "Y",
                                            "ADD_SECTIONS_CHAIN" => "N",
                                            "ADD_TO_BASKET_ACTION" => "ADD",
                                            "AJAX_MODE" => "N",
                                            "AJAX_OPTION_ADDITIONAL" => "",
                                            "AJAX_OPTION_HISTORY" => "N",
                                            "AJAX_OPTION_JUMP" => "N",
                                            "AJAX_OPTION_STYLE" => "Y",
                                            "BACKGROUND_IMAGE" => "-",
                                            "BASKET_URL" => "/personal/basket.php",
                                            "BROWSER_TITLE" => "-",
                                            "CACHE_FILTER" => "N",
                                            "CACHE_GROUPS" => "Y",
                                            "CACHE_TIME" => "3600",
                                            "CACHE_TYPE" => "A",
                                            "COMPATIBLE_MODE" => "Y",
                                            "COMPOSITE_FRAME_MODE" => "A",
                                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                                            "CONVERT_CURRENCY" => "Y",
                                            "CURRENCY_ID" => "RUB",
                                            "CUSTOM_FILTER" => "",
                                            "DETAIL_URL" => "",
                                            "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                                            "DISPLAY_BOTTOM_PAGER" => "Y",
                                            "DISPLAY_COMPARE" => "N",
                                            "DISPLAY_TOP_PAGER" => "N",
                                            "ELEMENT_SORT_FIELD" => "sort",
                                            "ELEMENT_SORT_FIELD2" => "id",
                                            "ELEMENT_SORT_ORDER" => "asc",
                                            "ELEMENT_SORT_ORDER2" => "desc",
                                            "ENLARGE_PRODUCT" => "STRICT",
                                            "FILTER_NAME" => "arRelatFilter",
                                            "HIDE_NOT_AVAILABLE" => "N",
                                            "HIDE_NOT_AVAILABLE_OFFERS" => "N",
                                            "IBLOCK_ID" => "7",
                                            "IBLOCK_TYPE" => "1c_catalog",
                                            "INCLUDE_SUBSECTIONS" => "Y",
                                            "LABEL_PROP" => array(),
                                            "LAZY_LOAD" => "N",
                                            "LINE_ELEMENT_COUNT" => "3",
                                            "LOAD_ON_SCROLL" => "N",
                                            "MESSAGE_404" => "",
                                            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
                                            "MESS_BTN_BUY" => "Купить",
                                            "MESS_BTN_DETAIL" => "Подробнее",
                                            "MESS_BTN_SUBSCRIBE" => "Подписаться",
                                            "MESS_NOT_AVAILABLE" => "Нет в наличии",
                                            "META_DESCRIPTION" => "-",
                                            "META_KEYWORDS" => "-",
                                            "OFFERS_CART_PROPERTIES" => array(),
                                            "OFFERS_FIELD_CODE" => array(
                                                0 => "",
                                                1 => "",
                                            ),
                                            "OFFERS_LIMIT" => "5",
                                            "OFFERS_PROPERTY_CODE" => array(
                                                0 => "",
                                                1 => "",
                                            ),
                                            "OFFERS_SORT_FIELD" => "sort",
                                            "OFFERS_SORT_FIELD2" => "id",
                                            "OFFERS_SORT_ORDER" => "asc",
                                            "OFFERS_SORT_ORDER2" => "desc",
                                            "PAGER_BASE_LINK_ENABLE" => "N",
                                            "PAGER_DESC_NUMBERING" => "N",
                                            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                            "PAGER_SHOW_ALL" => "N",
                                            "PAGER_SHOW_ALWAYS" => "N",
                                            "PAGER_TEMPLATE" => ".default",
                                            "PAGER_TITLE" => "Товары",
                                            "PAGE_ELEMENT_COUNT" => "12",
                                            "PARTIAL_PRODUCT_PROPERTIES" => "N",
                                            "PRICE_CODE" => array(
                                                0 => "BASE",
                                                1 => "Интернет-магазин",
                                                2 => "Розничные продажи (EUR)",
                                            ),
                                            "PRICE_VAT_INCLUDE" => "Y",
                                            "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
                                            "PRODUCT_DISPLAY_MODE" => "N",
                                            "PRODUCT_ID_VARIABLE" => "id",
                                            "PRODUCT_PROPERTIES" => array(),
                                            "PRODUCT_PROPS_VARIABLE" => "prop",
                                            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                                            "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false}]",
                                            "PRODUCT_SUBSCRIPTION" => "Y",
                                            "PROPERTY_CODE" => array(
                                                0 => "",
                                                1 => "",
                                            ),
                                            "PROPERTY_CODE_MOBILE" => array(),
                                            "RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
                                            "RCM_TYPE" => "personal",
                                            "SECTION_CODE" => "",
                                            "SECTION_ID" => " ={\$_REQUEST[\"SECTION_ID\"]}",
                                            "SECTION_ID_VARIABLE" => "SECTION_ID",
                                            "SECTION_URL" => "",
                                            "SECTION_USER_FIELDS" => array(
                                                0 => "",
                                                1 => "",
                                            ),
                                            "SEF_MODE" => "N",
                                            "SET_BROWSER_TITLE" => "Y",
                                            "SET_LAST_MODIFIED" => "N",
                                            "SET_META_DESCRIPTION" => "Y",
                                            "SET_META_KEYWORDS" => "Y",
                                            "SET_STATUS_404" => "N",
                                            "SET_TITLE" => "Y",
                                            "SHOW_404" => "N",
                                            "SHOW_ALL_WO_SECTION" => "Y",
                                            "SHOW_CLOSE_POPUP" => "N",
                                            "SHOW_DISCOUNT_PERCENT" => "N",
                                            "SHOW_FROM_SECTION" => "N",
                                            "SHOW_MAX_QUANTITY" => "N",
                                            "SHOW_OLD_PRICE" => "N",
                                            "SHOW_PRICE_COUNT" => "1",
                                            "SHOW_SLIDER" => "Y",
                                            "SLIDER_INTERVAL" => "3000",
                                            "SLIDER_PROGRESS" => "N",
                                            "TEMPLATE_THEME" => "blue",
                                            "USE_ENHANCED_ECOMMERCE" => "N",
                                            "USE_MAIN_ELEMENT_SECTION" => "N",
                                            "USE_PRICE_COUNT" => "N",
                                            "USE_PRODUCT_QUANTITY" => "N",
                                            "COMPONENT_TEMPLATE" => ".default"
                                        ),
                                        false
                                    ); ?>
                                </div>
                            <? } ?>
                            <!-- ================================================================================================================= -->
                            <? if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS']) { ?>
                                <div class="product-item-detail-tab-content" data-entity="tab-container"
                                     data-value="properties">
                                    <? /***************20_08_2019***************/

                                    if ($isH2ATTRIBUTES) {
                                        $resH2ATTRIBUTES = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['ID'], array("sort" => "asc"), Array("CODE" => "SUBTITLE_SECTION_ATTRIBUTES"));
                                        while ($obH2ATTRIBUTES = $resH2ATTRIBUTES->GetNext()) {
                                            if (!empty($obH2ATTRIBUTES['VALUE'])) {
                                                echo '<h2>' . $obH2ATTRIBUTES['VALUE'] . '</h2>';
                                            }
                                        }
                                    }
                                    /***************************************/ ?>
                                    <? if (!empty($arResult['DISPLAY_PROPERTIES'])) { ?>
                                        <dl class="product-item-detail-properties">
                                            <? foreach ($arResult['DISPLAY_PROPERTIES'] as $property) { ?>
                                                <dt><?= $property['NAME'] ?></dt>
                                                <dd><?= (
                                                    is_array($property['DISPLAY_VALUE'])
                                                        ? implode(' / ', $property['DISPLAY_VALUE'])
                                                        : $property['DISPLAY_VALUE']
                                                    ) ?>
                                                </dd>
                                            <? }
                                            unset($property);
                                            ?>
                                        </dl>
                                    <? } ?>
                                    <? if ($arResult['SHOW_OFFERS_PROPS']) { ?>
                                        <dl class="product-item-detail-properties"
                                            id="<?= $itemIds['DISPLAY_PROP_DIV'] ?>"></dl>
                                    <? } ?>
                                </div>
                            <? } ?>
                            <? if ($arParams['USE_COMMENTS'] === 'Y') { ?>
                                <div class="product-item-detail-tab-content" data-entity="tab-container"
                                     data-value="comments" style="display: none;">
                                    <?
                                    $componentCommentsParams = array(
                                        'ELEMENT_ID' => $arResult['ID'],
                                        'ELEMENT_CODE' => '',
                                        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                                        'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
                                        'URL_TO_COMMENT' => '',
                                        'WIDTH' => '',
                                        'COMMENTS_COUNT' => '5',
                                        'BLOG_USE' => $arParams['BLOG_USE'],
                                        'FB_USE' => $arParams['FB_USE'],
                                        'FB_APP_ID' => $arParams['FB_APP_ID'],
                                        'VK_USE' => $arParams['VK_USE'],
                                        'VK_API_ID' => $arParams['VK_API_ID'],
                                        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                        'CACHE_TIME' => $arParams['CACHE_TIME'],
                                        'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                                        'BLOG_TITLE' => '',
                                        'BLOG_URL' => $arParams['BLOG_URL'],
                                        'PATH_TO_SMILE' => '',
                                        'EMAIL_NOTIFY' => $arParams['BLOG_EMAIL_NOTIFY'],
                                        'AJAX_POST' => 'Y',
                                        'SHOW_SPAM' => 'Y',
                                        'SHOW_RATING' => 'N',
                                        'FB_TITLE' => '',
                                        'FB_USER_ADMIN_ID' => '',
                                        'FB_COLORSCHEME' => 'light',
                                        'FB_ORDER_BY' => 'reverse_time',
                                        'VK_TITLE' => '',
                                        'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME']
                                    );
                                    if (isset($arParams["USER_CONSENT"]))
                                        $componentCommentsParams["USER_CONSENT"] = $arParams["USER_CONSENT"];
                                    if (isset($arParams["USER_CONSENT_ID"]))
                                        $componentCommentsParams["USER_CONSENT_ID"] = $arParams["USER_CONSENT_ID"];
                                    if (isset($arParams["USER_CONSENT_IS_CHECKED"]))
                                        $componentCommentsParams["USER_CONSENT_IS_CHECKED"] = $arParams["USER_CONSENT_IS_CHECKED"];
                                    if (isset($arParams["USER_CONSENT_IS_LOADED"]))
                                        $componentCommentsParams["USER_CONSENT_IS_LOADED"] = $arParams["USER_CONSENT_IS_LOADED"];
                                    $APPLICATION->IncludeComponent(
                                        'bitrix:catalog.comments',
                                        '',
                                        $componentCommentsParams,
                                        $component,
                                        array('HIDE_ICONS' => 'Y')
                                    );
                                    ?>
                                </div>
                            <? } ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-md-3">
                    <div>
                        <?
                        if ($arParams['BRAND_USE'] === 'Y') {
                            $APPLICATION->IncludeComponent(
                                'bitrix:catalog.brandblock',
                                '.default',
                                array(
                                    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                                    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                                    'ELEMENT_ID' => $arResult['ID'],
                                    'ELEMENT_CODE' => '',
                                    'PROP_CODE' => $arParams['BRAND_PROP_CODE'],
                                    'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                    'CACHE_TIME' => $arParams['CACHE_TIME'],
                                    'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                                    'WIDTH' => '',
                                    'HEIGHT' => ''
                                ),
                                $component,
                                array('HIDE_ICONS' => 'Y')
                            );
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <?
                    if ($arResult['CATALOG'] && $actualItem['CAN_BUY'] && \Bitrix\Main\ModuleManager::isModuleInstalled('sale')) {
                        $APPLICATION->IncludeComponent(
                            'bitrix:sale.prediction.product.detail',
                            '.default',
                            array(
                                'BUTTON_ID' => $showBuyBtn ? $itemIds['BUY_LINK'] : $itemIds['ADD_BASKET_LINK'],
                                'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
                                'POTENTIAL_PRODUCT_TO_BUY' => array(
                                    'ID' => isset($arResult['ID']) ? $arResult['ID'] : null,
                                    'MODULE' => isset($arResult['MODULE']) ? $arResult['MODULE'] : 'catalog',
                                    'PRODUCT_PROVIDER_CLASS' => isset($arResult['PRODUCT_PROVIDER_CLASS']) ? $arResult['PRODUCT_PROVIDER_CLASS'] : 'CCatalogProductProvider',
                                    'QUANTITY' => isset($arResult['QUANTITY']) ? $arResult['QUANTITY'] : null,
                                    'IBLOCK_ID' => isset($arResult['IBLOCK_ID']) ? $arResult['IBLOCK_ID'] : null,

                                    'PRIMARY_OFFER_ID' => isset($arResult['OFFERS'][0]['ID']) ? $arResult['OFFERS'][0]['ID'] : null,
                                    'SECTION' => array(
                                        'ID' => isset($arResult['SECTION']['ID']) ? $arResult['SECTION']['ID'] : null,
                                        'IBLOCK_ID' => isset($arResult['SECTION']['IBLOCK_ID']) ? $arResult['SECTION']['IBLOCK_ID'] : null,
                                        'LEFT_MARGIN' => isset($arResult['SECTION']['LEFT_MARGIN']) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
                                        'RIGHT_MARGIN' => isset($arResult['SECTION']['RIGHT_MARGIN']) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
                                    ),
                                )
                            ),
                            $component,
                            array('HIDE_ICONS' => 'Y')
                        );
                    }

                    if ($arResult['CATALOG'] && $arParams['USE_GIFTS_DETAIL'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled('sale')) {
                        ?>
                        <div data-entity="parent-container">
                            <?
                            if (!isset($arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE']) || $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'] !== 'Y') {
                                ?>
                                <div class="catalog-block-header" data-entity="header" data-showed="false"
                                     style="display: none; opacity: 0;">
                                    <b><?= ($arParams['GIFTS_DETAIL_BLOCK_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_GIFT_BLOCK_TITLE_DEFAULT')) ?></b>
                                </div>
                                <?
                            }

                            CBitrixComponent::includeComponentClass('bitrix:sale.products.gift');
                            $APPLICATION->IncludeComponent(
                                'bitrix:sale.products.gift',
                                '.default',
                                array(
                                    'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
                                    'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
                                    'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],

                                    'PRODUCT_ROW_VARIANTS' => "",
                                    'PAGE_ELEMENT_COUNT' => 0,
                                    'DEFERRED_PRODUCT_ROW_VARIANTS' => \Bitrix\Main\Web\Json::encode(
                                        SaleProductsGiftComponent::predictRowVariants(
                                            $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
                                            $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT']
                                        )
                                    ),
                                    'DEFERRED_PAGE_ELEMENT_COUNT' => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],

                                    'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
                                    'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
                                    'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
                                    'PRODUCT_DISPLAY_MODE' => 'Y',
                                    'PRODUCT_BLOCKS_ORDER' => $arParams['GIFTS_PRODUCT_BLOCKS_ORDER'],
                                    'SHOW_SLIDER' => $arParams['GIFTS_SHOW_SLIDER'],
                                    'SLIDER_INTERVAL' => isset($arParams['GIFTS_SLIDER_INTERVAL']) ? $arParams['GIFTS_SLIDER_INTERVAL'] : '',
                                    'SLIDER_PROGRESS' => isset($arParams['GIFTS_SLIDER_PROGRESS']) ? $arParams['GIFTS_SLIDER_PROGRESS'] : '',

                                    'TEXT_LABEL_GIFT' => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],

                                    'LABEL_PROP_' . $arParams['IBLOCK_ID'] => array(),
                                    'LABEL_PROP_MOBILE_' . $arParams['IBLOCK_ID'] => array(),
                                    'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],

                                    'ADD_TO_BASKET_ACTION' => (isset($arParams['ADD_TO_BASKET_ACTION']) ? $arParams['ADD_TO_BASKET_ACTION'] : ''),
                                    'MESS_BTN_BUY' => $arParams['~GIFTS_MESS_BTN_BUY'],
                                    'MESS_BTN_ADD_TO_BASKET' => $arParams['~GIFTS_MESS_BTN_BUY'],
                                    'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
                                    'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],

                                    'SHOW_PRODUCTS_' . $arParams['IBLOCK_ID'] => 'Y',
                                    'PROPERTY_CODE_' . $arParams['IBLOCK_ID'] => $arParams['LIST_PROPERTY_CODE'],
                                    'PROPERTY_CODE_MOBILE' . $arParams['IBLOCK_ID'] => $arParams['LIST_PROPERTY_CODE_MOBILE'],
                                    'PROPERTY_CODE_' . $arResult['OFFERS_IBLOCK'] => $arParams['OFFER_TREE_PROPS'],
                                    'OFFER_TREE_PROPS_' . $arResult['OFFERS_IBLOCK'] => $arParams['OFFER_TREE_PROPS'],
                                    'CART_PROPERTIES_' . $arResult['OFFERS_IBLOCK'] => $arParams['OFFERS_CART_PROPERTIES'],
                                    'ADDITIONAL_PICT_PROP_' . $arParams['IBLOCK_ID'] => (isset($arParams['ADD_PICT_PROP']) ? $arParams['ADD_PICT_PROP'] : ''),
                                    'ADDITIONAL_PICT_PROP_' . $arResult['OFFERS_IBLOCK'] => (isset($arParams['OFFER_ADD_PICT_PROP']) ? $arParams['OFFER_ADD_PICT_PROP'] : ''),

                                    'HIDE_NOT_AVAILABLE' => 'Y',
                                    'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
                                    'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                                    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
                                    'PRICE_CODE' => $arParams['PRICE_CODE'],
                                    'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
                                    'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
                                    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                                    'BASKET_URL' => $arParams['BASKET_URL'],
                                    'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
                                    'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
                                    'PARTIAL_PRODUCT_PROPERTIES' => $arParams['PARTIAL_PRODUCT_PROPERTIES'],
                                    'USE_PRODUCT_QUANTITY' => 'N',
                                    'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                                    'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                                    'POTENTIAL_PRODUCT_TO_BUY' => array(
                                        'ID' => isset($arResult['ID']) ? $arResult['ID'] : null,
                                        'MODULE' => isset($arResult['MODULE']) ? $arResult['MODULE'] : 'catalog',
                                        'PRODUCT_PROVIDER_CLASS' => isset($arResult['PRODUCT_PROVIDER_CLASS']) ? $arResult['PRODUCT_PROVIDER_CLASS'] : 'CCatalogProductProvider',
                                        'QUANTITY' => isset($arResult['QUANTITY']) ? $arResult['QUANTITY'] : null,
                                        'IBLOCK_ID' => isset($arResult['IBLOCK_ID']) ? $arResult['IBLOCK_ID'] : null,

                                        'PRIMARY_OFFER_ID' => isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'])
                                            ? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID']
                                            : null,
                                        'SECTION' => array(
                                            'ID' => isset($arResult['SECTION']['ID']) ? $arResult['SECTION']['ID'] : null,
                                            'IBLOCK_ID' => isset($arResult['SECTION']['IBLOCK_ID']) ? $arResult['SECTION']['IBLOCK_ID'] : null,
                                            'LEFT_MARGIN' => isset($arResult['SECTION']['LEFT_MARGIN']) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
                                            'RIGHT_MARGIN' => isset($arResult['SECTION']['RIGHT_MARGIN']) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
                                        ),
                                    ),

                                    'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
                                    'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
                                    'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY']
                                ),
                                $component,
                                array('HIDE_ICONS' => 'Y')
                            );
                            ?>
                        </div>
                        <?
                    }

                    if ($arResult['CATALOG'] && $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled('sale')) {
                        ?>
                        <div data-entity="parent-container">
                            <?
                            if (!isset($arParams['GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE']) || $arParams['GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE'] !== 'Y') {
                                ?>
                                <div class="catalog-block-header" data-entity="header" data-showed="false"
                                     style="display: none; opacity: 0;">
                                    <?= ($arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_GIFTS_MAIN_BLOCK_TITLE_DEFAULT')) ?>
                                </div>
                                <?
                            }

                            $APPLICATION->IncludeComponent(
                                'bitrix:sale.gift.main.products',
                                '.default',
                                array(
                                    'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
                                    'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
                                    'LINE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
                                    'HIDE_BLOCK_TITLE' => 'Y',
                                    'BLOCK_TITLE' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],

                                    'OFFERS_FIELD_CODE' => $arParams['OFFERS_FIELD_CODE'],
                                    'OFFERS_PROPERTY_CODE' => $arParams['OFFERS_PROPERTY_CODE'],

                                    'AJAX_MODE' => $arParams['AJAX_MODE'],
                                    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                                    'IBLOCK_ID' => $arParams['IBLOCK_ID'],

                                    'ELEMENT_SORT_FIELD' => 'ID',
                                    'ELEMENT_SORT_ORDER' => 'DESC',
                                    //'ELEMENT_SORT_FIELD2' => $arParams['ELEMENT_SORT_FIELD2'],
                                    //'ELEMENT_SORT_ORDER2' => $arParams['ELEMENT_SORT_ORDER2'],
                                    'FILTER_NAME' => 'searchFilter',
                                    'SECTION_URL' => $arParams['SECTION_URL'],
                                    'DETAIL_URL' => $arParams['DETAIL_URL'],
                                    'BASKET_URL' => $arParams['BASKET_URL'],
                                    'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
                                    'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
                                    'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],

                                    'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                    'CACHE_TIME' => $arParams['CACHE_TIME'],

                                    'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                                    'SET_TITLE' => $arParams['SET_TITLE'],
                                    'PROPERTY_CODE' => $arParams['PROPERTY_CODE'],
                                    'PRICE_CODE' => $arParams['PRICE_CODE'],
                                    'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
                                    'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],

                                    'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
                                    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                                    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                                    'HIDE_NOT_AVAILABLE' => 'Y',
                                    'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
                                    'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
                                    'PRODUCT_BLOCKS_ORDER' => $arParams['GIFTS_PRODUCT_BLOCKS_ORDER'],

                                    'SHOW_SLIDER' => $arParams['GIFTS_SHOW_SLIDER'],
                                    'SLIDER_INTERVAL' => isset($arParams['GIFTS_SLIDER_INTERVAL']) ? $arParams['GIFTS_SLIDER_INTERVAL'] : '',
                                    'SLIDER_PROGRESS' => isset($arParams['GIFTS_SLIDER_PROGRESS']) ? $arParams['GIFTS_SLIDER_PROGRESS'] : '',

                                    'ADD_PICT_PROP' => (isset($arParams['ADD_PICT_PROP']) ? $arParams['ADD_PICT_PROP'] : ''),
                                    'LABEL_PROP' => (isset($arParams['LABEL_PROP']) ? $arParams['LABEL_PROP'] : ''),
                                    'LABEL_PROP_MOBILE' => (isset($arParams['LABEL_PROP_MOBILE']) ? $arParams['LABEL_PROP_MOBILE'] : ''),
                                    'LABEL_PROP_POSITION' => (isset($arParams['LABEL_PROP_POSITION']) ? $arParams['LABEL_PROP_POSITION'] : ''),
                                    'OFFER_ADD_PICT_PROP' => (isset($arParams['OFFER_ADD_PICT_PROP']) ? $arParams['OFFER_ADD_PICT_PROP'] : ''),
                                    'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS']) ? $arParams['OFFER_TREE_PROPS'] : ''),
                                    'SHOW_DISCOUNT_PERCENT' => (isset($arParams['SHOW_DISCOUNT_PERCENT']) ? $arParams['SHOW_DISCOUNT_PERCENT'] : ''),
                                    'DISCOUNT_PERCENT_POSITION' => (isset($arParams['DISCOUNT_PERCENT_POSITION']) ? $arParams['DISCOUNT_PERCENT_POSITION'] : ''),
                                    'SHOW_OLD_PRICE' => (isset($arParams['SHOW_OLD_PRICE']) ? $arParams['SHOW_OLD_PRICE'] : ''),
                                    'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
                                    'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
                                    'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
                                    'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
                                    'ADD_TO_BASKET_ACTION' => (isset($arParams['ADD_TO_BASKET_ACTION']) ? $arParams['ADD_TO_BASKET_ACTION'] : ''),
                                    'SHOW_CLOSE_POPUP' => (isset($arParams['SHOW_CLOSE_POPUP']) ? $arParams['SHOW_CLOSE_POPUP'] : ''),
                                    'DISPLAY_COMPARE' => (isset($arParams['DISPLAY_COMPARE']) ? $arParams['DISPLAY_COMPARE'] : ''),
                                    'COMPARE_PATH' => (isset($arParams['COMPARE_PATH']) ? $arParams['COMPARE_PATH'] : ''),
                                )
                                + array(
                                    'OFFER_ID' => empty($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'])
                                        ? $arResult['ID']
                                        : $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'],
                                    'SECTION_ID' => $arResult['SECTION']['ID'],
                                    'ELEMENT_ID' => $arResult['ID'],

                                    'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
                                    'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
                                    'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY']
                                ),
                                $component,
                                array('HIDE_ICONS' => 'Y')
                            );
                            ?>
                        </div>
                        <?
                    }
                    ?>
                </div>
            </div>
        </div>

        <!--Small Card-->
        <div class="product-item-detail-short-card-fixed hidden-xs" id="<?= $itemIds['SMALL_CARD_PANEL_ID'] ?>">
            <div class="product-item-detail-short-card-content-container">
                <table>
                    <tr>
                        <td rowspan="2" class="product-item-detail-short-card-image">
                            <img src="" style="height: 65px;" data-entity="panel-picture" alt="<?= $alt ?>">
                        </td>
                        <td class="product-item-detail-short-title-container" data-entity="panel-title">
                            <span class="product-item-detail-short-title-text"><?= $name ?></span>
                        </td>
                        <td rowspan="2" class="product-item-detail-short-card-price">
                            <?
                            if ($arParams['SHOW_OLD_PRICE'] === 'Y') {
                                ?>
                                <div class="product-item-detail-price-old"
                                     style="display: <?= ($showDiscount ? '' : 'none') ?>;"
                                     data-entity="panel-old-price">
                                    <?= ($showDiscount ? $price['PRINT_RATIO_BASE_PRICE'] : '') ?>
                                </div>
                                <?
                            }
                            ?>
                            <div class="product-item-detail-price-current" data-entity="panel-price">
                                <?= $price['PRINT_RATIO_PRICE'] ?>
                            </div>
                        </td>
                        <?
                        if ($showAddBtn) {
                            ?>
                            <td rowspan="2" class="product-item-detail-short-card-btn"
                                style="display: <?= ($actualItem['CAN_BUY'] ? '' : 'none') ?>;"
                                data-entity="panel-add-button">
                                <a class="btn <?= $showButtonClassName ?> product-item-detail-buy-button"
                                   id="<?= $itemIds['ADD_BASKET_LINK'] ?>"
                                   href="javascript:void(0);">
                                    <span><?= $arParams['MESS_BTN_ADD_TO_BASKET'] ?></span>
                                </a>
                            </td>
                            <?
                        }

                        if ($showBuyBtn) {
                            ?>
                            <td rowspan="2" class="product-item-detail-short-card-btn"
                                style="display: <?= ($actualItem['CAN_BUY'] ? '' : 'none') ?>;"
                                data-entity="panel-buy-button">
                                <a class="btn <?= $buyButtonClassName ?> product-item-detail-buy-button"
                                   id="<?= $itemIds['BUY_LINK'] ?>"
                                   href="javascript:void(0);">
                                    <span><?= $arParams['MESS_BTN_BUY'] ?></span>
                                </a>
                            </td>
                            <?
                        }
                        ?>
                        <td rowspan="2" class="product-item-detail-short-card-btn"
                            style="display: <?= (!$actualItem['CAN_BUY'] ? '' : 'none') ?>;"
                            data-entity="panel-not-available-button">
                            <a class="btn btn-link product-item-detail-buy-button" href="javascript:void(0)"
                               rel="nofollow">
                                <?= $arParams['MESS_NOT_AVAILABLE'] ?>
                            </a>
                        </td>
                    </tr>
                    <?
                    if ($haveOffers) {
                        ?>
                        <tr>
                            <td>
                                <div class="product-item-selected-scu-container" data-entity="panel-sku-container">
                                    <?
                                    $i = 0;

                                    foreach ($arResult['SKU_PROPS'] as $skuProperty) {
                                        if (!isset($arResult['OFFERS_PROP'][$skuProperty['CODE']])) {
                                            continue;
                                        }

                                        $propertyId = $skuProperty['ID'];

                                        foreach ($skuProperty['VALUES'] as $value) {
                                            $value['NAME'] = htmlspecialcharsbx($value['NAME']);
                                            if ($skuProperty['SHOW_MODE'] === 'PICT') {
                                                ?>
                                                <div class="product-item-selected-scu product-item-selected-scu-color selected"
                                                     title="<?= $value['NAME'] ?>"
                                                     style="background-image: url('<?= $value['PICT']['SRC'] ?>'); display: none;"
                                                     data-sku-line="<?= $i ?>"
                                                     data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
                                                     data-onevalue="<?= $value['ID'] ?>">
                                                </div>
                                                <?
                                            } else {
                                                ?>
                                                <div class="product-item-selected-scu product-item-selected-scu-text selected"
                                                     title="<?= $value['NAME'] ?>"
                                                     style="display: none;"
                                                     data-sku-line="<?= $i ?>"
                                                     data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
                                                     data-onevalue="<?= $value['ID'] ?>">
                                                    <?= $value['NAME'] ?>
                                                </div>
                                                <?
                                            }
                                        }

                                        $i++;
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <?
                    }
                    ?>
                </table>
            </div>
        </div>
        <!--Top tabs-->
        <div class="product-item-detail-tabs-container-fixed hidden-xs" id="<?= $itemIds['TABS_PANEL_ID'] ?>">
            <ul class="product-item-detail-tabs-list">
                <? if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS']) { ?>
                    <li class="product-item-detail-tab" data-entity="tab" data-value="properties">
                        <a href="javascript:void(0);" class="product-item-detail-tab-link">
                            <span><?= $arParams['MESS_PROPERTIES_TAB'] ?></span>
                        </a>
                    </li>
                <? } ?>
				<? if ($isSIMILARPRODUCTS) { ?>
                    <li class="product-item-detail-tab active" data-entity="tab" data-value="similarproducts">
                        <a href="javascript:void(0);" class="product-item-detail-tab-link">
                            <span><?= GetMessage('CP_BCE_SIMILAR_PRODUCTS_TAB') ?></span>
                        </a>
                    </li>
                <? } ?>
                <? if ($showDescription) { ?>
                    <li class="product-item-detail-tab" data-entity="tab" data-value="description">
                        <a href="javascript:void(0);" class="product-item-detail-tab-link">
                            <span><?= $arParams['MESS_DESCRIPTION_TAB'] ?></span>
                        </a>
                    </li>
                <? } ?>

                <? if ($isVIDEOYOUTUBE) { ?>
                    <li class="product-item-detail-tab" data-entity="tab" data-value="videoyoutube">
                        <a href="javascript:void(0);" class="product-item-detail-tab-link">
                            <span><?= GetMessage('CP_BCE_VIDEOYOUTUBE_TAB') ?></span>
                        </a>
                    </li>
                <? } ?>
                <? if ($isINSTRUCTIONS) { ?>
                    <li class="product-item-detail-tab" data-entity="tab" data-value="instructions">
                        <a href="javascript:void(0);" class="product-item-detail-tab-link">
                            <span><?= $arParams['MESS_INSTRUCTIONS_TAB'] ?></span>
                        </a>
                    </li>
                <? } ?>
                <? if ($isCERTIFICATES) { ?>
                    <li class="product-item-detail-tab" data-entity="tab" data-value="certificates">
                        <a href="javascript:void(0);" class="product-item-detail-tab-link">
                            <span><?= $arParams['MESS_CERTIFICATES_TAB'] ?></span>
                        </a>
                    </li>
                <? } ?>
                <? if ($isACCESSORIES) { ?>
                    <li class="product-item-detail-tab" data-entity="tab" data-value="accessories">
                        <a href="javascript:void(0);" class="product-item-detail-tab-link">
                            <span><?= $arParams['MESS_ACCESSORIES_TAB'] ?></span>
                        </a>
                    </li>
                <? } ?>
                <? if ($isDIMENSIONS) { ?>
                    <li class="product-item-detail-tab" data-entity="tab" data-value="dimensions">
                        <a href="javascript:void(0);" class="product-item-detail-tab-link">
                            <span><?= GetMessage('CP_BCE_DIMENSIONS_TAB') ?></span>
                        </a>
                    </li>
                <? } ?>
                <? if ($isRELATEDPRODUCTS) { ?>
                    <li class="product-item-detail-tab active" data-entity="tab" data-value="relatedproducts">
                        <a href="javascript:void(0);" class="product-item-detail-tab-link">
                            <span><?= GetMessage('CP_BCE_RELATED_PRODUCTS_TAB') ?></span>
                        </a>
                    </li>
                <? } ?>
                <? if ($arParams['USE_COMMENTS'] === 'Y') { ?>
                    <li class="product-item-detail-tab" data-entity="tab" data-value="comments">
                        <a href="javascript:void(0);" class="product-item-detail-tab-link">
                            <span><?= $arParams['MESS_COMMENTS_TAB'] ?></span>
                        </a>
                    </li>
                <? } ?>
            </ul>
        </div>

        <meta itemprop="name" content="<?= $name ?>"/>
        <meta itemprop="category" content="<?= $arResult['CATEGORY_PATH'] ?>"/>
        <?
        if ($haveOffers) {
            foreach ($arResult['JS_OFFERS'] as $offer) {
                $currentOffersList = array();

                if (!empty($offer['TREE']) && is_array($offer['TREE'])) {
                    foreach ($offer['TREE'] as $propName => $skuId) {
                        $propId = (int)substr($propName, 5);

                        foreach ($skuProps as $prop) {
                            if ($prop['ID'] == $propId) {
                                foreach ($prop['VALUES'] as $propId => $propValue) {
                                    if ($propId == $skuId) {
                                        $currentOffersList[] = $propValue['NAME'];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }

                $offerPrice = $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']];
                ?>
                <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<meta itemprop="sku" content="<?= htmlspecialcharsbx(implode('/', $currentOffersList)) ?>"/>
				<meta itemprop="price" content="<?= $offerPrice['RATIO_PRICE'] ?>"/>
				<meta itemprop="priceCurrency" content="<?= $offerPrice['CURRENCY'] ?>"/>
				<link itemprop="availability"
                      href="http://schema.org/<?= ($offer['CAN_BUY'] ? 'InStock' : 'OutOfStock') ?>"/>
			</span>
                <?
            }

            unset($offerPrice, $currentOffersList);
        } else {
            ?>
            <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			<meta itemprop="price" content="<?= $price['RATIO_PRICE'] ?>"/>
			<meta itemprop="priceCurrency" content="<?= $price['CURRENCY'] ?>"/>
			<link itemprop="availability"
                  href="http://schema.org/<?= ($actualItem['CAN_BUY'] ? 'InStock' : 'OutOfStock') ?>"/>
		</span>
            <?
        }
        ?>
    </div>

<?
if ($haveOffers) {
    $offerIds = array();
    $offerCodes = array();

    $useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';

    foreach ($arResult['JS_OFFERS'] as $ind => &$jsOffer) {
        $offerIds[] = (int)$jsOffer['ID'];
        $offerCodes[] = $jsOffer['CODE'];

        $fullOffer = $arResult['OFFERS'][$ind];
        $measureName = $fullOffer['ITEM_MEASURE']['TITLE'];

        $strAllProps = '';
        $strMainProps = '';
        $strPriceRangesRatio = '';
        $strPriceRanges = '';

        if ($arResult['SHOW_OFFERS_PROPS']) {
            if (!empty($jsOffer['DISPLAY_PROPERTIES'])) {
                foreach ($jsOffer['DISPLAY_PROPERTIES'] as $property) {
                    $current = '<dt>' . $property['NAME'] . '</dt><dd>' . (
                        is_array($property['VALUE'])
                            ? implode(' / ', $property['VALUE'])
                            : $property['VALUE']
                        ) . '</dd>';
                    $strAllProps .= $current;

                    if (isset($arParams['MAIN_BLOCK_OFFERS_PROPERTY_CODE'][$property['CODE']])) {
                        $strMainProps .= $current;
                    }
                }

                unset($current);
            }
        }

        if ($arParams['USE_PRICE_COUNT'] && count($jsOffer['ITEM_QUANTITY_RANGES']) > 1) {
            $strPriceRangesRatio = '(' . Loc::getMessage(
                    'CT_BCE_CATALOG_RATIO_PRICE',
                    array('#RATIO#' => ($useRatio
                            ? $fullOffer['ITEM_MEASURE_RATIOS'][$fullOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']
                            : '1'
                        ) . ' ' . $measureName)
                ) . ')';

            foreach ($jsOffer['ITEM_QUANTITY_RANGES'] as $range) {
                if ($range['HASH'] !== 'ZERO-INF') {
                    $itemPrice = false;

                    foreach ($jsOffer['ITEM_PRICES'] as $itemPrice) {
                        if ($itemPrice['QUANTITY_HASH'] === $range['HASH']) {
                            break;
                        }
                    }

                    if ($itemPrice) {
                        $strPriceRanges .= '<dt>' . Loc::getMessage(
                                'CT_BCE_CATALOG_RANGE_FROM',
                                array('#FROM#' => $range['SORT_FROM'] . ' ' . $measureName)
                            ) . ' ';

                        if (is_infinite($range['SORT_TO'])) {
                            $strPriceRanges .= Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
                        } else {
                            $strPriceRanges .= Loc::getMessage(
                                'CT_BCE_CATALOG_RANGE_TO',
                                array('#TO#' => $range['SORT_TO'] . ' ' . $measureName)
                            );
                        }

                        $strPriceRanges .= '</dt><dd>' . ($useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE']) . '</dd>';
                    }
                }
            }

            unset($range, $itemPrice);
        }

        $jsOffer['DISPLAY_PROPERTIES'] = $strAllProps;
        $jsOffer['DISPLAY_PROPERTIES_MAIN_BLOCK'] = $strMainProps;
        $jsOffer['PRICE_RANGES_RATIO_HTML'] = $strPriceRangesRatio;
        $jsOffer['PRICE_RANGES_HTML'] = $strPriceRanges;
    }

    $templateData['OFFER_IDS'] = $offerIds;
    $templateData['OFFER_CODES'] = $offerCodes;
    unset($jsOffer, $strAllProps, $strMainProps, $strPriceRanges, $strPriceRangesRatio, $useRatio);

    $jsParams = array(
        'CONFIG' => array(
            'USE_CATALOG' => $arResult['CATALOG'],
            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_PRICE' => true,
            'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
            'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
            'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
            'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
            'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
            'OFFER_GROUP' => $arResult['OFFER_GROUP'],
            'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
            'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
            'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
            'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
            'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
            'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
            'USE_STICKERS' => true,
            'USE_SUBSCRIBE' => $showSubscribe,
            'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
            'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
            'ALT' => $alt,
            'TITLE' => $title,
            'MAGNIFIER_ZOOM_PERCENT' => 200,
            'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
            'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
            'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
                ? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
                : null
        ),
        'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
        'VISUAL' => $itemIds,
        'DEFAULT_PICTURE' => array(
            'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
            'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
        ),
        'PRODUCT' => array(
            'ID' => $arResult['ID'],
            'ACTIVE' => $arResult['ACTIVE'],
            'NAME' => $arResult['~NAME'],
            'CATEGORY' => $arResult['CATEGORY_PATH']
        ),
        'BASKET' => array(
            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'BASKET_URL' => $arParams['BASKET_URL'],
            'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
        ),
        'OFFERS' => $arResult['JS_OFFERS'],
        'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
        'TREE_PROPS' => $skuProps
    );
} else {
    $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
    if ($arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !$emptyProductProperties) {
        ?>
        <div id="<?= $itemIds['BASKET_PROP_DIV'] ?>" style="display: none;">
            <?
            if (!empty($arResult['PRODUCT_PROPERTIES_FILL'])) {
                foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propId => $propInfo) {
                    ?>
                    <input type="hidden" name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]"
                           value="<?= htmlspecialcharsbx($propInfo['ID']) ?>">
                    <?
                    unset($arResult['PRODUCT_PROPERTIES'][$propId]);
                }
            }

            $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
            if (!$emptyProductProperties) {
                ?>
                <table>
                    <?
                    foreach ($arResult['PRODUCT_PROPERTIES'] as $propId => $propInfo) {
                        ?>
                        <tr>
                            <td><?= $arResult['PROPERTIES'][$propId]['NAME'] ?></td>
                            <td>
                                <?
                                if (
                                    $arResult['PROPERTIES'][$propId]['PROPERTY_TYPE'] === 'L'
                                    && $arResult['PROPERTIES'][$propId]['LIST_TYPE'] === 'C'
                                ) {
                                    foreach ($propInfo['VALUES'] as $valueId => $value) {
                                        ?>
                                        <label>
                                            <input type="radio"
                                                   name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]"
                                                   value="<?= $valueId ?>" <?= ($valueId == $propInfo['SELECTED'] ? '"checked"' : '') ?>>
                                            <?= $value ?>
                                        </label>
                                        <br>
                                        <?
                                    }
                                } else {
                                    ?>
                                    <select name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]">
                                        <?
                                        foreach ($propInfo['VALUES'] as $valueId => $value) {
                                            ?>
                                            <option value="<?= $valueId ?>" <?= ($valueId == $propInfo['SELECTED'] ? '"selected"' : '') ?>>
                                                <?= $value ?>
                                            </option>
                                            <?
                                        }
                                        ?>
                                    </select>
                                    <?
                                }
                                ?>
                            </td>
                        </tr>
                        <?
                    }
                    ?>
                </table>
                <?
            }
            ?>
        </div>
        <?
    }

    $jsParams = array(
        'CONFIG' => array(
            'USE_CATALOG' => $arResult['CATALOG'],
            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_PRICE' => !empty($arResult['ITEM_PRICES']),
            'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
            'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
            'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
            'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
            'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
            'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
            'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
            'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
            'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
            'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
            'USE_STICKERS' => true,
            'USE_SUBSCRIBE' => $showSubscribe,
            'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
            'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
            'ALT' => $alt,
            'TITLE' => $title,
            'MAGNIFIER_ZOOM_PERCENT' => 200,
            'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
            'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
            'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
                ? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
                : null
        ),
        'VISUAL' => $itemIds,
        'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
        'PRODUCT' => array(
            'ID' => $arResult['ID'],
            'ACTIVE' => $arResult['ACTIVE'],
            'PICT' => reset($arResult['MORE_PHOTO']),
            'NAME' => $arResult['~NAME'],
            'SUBSCRIPTION' => true,
            'ITEM_PRICE_MODE' => $arResult['ITEM_PRICE_MODE'],
            'ITEM_PRICES' => $arResult['ITEM_PRICES'],
            'ITEM_PRICE_SELECTED' => $arResult['ITEM_PRICE_SELECTED'],
            'ITEM_QUANTITY_RANGES' => $arResult['ITEM_QUANTITY_RANGES'],
            'ITEM_QUANTITY_RANGE_SELECTED' => $arResult['ITEM_QUANTITY_RANGE_SELECTED'],
            'ITEM_MEASURE_RATIOS' => $arResult['ITEM_MEASURE_RATIOS'],
            'ITEM_MEASURE_RATIO_SELECTED' => $arResult['ITEM_MEASURE_RATIO_SELECTED'],
            'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
            'SLIDER' => $arResult['MORE_PHOTO'],
            'CAN_BUY' => $arResult['CAN_BUY'],
            'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
            'CHECK_NALICHIE' => ($arResult['PROPERTIES']['NALICHIE']['VALUE'] == 'Нет в наличии' ? false : true),
            'IS_SIMILAR_PRODUCTS' => $isSIMILARPRODUCTS,
            'QUANTITY_FLOAT' => is_float($arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
            'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
            'STEP_QUANTITY' => $arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
            'CATEGORY' => $arResult['CATEGORY_PATH']
        ),
        'BASKET' => array(
            'ADD_PROPS' => $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y',
            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
            'EMPTY_PROPS' => $emptyProductProperties,
            'BASKET_URL' => $arParams['BASKET_URL'],
            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
        )
    );
    unset($emptyProductProperties);
}

if ($arParams['DISPLAY_COMPARE']) {
    $jsParams['COMPARE'] = array(
        'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
        'COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
        'COMPARE_PATH' => $arParams['COMPARE_PATH']
    );
}

?>

    <script>
        BX.message({
            ECONOMY_INFO_MESSAGE: '<?=GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO2')?>',
            TITLE_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR')?>',
            TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS')?>',
            BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR')?>',
            BTN_SEND_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS')?>',
            BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
            BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE')?>',
            BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
            TITLE_SUCCESSFUL: '<?=GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK')?>',
            COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK')?>',
            COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
            COMPARE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE')?>',
            BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
            PRODUCT_GIFT_LABEL: '<?=GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL')?>',
            PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
            RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
            RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
            SITE_ID: '<?=$component->getSiteId()?>'
        });

        var <?=$obName?> =
        new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
    </script>
<?
unset($actualItem, $itemIds, $jsParams);