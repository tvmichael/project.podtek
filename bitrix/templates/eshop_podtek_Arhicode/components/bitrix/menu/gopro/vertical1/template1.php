<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

$this->setFrameMode(true);
$arParams['RSGOPRO_CATALOG_PATH'] = (empty($arParams['RSGOPRO_CATALOG_PATH']) ? SITE_DIR.'catalog/' : $arParams['RSGOPRO_CATALOG_PATH']);
?>
<?if(is_array($arResult) && count($arResult)>0) :?>
    <div class="catalogmenucolumn">
        <ul class="catalogmenu list-unstyled clearfix">
            <li class="parent">
                <a href="<?=$arParams['RSGOPRO_CATALOG_PATH']?>" class="parent">
                    Все разделы
                    <?//=GetMessage('RSGOPRO_CATALOG')?>
                    <img class="svg-icon menu" src="<?=$templateFolder?>/images/list.svg" alt="menu">
                    <!--svg class="svg-icon menu"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-menu"></use></svg-->
                </a>
                <ul class="first list-unstyled clearfix lvl1<?if($arParams['IS_MAIN']=='Y'):?> rs-show<?endif;?>">
                    <?
                    $previousLevel = 0;
                    $index = 1;
                    $max = $arParams['RSGOPRO_MAX_ITEM'];

                    $max = 0;
                    foreach($arResult as $key => $arItem)
                    {
                        if($arParams['MAX_LEVEL'] == 2)
                        {
                            if ($arItem['DEPTH_LEVEL'] > 2)
                            {
                                unset($arResult[$key]);
                                continue;
                            }

                            if ($arItem['DEPTH_LEVEL'] == 2)
                            {
                                $arResult[$key]['IS_PARENT'] = false;
                                continue;
                            }
                        }

                        if ($arItem['DEPTH_LEVEL'] == 1)
                        {
                            $max++;
                        }
                    }

                    $last_lvl1 = false;
                    foreach($arResult as $arItem)
                    {
                        if ($previousLevel > 0 && $arItem['DEPTH_LEVEL'] < $previousLevel)
                        {
                            echo str_repeat("</ul></li><!-- the end -->", ($previousLevel - $arItem['DEPTH_LEVEL'] - 1));
                            echo "</ul></li>";
                        }
                        if ($arItem['DEPTH_LEVEL'] == 1)
                        {
                            $last_lvl1 = $arItem['ITEM_INDEX'];
                        }
                        if ($arItem['IS_PARENT'])
                        {
                            if ($arItem['DEPTH_LEVEL'] == 1)
                            {?>
                                <li class="first<?if ($index>$max):?> more<?endif;?><?if($arItem['IS_LAST_LVL1']=='Y'):?> lastchild<?endif;?><?=(!empty($arItem['DETAIL_PICTURE']) ? ' catalogmenu-icon' : '')?>">
                                    <a href="<?=$arItem['LINK']?>" class="first<?if($arItem['SELECTED']):?> selected<?endif?>" title="<?=$arItem['TEXT']?>">
                                        <?/*if (!empty($arItem['DETAIL_PICTURE']))
                                        {?>
                                            <img class="catalogmenu__icon" src="<?=$arItem['DETAIL_PICTURE']?>" alt="catalogmenu__icon" title=""><?
                                        }*/?>
                                        <span><?=$arItem['TEXT']?></span>
                                        <img class="svg-icon arrow" src="../../images/right-arrow.svg" alt="arrow">
                                        <!--svg class="svg-icon arrow"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-linear-right"></use></svg-->
                                    </a>
                                    <ul class="list-unstyled lvl<?if($arItem['DEPTH_LEVEL']>3):?>4<?else:?><?=($arItem['DEPTH_LEVEL']+1)?><?endif;?>">
                                        <li class="sub-img">
                                            <a href="<?=$arItem['LINK']?>" title="<?=$arItem['TEXT']?>">
                                                <img class="catalog-menu-img" src="<?=$arItem['DETAIL_PICTURE']?>" alt="catalog-menu-icon" title="">
                                            </a>
                                        </li>
                                <?$index++;
                            }
                            else
                            {?>
                                <li class="sub <?if ($arItem['SELECTED']):?> selected<?endif?>">
                                    <a href="<?=$arItem['LINK']?>" class="sub" title="<?=$arItem['TEXT']?>">
                                        <?=$arItem['TEXT']?>
                                        <!--svg class="svg-icon arrow"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-linear-right"></use></svg-->
                                    </a>
                                    <ul class="list-unstyled lvl<?if($arItem['DEPTH_LEVEL']>3):?>4<?else:?><?=($arItem['DEPTH_LEVEL']+1)?><?endif;?>">
                            <?}
                        }
                        else
                        {
                            if ($arItem['DEPTH_LEVEL'] == 1)
                            {?>
                                <li class="first<?if($index>$max):?> more<?endif;?><?if($arItem['IS_LAST_LVL1']=='Y'):?> lastchild<?endif;?><?=(!empty($arItem['DETAIL_PICTURE']) ? ' catalogmenu-icon' : '')?>">
                                    <a href="<?=$arItem['LINK']?>" class="first<?if($arItem['SELECTED']):?> selected<?endif?>" title="<?=$arItem['TEXT']?>">
                                        <?/* if (!empty($arItem['DETAIL_PICTURE']))
                                        {?>
                                            <img class="catalogmenu__icon" src="<?=$arItem['DETAIL_PICTURE']?>" alt="catalogmenu__icon" title=""><?
                                        }*/?>
                                        <span><?=$arItem['TEXT']?></span>
                                    </a>
                                </li>
                                <?$index++;
                            }
                            else
                            {?>
                                <li class="sub <?if($arItem['SELECTED']):?> selected<?endif?>">
                                    <a href="<?=$arItem['LINK']?>" class="sub" title="<?=$arItem['TEXT']?>">
                                        <?=$arItem['TEXT']?>
                                    </a>
                                </li>
                            <?}
                        }
                        $previousLevel = $arItem['DEPTH_LEVEL'];
                    }

                    if($previousLevel > 1)
                    {
                        echo str_repeat('</ul></li>', ($previousLevel-2) );
                        if($last_lvl1!==false && $arResult[$last_lvl1]['PARAMS']['ELEMENT']=='Y'){
                            RSGoProCatalogMenuElement($arResult[$last_lvl1]['PARAMS']['ELEMENT_ID'],$arParams);
                        }
                        echo '</ul></li>';
                    }
                    if($index > ($max + 1))
                    {?>
                        <li class="first morelink lastchild">
                            <a href="<?=$arParams['RSGOPRO_CATALOG_PATH']?>" class="first morelink">
                                <!--svg class="svg-icon svg-icon__morelink"><use xlink:href="#svg-more"></use></svg><svg class="svg-icon arrow"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-linear-right"></use></svg-->
                            </a>
                        </li><?
                    }?>
                </ul>
            </li>
        </ul>
        <ul class="catalogmenusmall clearfix">
            <li class="parent">
                <a href="<?=$arParams['RSGOPRO_CATALOG_PATH']?>" class="parent">
                    <?=GetMessage('RSGOPRO_CATALOG')?>
                    <svg class="svg-icon menu">
                        <!--use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-menu"></use-->
                    </svg>
                </a>
            <ul class="first list-unstyled clearfix lvl1 noned">
                <? foreach($arResult as $arItem)
                {
                    if($arItem['DEPTH_LEVEL'] == 1)
                    {?>
                        <li class="first<?if($arItem['IS_LAST_LVL1']=='Y'):?> lastchild<?endif;?>">
                            <a href="<?=$arItem['LINK']?>" class="first<?if($arItem['SELECTED']):?> selected<?endif?>">
                                <?=$arItem['TEXT']?>
                            </a>
                        </li>
                    <?}
                }?>
            </ul>
        </ul>
    </div>
<?endif;?>
<pre style="width:900px;position:absolute;top:100px;z-index:20;">
    <?
    //print_r($arResult);
    print_r($arParams);
    foreach ($arResult as $item)
    {
        echo $item['TEXT'];
        echo '<br>';
        echo '- '.$item['DEPTH_LEVEL'].'_____'. $item['IS_PARENT'];
        echo '<br>';
    }
    ?>
</pre>