<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>
<?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>

<?=$arResult["FORM_NOTE"]?>

<?if ($arResult["isFormNote"] != "Y")
{
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
                    <h3>Заполните форму</h3>
                    <?
                } //endif ;
                if ($arResult["isFormImage"] == "Y")
                {
                    ?>
                    <a href="<?=$arResult["FORM_IMAGE"]["URL"]?>" target="_blank" alt="<?=GetMessage("FORM_ENLARGE")?>"><img src="<?=$arResult["FORM_IMAGE"]["URL"]?>" <?if($arResult["FORM_IMAGE"]["WIDTH"] > 300):?>width="300"<?elseif($arResult["FORM_IMAGE"]["HEIGHT"] > 200):?>height="200"<?else:?><?=$arResult["FORM_IMAGE"]["ATTR"]?><?endif;?> hspace="3" vscape="3" border="0" /></a>
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

        <?
        $FIELD_SID_FIND = 1;
        foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
        {
        if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden')
        {
            echo $arQuestion["HTML_CODE"];
        }
        else
        {

            if(($FIELD_SID != "new_field_0003") && ($FIELD_SID_FIND == 1)){
                $FIELD_SID_FIND++;?>
                 <div class="row <?=$FIELD_SID_FIND?>">
                 <div class="question block1-left col-xs-12 col-sm-3">
            <?}elseif($FIELD_SID == "new_field_0003" || $FIELD_SID == "new_field_0003_1"){
                $FIELD_SID_FIND++;?>
                </div>
                <div class="question block1-right col-xs-12 col-sm-9">
            <?}elseif($FIELD_SID != "new_field_0003" && $FIELD_SID_FIND == 3){
                    $FIELD_SID_FIND++;?>
                    </div></div>
                    <div class="row">
                    <div class="question block2-left col-xs-12 col-sm-3">
            <?}?>

                <?if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'text'){?>
                    <div class="el-question">
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
                            <input placeholder="<?=$arTypetext['MESSAGE']?>" type="<?=$arTypetext['FIELD_TYPE']?>" class="inputtext" name="form_<?=$arTypetext['FIELD_TYPE']?>_<?=$arTypetext['ID']?>" value="" size="0"><br>
                        <?}?>

                        <?//=$arQuestion["HTML_CODE"]?>
                    </div>
                <?}elseif($FIELD_SID == "new_field_0003" || $FIELD_SID == "new_field_0003_1"){?>
                    <div class="el-question new_field_0003">
                        <?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
                            <span class="error-fld" title="<?=htmlspecialcharsbx($arResult["FORM_ERRORS"][$FIELD_SID])?>"></span>
                        <?endif;?>

                        <div class="color-question title-question"><?=$arQuestion["CAPTION"]?>
                            <?if ($arQuestion["REQUIRED"] == "Y"):?>
                                <?=$arResult["REQUIRED_SIGN"];?>
                            <?endif;?>
                        </div>
                        <?=$arQuestion["IS_INPUT_CAPTION_IMAGE"] == "Y" ? "<br />".$arQuestion["IMAGE"]["HTML_CODE"]."<br />" : ""?>

                        <div class="col-xs-12 color-plenki">Однотонная</div>
                        <?foreach ($arQuestion["STRUCTURE"] as $arLabelImg)
                        {
                            $pos = strpos($arLabelImg['MESSAGE'], 'id="mono"');
                            if($pos){?>
                                <label class="col-xs-4 col-sm-2 image-color-rb-q"><input class="hidden-dot" type="<?=$arLabelImg['FIELD_TYPE']?>" <?if($arLabelImg['FIELD_PARAM']){?> <?=$arLabelImg['FIELD_PARAM']?> checked="" <?}?>id="<?=$arLabelImg['ID']?>" name="form_radio_<?=$FIELD_SID?>" value="<?=$arLabelImg['ID']?>"><img class="checked" src="/upload/iblock/icons/checkmark_green.png"><?=$arLabelImg['MESSAGE']?></label>
                            <?}//if $pos?>
                        <?}?>

                        <div class="col-xs-12 color-plenki">С рисунком</div>
                        <?foreach ($arQuestion["STRUCTURE"] as $arLabelImg)
                        {
                            $pos = strpos($arLabelImg['MESSAGE'], 'id="picture"');
                            if($pos){?>
                                <label class="col-xs-4 col-sm-2 image-color-rb-q"><input class="hidden-dot" type="<?=$arLabelImg['FIELD_TYPE']?>" <?if($arLabelImg['FIELD_PARAM']){?> <?=$arLabelImg['FIELD_PARAM']?> checked="" <?}?>id="<?=$arLabelImg['ID']?>" name="form_radio_<?=$FIELD_SID?>" value="<?=$arLabelImg['ID']?>"><img class="checked" src="/upload/iblock/icons/checkmark_green.png"><?=$arLabelImg['MESSAGE']?></label>
                            <?}//if $pos?>
                        <?}?>


                        <div class="col-xs-12 color-plenki">Текстурная</div>
                        <?foreach ($arQuestion["STRUCTURE"] as $arLabelImg)
                        {
                            $pos = strpos($arLabelImg['MESSAGE'], 'id="textures"');
                            if($pos){?>
                                <label class="col-xs-4 col-sm-2 image-color-rb-q"><input class="hidden-dot" type="<?=$arLabelImg['FIELD_TYPE']?>" <?if($arLabelImg['FIELD_PARAM']){?> <?=$arLabelImg['FIELD_PARAM']?> checked="" <?}?>id="<?=$arLabelImg['ID']?>" name="form_radio_<?=$FIELD_SID?>" value="<?=$arLabelImg['ID']?>"><img class="checked" src="/upload/iblock/icons/checkmark_green.png"><?=$arLabelImg['MESSAGE']?></label>
                            <?}//if $pos?>
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
                            <? if($USER->IsAdmin()) {
                                //echo '<pre> $arQuestion= <br/>'; print_r($arQuestion); echo '</pre>';
                            } ?>
                            <?foreach ($arQuestion["STRUCTURE"] as $arLabelImg)
                            {?>
                                <label class="col-xs-12 rb-q">
                                    <input class="radio-dot" type="<?=$arLabelImg['FIELD_TYPE']?>" data-product-id="<?=$arLabelImg['VALUE'];?>"
                                        <?if($arLabelImg['FIELD_PARAM']){?> <?=$arLabelImg['FIELD_PARAM']?> selected="" checked="" <?}?>id="<?=$arLabelImg['ID']?>" name="form_<?=$arLabelImg['FIELD_TYPE']?>_<?=$FIELD_SID?>" value="<?=$arLabelImg['ID']?>">
                                    <span class="ans"><?=$arLabelImg['MESSAGE']?></span>

                                    <pre style="display:none;"><?=print_r($arLabelImg);?></pre>

                                </label>
                            <?}?>
                            <?/*=$arQuestion["IS_INPUT_CAPTION_IMAGE"] == "Y" ? "<br />".$arQuestion["IMAGE"]["HTML_CODE"]."<br />" : ""?>
					<?=$arQuestion["HTML_CODE"]*/?>
                        </div>
                    </div>
                <?}?>
                <?
                }
                } //endwhile
                ?>
            </div></div><?// end class row?>
        <?
        if($arResult["isUseCaptcha"] == "Y")
        {
            ?>
            <div><b><?=GetMessage("FORM_CAPTCHA_TABLE_TITLE")?></b>
            </div>
            <div><input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" /><img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" />
            </div>
            <div>
                <div><?=GetMessage("FORM_CAPTCHA_FIELD_TITLE")?><?=$arResult["REQUIRED_SIGN"];?></div>
                <div><input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext" /></div>
            </div>
            <?
        } // isUseCaptcha
        ?>

        <div class="form-footer">

            <div>
                <input <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> type="submit" name="web_form_submit" value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" />
                <?if ($arResult["F_RIGHT"] >= 15):?>
                    &nbsp;<input type="hidden" name="web_form_apply" value="Y" /><input type="submit" name="web_form_apply" value="<?=GetMessage("FORM_APPLY")?>" />
                <?endif;?>
                &nbsp;<input type="reset" value="<?=GetMessage("FORM_RESET");?>" />
            </div>

        </div>
    </div>
    <p>
        <?=$arResult["REQUIRED_SIGN"];?> - <?=GetMessage("FORM_REQUIRED_FIELDS")?>
    </p>
    <?=$arResult["FORM_FOOTER"]?>
    <?
} //endif (isFormNote)
?>
<script>
    var variablePoolCalculate = new BXPoolCalculationPrise(<?=CUtil::PhpToJSObject($arResult["POOL_PARAMS"])?>);
</script>