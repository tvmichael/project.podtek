<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Частые вопросы");
$APPLICATION->SetAdditionalCss($APPLICATION->GetCurPage() . "style.css");
$APPLICATION->AddHeadScript($APPLICATION->GetCurPage() . 'script.js');
?>
<div class="row faq-page">
    <?
    $IBLOCK_ID = 16;
    $dbSectionTree = [];
    $res = CIBlockElement::GetList(
        Array('SORT' => 'ASC'),
        ['IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y'],
        false,
        false,
        ['ID', 'IBLOCK_SECTION_ID', 'NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_PICTURE', 'DETAIL_TEXT']
    );
    while ($ar = $res->Fetch())
    {
        $dbSectionTree[$ar['ID']] = $ar;
    }
    ?>
    <div class="faq-menu col-sm-5 col-md-4 col-lg-3">
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
    <div class="faq-contain col-sm-7 col-md-8 col-lg-9">
    <?
    $ar = 0;
    foreach ($dbSectionTree as $item):?>
        <div class="faq-contain-item <?=($ar==0?'selected':'');?>" data-id="<?=$item['ID'];?>">
            <?=$item['DETAIL_TEXT'];?>
        </div>
    <?
    $ar++;
    endforeach;?>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>