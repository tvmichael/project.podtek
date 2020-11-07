<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Частые вопросы");
$APPLICATION->SetAdditionalCss($APPLICATION->GetCurPage() . "style.css");
$APPLICATION->AddHeadScript($APPLICATION->GetCurPage() . 'script.js');
?>
<?if($USER->IsAdmin()):?>
<div class="row faq-page">
    <?
    $IBLOCK_ID = 16;
    $dbSectionTree = [];

    $res = CIBlock::GetByID($IBLOCK_ID);
    if($ar = $res->GetNext())
    {
        $dbSectionTree[$IBLOCK_ID] = [
            'ID' => $ar['ID'],
            'NAME' => $ar['NAME'],
            'SECTIONS_NAME' => $ar['SECTIONS_NAME'],
        ];
    }
    $dbSectionTree[$IBLOCK_ID]['CHILDREN'] = [];

    $res = CIBlockSection::GetList(
        Array('LEFT_MARGIN' => 'ASC'),
        array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y'),
        false,
        ['ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'NAME', 'PICTURE', 'DEPTH_LEVEL', 'DESCRIPTION']
    );
    while( $ar = $res-> GetNext(true, false) )
    {
        $dbSectionTree[$ar['ID']] = $ar;
        $dbSectionTree[$ar['ID']]['CHILDREN'] = [];
    }

    $res = CIBlockElement::GetList(
        Array('SORT' => 'ASC'),
        ['IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y'],
        false,
        false,
        ['ID', 'IBLOCK_SECTION_ID', 'NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_PICTURE', 'DETAIL_TEXT']
    );
    while ($ar = $res->Fetch())
    {
        if(isset($dbSectionTree[$ar['IBLOCK_SECTION_ID']]))
        {
            $dbSectionTree[$ar['IBLOCK_SECTION_ID']]['CHILDREN'][$ar['ID']] = $ar;
        }
        else
        {
            $dbSectionTree[$IBLOCK_ID]['CHILDREN'][$ar['ID']] = $ar;
        }
    }
    //print_r($dbSectionTree);
    ?>

    <div class="faq-menu col-sm-4">
    <?
    $ar = 0;
    foreach ($dbSectionTree as $item):?>
        <div class="faq-menu-item <?=($ar==0?'selected':'');?>" data-id="<?=$item['ID'];?>">
            <?=$item['NAME'];?>
        </div>
    <?
    $ar++;
    endforeach;?>
    </div>
    <div class="faq-contain col-sm-8">
    <?
    $ar = 0;
    foreach ($dbSectionTree as $item):?>
        <div class="faq-contain-item <?=($ar==0?'selected':'');?>" data-id="<?=$item['ID'];?>">
            <?if(!empty($item['CHILDREN'])):?>
                <?foreach ($item['CHILDREN'] as $child):?>
                    <div class="faq-contain-item-detail">
                        <h4><?=$child['NAME'];?></h4>
                        <div class="faq-contain-item-text"><?=$child['DETAIL_TEXT'];?></div>
                    </div>
                <?endforeach;?>
            <?endif;?>
        </div>
    <?
    $ar++;
    endforeach;?>
    </div>
</div>
<?else:?>
    <img width="512" src="https://podtek.ru/upload/medialibrary/3b1/3b1320ab5e12fb5ff59a97f280c3ddfd.jpg" height="502">
<?endif;?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>