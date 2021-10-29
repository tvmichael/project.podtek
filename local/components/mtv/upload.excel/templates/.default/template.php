<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}
/**
 * @var CBitrixComponentTemplate $this
 * @var CBitrixMenuComponent $component
 * @var array $arParams
 * @var array $arResult
 * @var string $templateFolder
 * @var string $componentPath
 */

if (!empty($arResult) && $arResult['BTN_DISPLAY_FOR_USER']):?>
	<div class="btn-excel-container">
        <button id="<?=$arResult["BTN_ID"]?>" class="btn btn-default">
            <?=$arResult['BTN_TEXT'] ?? '';?>
            <span class="btn-excel-load">
                <img src="<?=$templateFolder;?>/images/loader.gif">
            </span>
        </button>
	</div>
    <script>
        <?$arResult['JS_PARAMS']['URL_AJAX'] = CUtil::JSEscape($component->getPath().'/ajax.php');?>
        BX.MTVExcel.init(<?=CUtil::PhpToJSObject($arResult['JS_PARAMS'])?>);
    </script>
<?endif;?>