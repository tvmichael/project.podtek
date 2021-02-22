<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>
<?
//Bitrix\Main\Diag\Debug::writeToFile(array('Form:'.date('H:i:s') => $arResult['arForm']['TIMESTAMP_X'], 'FORM_NOTE' => $arResult["FORM_NOTE"], $arResult["isFormNote"]),"","/test/log.txt");
//Bitrix\Main\Diag\Debug::writeToFile(array('Form:'.date('H:i:s') => $arResult, $_REQUEST),"","/test/log.txt");
?>

<?=$arResult["FORM_HEADER"]?>

    <div class="form-header">
        <?
        if ($arResult["isFormDescription"] == "Y" || $arResult["isFormTitle"] == "Y" || $arResult["isFormImage"] == "Y")
        {
        ?>
            <div><?
                /***********************************************************************************
                                    form header
                ***********************************************************************************/
                if ($arResult["isFormTitle"])
                {
                ?>
                    <h3><?= $arResult["FORM_TITLE"];?></h3>
                <?
                } //endif ;

                if ($arResult["isFormImage"] == "Y")
                {
                ?>
                    <a href="<?=$arResult["FORM_IMAGE"]["URL"]?>" target="_blank" alt="<?=GetMessage("FORM_ENLARGE")?>">
                        <img src="<?=$arResult["FORM_IMAGE"]["URL"]?>" <?if($arResult["FORM_IMAGE"]["WIDTH"] > 300):?>width="300"<?elseif($arResult["FORM_IMAGE"]["HEIGHT"] > 200):?>height="200"<?else:?><?=$arResult["FORM_IMAGE"]["ATTR"]?><?endif;?> hspace="3" vscape="3" border="0" />
                    </a>
                    <?//=$arResult["FORM_IMAGE"]["HTML_CODE"]?>
                <?
                } //endif
                ?>

                <p><?=$arResult["FORM_DESCRIPTION"]?></p>
            </div>
            <?
        } // endif
        ?>
    </div>

    <?
    /***********************************************************************************
                            form questions
    ***********************************************************************************/
    ?>
    <div class="form-table data-table">
        <div class="row">
            <div class="question block1-left col-xs-12 col-sm-4">
                <?
                $arSetViewTarget = [];
                foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
                {
                    if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden')
                    {
                        echo $arQuestion["HTML_CODE"];
                    }
                    else
                    {?>
                        <?if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'text'){?>
                            <div class="el-question el-text">
                                <?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
                                    <span class="error-fld" title="<?=htmlspecialcharsbx($arResult["FORM_ERRORS"][$FIELD_SID])?>"></span>
                                <?endif;?>
                                <div class="color-question title-question">
                                <?=$arQuestion["CAPTION"]?>
                                <?if ($arQuestion["REQUIRED"] == "Y"):?>
                                    <?=$arResult["REQUIRED_SIGN"];?>
                                <?endif;?>
                                </div>
                                <?=$arQuestion["IS_INPUT_CAPTION_IMAGE"] == "Y" ? "<br />".$arQuestion["IMAGE"]["HTML_CODE"]."<br />" : ""?>

                                <?foreach ($arQuestion["STRUCTURE"] as $arTypetext)
                                {?>
                                    <input placeholder="<?=$arTypetext['MESSAGE']?>"
                                           data-product-id="<?=$arTypetext['VALUE'];?>"
                                           type="<?=$arTypetext['FIELD_TYPE']?>"
                                           class="inputtext"
                                           name="form_<?=$arTypetext['FIELD_TYPE']?>_<?=$arTypetext['ID']?>"
                                           value="" size="0"><br>
                                <?}?>

                                <?//=$arQuestion["HTML_CODE"]?>
                            </div>
                        <?}else{?>
                            <div class="row">
                                <div class="col-xs-12 el-question">
                                    <?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
                                        <span class="error-fld" title="<?=htmlspecialcharsbx($arResult["FORM_ERRORS"][$FIELD_SID])?>"></span>
                                    <?endif;?>
                                    <div class="color-question title-question"><?=$arQuestion["CAPTION"]?>
                                        <?if ($arQuestion["REQUIRED"] == "Y"):?>
                                            <?=$arResult["REQUIRED_SIGN"];?>
                                        <?endif;?>
                                    </div>
                                    <fieldset>
                                        <?foreach ($arQuestion["STRUCTURE"] as $arLabelImg)
                                        {?>
                                            <label class="col-xs-12 rb-q">
                                                <input class="radio-dot"
                                                       type="<?=$arLabelImg['FIELD_TYPE']?>"
                                                       data-product-id="<?=$arLabelImg['VALUE'];?>"
                                                       <?if(!empty($arLabelImg['FIELD_PARAM'])) { echo $arLabelImg['FIELD_PARAM'];?> checked="" selected="" <?};?>
                                                       id="<?=$arLabelImg['ID']?>"
                                                       name="form_<?=$arLabelImg['FIELD_TYPE']?>_<?=$FIELD_SID?>"
                                                       value="<?=$arLabelImg['ID']?>">
                                                <span class="ans"><?=$arLabelImg['MESSAGE']?></span>
                                            </label>
                                        <?}?>
                                    </fieldset>
                                    <?/*=$arQuestion["IS_INPUT_CAPTION_IMAGE"] == "Y" ? "<br />".$arQuestion["IMAGE"]["HTML_CODE"]."<br />" : ""?>
                                    <?=$arQuestion["HTML_CODE"]*/?>
                                </div>
                            </div>

                            <? if(isset($arQuestion['Q_DATA'])): // select question
                                $arSetViewTarget[] = $FIELD_SID;
                                $this->SetViewTarget($FIELD_SID);?>
                                <? foreach ($arQuestion['Q_DATA'] as $i=>$q_data):?>
                                    <? if($i==0):?>
                                        <div id="<?=$q_data['FIELD_NAME'];?>" class="el-question">
                                            <?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
                                                <span class="error-fld" title="<?=htmlspecialcharsbx($arResult["FORM_ERRORS"][$FIELD_SID])?>"></span>
                                            <?endif;?>

                                            <div class="color-question title-question"><?=$q_data["CAPTION"]?>
                                                <?if ($q_data["REQUIRED"] == "Y"):?>
                                                    <?=$arResult["REQUIRED_SIGN"];?>
                                                <?endif;?>
                                            </div>
                                            <?=$q_data["IS_INPUT_CAPTION_IMAGE"] == "Y" ? "<br />".$q_data["IMAGE"]["HTML_CODE"]."<br />" : ""?>

                                            <div class="col-xs-12 color-plenki">Однотонная</div>
                                            <?foreach ($q_data["STRUCTURE"] as $arLabelImg)
                                            {
                                                $pos = strpos($arLabelImg['MESSAGE'], 'id="mono"');
                                                if($pos){?>
                                                    <label class="col-xs-4 col-sm-2 image-color-rb-q">
                                                        <input class="hidden-dot" type="<?=$arLabelImg['FIELD_TYPE']?>"
                                                            <?if($arLabelImg['FIELD_PARAM']){?> <?=$arLabelImg['FIELD_PARAM']?> checked="" <?}?>id="<?=$arLabelImg['ID']?>"
                                                            data-product-id="<?=$arLabelImg['VALUE'];?>"
                                                            name="form_radio_<?=$FIELD_SID.'_'.$i;?>"
                                                            value="<?=$arLabelImg['ID']?>">
                                                        <img class="checked" src="/upload/iblock/icons/checkmark_green.png"><?=$arLabelImg['MESSAGE']?>
                                                    </label>
                                                <?}//if $pos?>
                                            <?}?>

                                            <div class="col-xs-12 color-plenki">С рисунком</div>
                                            <?foreach ($q_data["STRUCTURE"] as $arLabelImg)
                                            {
                                                $pos = strpos($arLabelImg['MESSAGE'], 'id="picture"');
                                                if($pos){?>
                                                    <label class="col-xs-4 col-sm-2 image-color-rb-q">
                                                        <input class="hidden-dot" type="<?=$arLabelImg['FIELD_TYPE']?>"
                                                            <?if($arLabelImg['FIELD_PARAM']){?> <?=$arLabelImg['FIELD_PARAM']?> checked="" <?}?>id="<?=$arLabelImg['ID']?>"
                                                            data-product-id="<?=$arLabelImg['VALUE'];?>"
                                                            name="form_radio_<?=$FIELD_SID.'_'.$i;?>"
                                                            value="<?=$arLabelImg['ID']?>">
                                                        <img class="checked" src="/upload/iblock/icons/checkmark_green.png"><?=$arLabelImg['MESSAGE']?>
                                                    </label>
                                                <?}//if $pos?>
                                            <?}?>

                                            <div class="col-xs-12 color-plenki">Текстурная</div>
                                            <?foreach ($q_data["STRUCTURE"] as $arLabelImg)
                                            {
                                                $pos = strpos($arLabelImg['MESSAGE'], 'id="textures"');
                                                if($pos){?>
                                                    <label class="col-xs-4 col-sm-2 image-color-rb-q">
                                                        <input class="hidden-dot" type="<?=$arLabelImg['FIELD_TYPE']?>"
                                                            <?if($arLabelImg['FIELD_PARAM']){?> <?=$arLabelImg['FIELD_PARAM']?> checked="" <?}?>id="<?=$arLabelImg['ID']?>"
                                                            data-product-id="<?=$arLabelImg['VALUE'];?>"
                                                            name="form_radio_<?=$FIELD_SID.'_'.$i;?>"
                                                            value="<?=$arLabelImg['ID']?>">
                                                        <img class="checked" src="/upload/iblock/icons/checkmark_green.png"><?=$arLabelImg['MESSAGE']?>
                                                    </label>
                                                <?}//if $pos?>
                                            <?}?>

                                            <?//=$q_data["HTML_CODE"]?>
                                        </div>
                                    <?else:?>
                                        <div id="<?=$q_data['FIELD_NAME'];?>" class="el-question" style="display:none;">
                                            <?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
                                                <span class="error-fld" title="<?=htmlspecialcharsbx($arResult["FORM_ERRORS"][$FIELD_SID])?>"></span>
                                            <?endif;?>
                                            <div class="color-question title-question"><?=$q_data["CAPTION"]?>
                                                <?if ($q_data["REQUIRED"] == "Y"):?>
                                                    <?=$arResult["REQUIRED_SIGN"];?>
                                                <?endif;?>
                                            </div>
                                            <?foreach ($q_data["STRUCTURE"] as $arLabelImg)
                                            {?>
                                                <label class="col-xs-4 col-sm-3 rb-q">
                                                    <input class="hidden-dot"
                                                        type="<?=$arLabelImg['FIELD_TYPE']?>"
                                                        data-product-id="<?=$arLabelImg['VALUE'];?>"
                                                        <?if($arLabelImg['FIELD_PARAM']){?> <?=$arLabelImg['FIELD_PARAM']?> selected="" checked="" <?}?>id="<?=$arLabelImg['ID']?>"
                                                        name="form_radio_<?=$FIELD_SID.'_'.$i;?>"
                                                        value="<?=$arLabelImg['ID']?>">
                                                    <img class="checked" src="/upload/iblock/icons/checkmark_green.png"><?=$arLabelImg['MESSAGE']?>
                                                </label>
                                            <?}?>
                                        </div>
                                    <? endif;?>
                                <? endforeach; ?>
                                <?$this->EndViewTarget();?>
                            <? endif; // end select question ?>
                        <?}?>
                    <?}
                } //end foreach
                ?>
            </div>
            <div class="question block1-right col-xs-12 col-sm-8">
                <? foreach ($arSetViewTarget as $targetName) $APPLICATION->ShowViewContent($targetName);?>
            </div>
            <div class="total-price col-xs-12 col-sm-8">
                <div class="price-title">
                    Стоимость
                </div>
                <div class="price-product">
                    <label>Oборудование и материалы:</label>
                    <span id="pool-price-material">0 руб.</span>
                </div>
                <div class="price-work">
                    <label>Работы:</label>
                    <span id="pool-price-work">0 руб.</span>
                </div>
                <div class="price-full">
                    <label>Итого:</label>
                    <span id="pool-price-all">0 руб.</span>
                </div>
            </div>
        </div> <?// end class row?>

        <!-- FORM CAPTCHA -->
        <?
        if($arResult["isUseCaptcha"] == "Y")
        {
            ?>
            <div><b><?=GetMessage("FORM_CAPTCHA_TABLE_TITLE")?></b></div>
            <div>
                <input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" /><img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" />
            </div>
            <div>
                <div><?=GetMessage("FORM_CAPTCHA_FIELD_TITLE")?><?=$arResult["REQUIRED_SIGN"];?></div>
                <div><input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext" /></div>
            </div>
            <?
        } // isUseCaptcha
        ?>

        <!-- FORM FOOTER -->
        <div class="form-footer">
            <div>
                <input type="submit" name="web_form_submit"  value="Сканировать счет" />
            </div>

            <?/* // базовий вариант
            <div>
                <input <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?>
                        type="submit" name="web_form_submit"
                        value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" />

                <?if ($arResult["F_RIGHT"] >= 15):?>
                    <input type="hidden" name="web_form_apply" value="Y" />
                    <input type="submit" name="web_form_apply" value="<?=GetMessage("FORM_APPLY");?>" />
                <?endif;?>
                <input type="reset" value="<?=GetMessage("FORM_RESET");?>" />
            </div>
            */?>
        </div>
    </div>

    <p class="allert">
        <?=$arResult["REQUIRED_SIGN"];?> - <?=GetMessage("FORM_REQUIRED_FIELDS")?>
    </p>
    <?=$arResult["FORM_FOOTER"]?>
<?
if($arResult["isFormNote"] == 'Y')
{
    if(isset($_REQUEST['WEB_FORM_ID']))
    {
        $arResult["POOL_PARAMS"]['isOpenPdf']['formId'] = $_REQUEST['WEB_FORM_ID'];
    }
    if(isset($_REQUEST['RESULT_ID']))
    {
        $arResult["POOL_PARAMS"]['isOpenPdf']['resultId'] = $_REQUEST['RESULT_ID'];
    }
    if(isset($_REQUEST['formresult']))
    {
        $arResult["POOL_PARAMS"]['isOpenPdf']['formResult'] = $_REQUEST['formresult'];
    }
    $arResult["POOL_PARAMS"]['isOpenPdf']['pdf'] = 'Y';
}
?>

<script>
    BX.ready(function () {
        var variablePoolCalculate = BX.PoolCalculationPrise.init(<?=CUtil::PhpToJSObject($arResult["POOL_PARAMS"])?>);
    });
</script>


<?/*
<pre style="display:none;">
    <?php
    if($USER->IsAdmin() && $USER->GetID() == 8)
    {
        print_r($arResult);
    }
    ?>
</pre>
*/?>