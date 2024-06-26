<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

/**
 * Bitrix vars
 *
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent         $component
 *
 * @var array                    $arParams
 * @var array                    $arResult
 *
 * @var string                   $templateName
 * @var string                   $templateFile
 * @var string                   $templateFolder
 * @var array                    $templateData
 *
 * @var string                   $componentPath
 * @var string                   $parentTemplateFolder
 *
 * @var CDatabase                $DB
 * @var CUser                    $USER
 * @var CMain                    $APPLICATION
 */

//$this - объект шаблона
//$component - объект компонента

//$this->GetFolder()
//$tplId = $this->GetEditAreaId($arResult['ID']);

//Объект родительского компонента
//$parent = $component->getParent();
//$parentPath = $parent->getPath();

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if(method_exists($this, 'setFrameMode'))
	$this->setFrameMode(true);

if($arParams['INCLUDE_CSS'] == 'Y') {
	// $this->addExternalCss($templateFolder . '/theme/' . $arParams['THEME'] . '/style.css');
}

$words = array('отзыв', 'отзыва', 'отзывов');
$num = $arResult['COUNT_ITEMS'] % 100;
if ($num > 19)  $num = $num % 10;
$mess = ' ';
switch ($num) {
    case 1:  $mess .= $words[0]; break;
    case 2:
    case 3:
    case 4:  $mess .= $words[1]; break;
    default: $mess .= $words[2]; break;
}

$reviewsId = "api_reviews_element_rating_" . $component->randString();
$border = ($arParams['HIDE_BORDER'] == 'Y' ? 'api-hide-border' : '');
?>
<style>
    /* General
 ========================================================================== */
    .api-reviews-element-rating *{ -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
    .api-reviews-element-rating{
        overflow: hidden; margin-bottom: 15px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05); -moz-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05); box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
    }
    .api-reviews-element-rating.api-hide-border{padding: 3px 0; border: 0;-webkit-box-shadow: none; -moz-box-shadow: none; box-shadow: none;}
    .api-reviews-element-rating .api-row + .api-row{ margin-top: 15px }
    .api-reviews-element-rating .api-rating > div{ display: inline-block; vertical-align: middle }
    .api-reviews-element-rating .api-average span{ font-size: 16px; font-weight: bold; display: inline-block;}
    .api-reviews-element-rating .api-average a{color: #333; text-decoration: none; border-bottom: 1px dotted #0c0c0c;}
    .api-reviews-element-rating .api-average a:hover{border-bottom: 1px solid #0c0c0c;}
    .api-reviews-element-rating .api-stars-empty{ height: 21px; width: 110px; display: block; margin-right: 5px }
    .api-reviews-element-rating .api-stars-full{ height: 21px; display: block; width: 0; }
    /* .api-info */
    .api-reviews-element-rating .api-info .api-info-row{ position: relative; padding: 5px 0 }
    .api-reviews-element-rating .api-info .api-info-title{ position: absolute; left: 0; bottom: 0; top: 50%; margin-top: -12.5px }
    .api-reviews-element-rating .api-info .api-info-progress{ background: #ececec; height: 18px; margin: 0 55px 0 40px; overflow: hidden; -webkit-border-radius: 2px; -moz-border-radius: 2px; border-radius: 2px; }
    .api-reviews-element-rating .api-info .api-info-qty{ position: absolute; right: 0; top: 0; bottom: 0; font-weight: bold; font-size: 14px; line-height: 30px; min-width: 45px; }
    .api-reviews-element-rating .api-info .api-info-bar{ float: left; height: 100%; width: 0; -webkit-border-radius: 2px; -moz-border-radius: 2px; border-radius: 2px; }
    /* .api-icon-star */
    .api-reviews-element-rating .api-info .api-icon-star{ display: block; width: 27px; height: 25px; }
    .api-reviews-element-rating .api-info .api-icon-star5{ background-position: -110px 0; }
    .api-reviews-element-rating .api-info .api-icon-star4{ background-position: -110px -25px; }
    .api-reviews-element-rating .api-info .api-icon-star3{ background-position: -110px -50px; }
    .api-reviews-element-rating .api-info .api-icon-star2{ background-position: -110px -75px; }
    .api-reviews-element-rating .api-info .api-icon-star1{ background-position: -110px -100px; }
    /* Color
     ========================================================================== */
    .arelrating-color-orange1 .api-icon-star{ background: url("/bitrix/images/api.reviews/flat/orange1/sprite.png") no-repeat 0 0 transparent; }
    .arelrating-color-orange1 .api-stars-empty{ background: url("/bitrix/images/api.reviews/flat/orange1/sprite.png") no-repeat 0 -15px; }
    .arelrating-color-orange1 .api-stars-full{ background: url("/bitrix/images/api.reviews/flat/orange1/sprite.png") no-repeat 0 -36px; }
    .arelrating-color-orange1 .api-info .api-info-bar{ background-color: #f66128; }

    .arelrating-color-orange2 .api-icon-star{ background: url("/bitrix/images/api.reviews/flat/orange2/sprite.png") no-repeat 0 0 transparent; }
    .arelrating-color-orange2 .api-stars-empty{ background: url("/bitrix/images/api.reviews/flat/orange2/sprite.png") no-repeat 0 -15px; }
    .arelrating-color-orange2 .api-stars-full{ background: url("/bitrix/images/api.reviews/flat/orange2/sprite.png") no-repeat 0 -36px; }
    .arelrating-color-orange2 .api-info .api-info-bar5{ background-color: #79c471; }
    .arelrating-color-orange2 .api-info .api-info-bar4{ background-color: #3fbcef; }
    .arelrating-color-orange2 .api-info .api-info-bar3{ background-color: #c790b9; }
    .arelrating-color-orange2 .api-info .api-info-bar2{ background-color: #ef9c00; }
    .arelrating-color-orange2 .api-info .api-info-bar1{ background-color: #f66128; }

    .arelrating-color-orange3 .api-icon-star{ background: url("/bitrix/images/api.reviews/flat/orange3/sprite.png") no-repeat 0 0 transparent; }
    .arelrating-color-orange3 .api-stars-empty{ background: url("/bitrix/images/api.reviews/flat/orange3/sprite.png") no-repeat 0 -15px; }
    .arelrating-color-orange3 .api-stars-full{ background: url("/bitrix/images/api.reviews/flat/orange3/sprite.png") no-repeat 0 -36px; }
    .arelrating-color-orange3 .api-info .api-info-bar5{ background-color: #79c471; }
    .arelrating-color-orange3 .api-info .api-info-bar4{ background-color: #3fbcef; }
    .arelrating-color-orange3 .api-info .api-info-bar3{ background-color: #c790b9; }
    .arelrating-color-orange3 .api-info .api-info-bar2{ background-color: #ef9c00; }
    .arelrating-color-orange3 .api-info .api-info-bar1{ background-color: #f66128; }

    .arelrating-color-blue1 .api-icon-star{ background: url("/bitrix/images/api.reviews/flat/blue1/sprite.png") no-repeat 0 0 transparent; }
    .arelrating-color-blue1 .api-stars-empty{ background: url("/bitrix/images/api.reviews/flat/blue1/sprite.png") no-repeat 0 -15px; }
    .arelrating-color-blue1 .api-stars-full{ background: url("/bitrix/images/api.reviews/flat/blue1/sprite.png") no-repeat 0 -36px; }
    .arelrating-color-blue1 .api-info .api-info-bar{ background-color: #33b5e5; border: 1px solid #1e1e1e; }

    .arelrating-color-blue2 .api-icon-star{ background: url("/bitrix/images/api.reviews/flat/blue2/sprite.png") no-repeat 0 0 transparent; }
    .arelrating-color-blue2 .api-stars-empty{ background: url("/bitrix/images/api.reviews/flat/blue2/sprite.png") no-repeat 0 -15px; }
    .arelrating-color-blue2 .api-stars-full{ background: url("/bitrix/images/api.reviews/flat/blue2/sprite.png") no-repeat 0 -36px; }
    .arelrating-color-blue2 .api-info .api-info-bar{ background-color: #0083d1; }

    .arelrating-color-blue3 .api-icon-star{ background: url("/bitrix/images/api.reviews/flat/blue3/sprite.png") no-repeat 0 0 transparent; }
    .arelrating-color-blue3 .api-stars-empty{ background: url("/bitrix/images/api.reviews/flat/blue3/sprite.png") no-repeat 0 -15px; }
    .arelrating-color-blue3 .api-stars-full{ background: url("/bitrix/images/api.reviews/flat/blue3/sprite.png") no-repeat 0 -36px; }
    .arelrating-color-blue3 .api-info .api-info-bar{ background-color: #3fbcef; }

    .arelrating-color-black1 .api-icon-star{ background: url("/bitrix/images/api.reviews/flat/black1/sprite.png") no-repeat 0 0 transparent; }
    .arelrating-color-black1 .api-stars-empty{ background: url("/bitrix/images/api.reviews/flat/black1/sprite.png") no-repeat 0 -15px; }
    .arelrating-color-black1 .api-stars-full{ background: url("/bitrix/images/api.reviews/flat/black1/sprite.png") no-repeat 0 -36px; }
    .arelrating-color-black1 .api-info .api-info-bar{ background-color: #222; }

    .arelrating-color-red1 .api-icon-star{ background: url("/bitrix/images/api.reviews/flat/red1/sprite.png") no-repeat 0 0 transparent; }
    .arelrating-color-red1 .api-stars-empty{ background: url("/bitrix/images/api.reviews/flat/red1/sprite.png") no-repeat 0 -15px; }
    .arelrating-color-red1 .api-stars-full{ background: url("/bitrix/images/api.reviews/flat/red1/sprite.png") no-repeat 0 -36px; }
    .arelrating-color-red1 .api-info .api-info-bar{ background-color: #ed1c24; }

    .arelrating-color-pink1 .api-icon-star{ background: url("/bitrix/images/api.reviews/flat/pink1/sprite.png") no-repeat 0 0 transparent; }
    .arelrating-color-pink1 .api-stars-empty{ background: url("/bitrix/images/api.reviews/flat/pink1/sprite.png") no-repeat 0 -15px; }
    .arelrating-color-pink1 .api-stars-full{ background: url("/bitrix/images/api.reviews/flat/pink1/sprite.png") no-repeat 0 -36px; }
    .arelrating-color-pink1 .api-info .api-info-bar{ background-color: #ff28a8; }

    .arelrating-color-yellow1 .api-icon-star{ background: url("/bitrix/images/api.reviews/flat/yellow1/sprite.png") no-repeat 0 0 transparent; }
    .arelrating-color-yellow1 .api-stars-empty{ background: url("/bitrix/images/api.reviews/flat/yellow1/sprite.png") no-repeat 0 -15px; }
    .arelrating-color-yellow1 .api-stars-full{ background: url("/bitrix/images/api.reviews/flat/yellow1/sprite.png") no-repeat 0 -36px; }
    .arelrating-color-yellow1 .api-info .api-info-bar{ background-color: #ffc733; }

    .arelrating-color-green1 .api-icon-star{ background: url("/bitrix/images/api.reviews/flat/green1/sprite.png") no-repeat 0 0 transparent; }
    .arelrating-color-green1 .api-stars-empty{ background: url("/bitrix/images/api.reviews/flat/green1/sprite.png") no-repeat 0 -15px; }
    .arelrating-color-green1 .api-stars-full{ background: url("/bitrix/images/api.reviews/flat/green1/sprite.png") no-repeat 0 -36px; }
    .arelrating-color-green1 .api-info .api-info-bar{ background-color: #79c471; }

    .arelrating-color-green2 .api-icon-star{ background: url("/bitrix/images/api.reviews/flat/green2/sprite.png") no-repeat 0 0 transparent; }
    .arelrating-color-green2 .api-stars-empty{ background: url("/bitrix/images/api.reviews/flat/green2/sprite.png") no-repeat 0 -15px; }
    .arelrating-color-green2 .api-stars-full{ background: url("/bitrix/images/api.reviews/flat/green2/sprite.png") no-repeat 0 -36px; }
    .arelrating-color-green2 .api-info .api-info-bar5{ background-color: #57bb8a; }
    .arelrating-color-green2 .api-info .api-info-bar4{ background-color: #9ace6a; }
    .arelrating-color-green2 .api-info .api-info-bar3{ background-color: #ffeb3b; }
    .arelrating-color-green2 .api-info .api-info-bar2{ background-color: #ffbb50; }
    .arelrating-color-green2 .api-info .api-info-bar1{ background-color: #ff8a65; }

    .arelrating-color-green3 .api-icon-star{ background: url("/bitrix/images/api.reviews/flat/green3/sprite.png") no-repeat 0 0 transparent; }
    .arelrating-color-green3 .api-stars-empty{ background: url("/bitrix/images/api.reviews/flat/green3/sprite.png") no-repeat 0 -15px; }
    .arelrating-color-green3 .api-stars-full{ background: url("/bitrix/images/api.reviews/flat/green3/sprite.png") no-repeat 0 -36px; }
    .arelrating-color-green3 .api-info .api-info-bar5{ background-color: #57bb8a; }
    .arelrating-color-green3 .api-info .api-info-bar4{ background-color: #9ace6a; }
    .arelrating-color-green3 .api-info .api-info-bar3{ background-color: #ffeb3b; }
    .arelrating-color-green3 .api-info .api-info-bar2{ background-color: #ffbb50; }
    .arelrating-color-green3 .api-info .api-info-bar1{ background-color: #ff8a65; }

    .arelrating-color-purple1 .api-icon-star{ background: url("/bitrix/images/api.reviews/flat/purple1/sprite.png") no-repeat 0 0 transparent; }
    .arelrating-color-purple1 .api-stars-empty{ background: url("/bitrix/images/api.reviews/flat/purple1/sprite.png") no-repeat 0 -15px; }
    .arelrating-color-purple1 .api-stars-full{ background: url("/bitrix/images/api.reviews/flat/purple1/sprite.png") no-repeat 0 -36px; }
    .arelrating-color-purple1 .api-info .api-info-bar{ background-color: #c790b9; }
</style>
<div id="<?=$reviewsId?>">
	<?
	//$dynamicArea = new \Bitrix\Main\Page\FrameStatic(ToLower($reviewsId));
	//$dynamicArea->setAnimation(true);
	//$dynamicArea->setStub('');
	//$dynamicArea->setContainerID($reviewsId);
	//$dynamicArea->startDynamicArea();
	?>
	<div class="api-reviews-element-rating arelrating-color-<?=$arParams['COLOR']?> <?=$border?>">
		<div class="api-row api-rating">
            <?if($arResult['COUNT_ITEMS'] > 0):?>
                <div class="api-stars-empty">
                    <div class="api-stars-full" style="width: <?=$arResult['FULL_RATING']?>%"></div>
                </div>
            <?endif;?>
			<div class="api-average">
				<? if($arParams['REVIEWS_LINK']): ?>
					<a href="<?=$arParams['REVIEWS_LINK']?>"><?=$arResult['MESS_FULL_RATING'].$mess?></a>
				<? else: ?>
					<?=$arResult['MESS_FULL_RATING']?>
				<? endif ?>
			</div>
		</div>
		<? if($arParams['SHOW_PROGRESS_BAR'] == 'Y'): ?>
			<div class="api-row">
				<div class="api-info">
					<? for($i = 5; $i >= 1; $i--): ?>
						<div class="api-info-row">
							<div class="api-info-title">
								<div class="api-icon-star api-icon-star<?=$i?>"></div>
							</div>
							<div class="api-info-progress">
								<div style="width:<?=$arResult['COUNT_PROGRESS'][ $i ]?>%" class="api-info-bar api-info-bar<?=$i?>"></div>
							</div>
							<div class="api-info-qty" title="<?=$arResult['COUNT_REVIEWS'][ $i ]?>"><?=$arResult['COUNT_PROGRESS'][ $i ]?>%</div>
						</div>
					<? endfor ?>
				</div>
			</div>
		<? endif ?>
	</div>
	<?
	//$dynamicArea->finishDynamicArea();
	?>
</div>
