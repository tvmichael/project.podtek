<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var CatalogSectionComponent $component
 */

if ($haveOffers)
{
	$showDisplayProps = !empty($item['DISPLAY_PROPERTIES']);
	$showProductProps = $arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $item['OFFERS_PROPS_DISPLAY'];
	$showPropsBlock = $showDisplayProps || $showProductProps;
	$showSkuBlock = $arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && !empty($item['OFFERS_PROP']);
}
else
{
	$showDisplayProps = !empty($item['DISPLAY_PROPERTIES']);
	$showProductProps = $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !empty($item['PRODUCT_PROPERTIES']);
	$showPropsBlock = $showDisplayProps || $showProductProps;
	$showSkuBlock = false;
}
?>

<div class="row product-item">
	<!--div class="col-xs-12">
		<div class="product-item-title_1">
			<a href="<!--?=$item['DETAIL_PAGE_URL']?>" title="<!--?=$productTitle?>"><!--?=$productTitle?></a>
		</div>
	</div-->
	<div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
        <a class="product-item-image-wrapper" href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$imgTitle?>"
           data-entity="image-wrapper">
			<!--span class="product-item-image-slider-slide-container slide" id="<?=$itemIds['PICT_SLIDER']?>"
				<!--?=($showSlider ? '' : 'style="display: none;"')?>
				data-slider-interval="<!--?=$arParams['SLIDER_INTERVAL']?>" data-slider-wrap="true">
				<!--?
				if ($showSlider)
				{
					foreach ($morePhoto as $key => $photo)
					{
						?>
						<span class="product-item-image-slide item <!--?=($key == 0 ? 'active' : '')?>"
							style="background-image: url('<!--?=$photo['SRC']?>');">
						</span>
						<!--?
					}
				}
				?>
			</span-->
			<span class="product-item-image-original" id="<?=$itemIds['PICT_SLIDER']?>"
				style="background-image: url('<?=$item['PREVIEW_PICTURE']['SRC']?>'); ">
			</span>
			<!--?
			if ($item['SECOND_PICT'])
			{
				$bgImage = !empty($item['PREVIEW_PICTURE_SECOND']) ? $item['PREVIEW_PICTURE_SECOND']['SRC'] : $item['PREVIEW_PICTURE']['SRC'];
				?>
				<span class="product-item-image-alternative" id="<!--?=$itemIds['SECOND_PICT']?>"
					style="background-image: url('<!--?=$bgImage?>'); ">
				</span>
				<!-?
			}
			?-->
			<!--div class="product-item-image-slider-control-container" id="<!--?=$itemIds['PICT_SLIDER']?>_indicator"
				<!--?=($showSlider ? '' : 'style="display: none;"')?>>
				<!--?
				if ($showSlider)
				{
					foreach ($morePhoto as $key => $photo)
					{
						?>
						<div class="product-item-image-slider-control<!--?=($key == 0 ? ' active' : '')?>" data-go-to="<!--?=$key?>"></div>
						<!--?
					}
				}
				?>
			</div-->
			<?
			if ($arParams['SLIDER_PROGRESS'] === 'Y')
			{
				?>
				<div class="product-item-image-slider-progress-bar-container">
					<div class="product-item-image-slider-progress-bar" id="<?=$itemIds['PICT_SLIDER']?>_progress_bar" style="width: 0;"></div>
				</div>
				<?
			}
			?>
		</a>
	</div>
	<?
	if (!$haveOffers)
	{
		if ($showPropsBlock)
		{
			?>
			<div class="col-xs-12 col-sm-6 col-md-9 col-lg-10">
				<?
				if ($showDisplayProps)
				{
					?>
					<div class="product-item-info-container" data-entity="props-block">
						<dl class="product-item-properties">
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							<h4 class="bx-title"><?=$item['DISPLAY_PROPERTIES']['FIO']['VALUE']?></h4>
							<h6 class="bx-title"><?=$item['DISPLAY_PROPERTIES']['PHONE']['VALUE']?></h6>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							<div><h6 class="bx-title" style="float:right;"><?=$item['DISPLAY_PROPERTIES']['KIND_OF_ACTIVITY']['VALUE']?></h6></div>
							<div class="col-xs-12">
                                <?
                                if (!empty($morePhoto))
                                {
                                    $slidesToShow = 6;
                                    $slidesToShowList = false;
                                    if(count($morePhoto) > $slidesToShow) $slidesToShowList = true;
                                    else $slidesToShow = count($morePhoto);
                                }
                                else $slidesToShow = 0;
                                ?>
                                <div class="bx-photo-list-container" style="width:<?=$slidesToShow*85;?>px;">
                                    <!--div class="product-item-detail-slider-images-container" data-entity="images-container">
                                    <?/*
                                    if (!empty($morePhoto))
                                    {
                                        foreach ($morePhoto as $key => $photo)
                                        {
                                            ?>
                                            <div class="product-item-detail-slider-image <?=($key==0?'active':'');?>" data-entity="image" data-id="<?=$photo['ID']?>">
                                                <img src="<?=$photo['SRC']?>" alt="<?=$alt?>" title="<?=$title?>"<?=($key == 0 ? ' itemprop="image"' : '')?>>
                                            </div>
                                            <?
                                        }
                                    }
                                    if ($arParams['SLIDER_PROGRESS'] === 'Y')
                                    {
                                        ?>
                                        <div class="product-item-detail-slider-progress-bar" data-entity="slider-progress-bar" style="width: 0;"></div>
                                        <?
                                    }
                                    */?>
                                    </div-->
                                    <div class="bx-photo-slider-full" >
                                        <div id="slider_<?php echo $itemIds['ID'];?>" class="bx-photo-slider">
                                            <?
                                            if (!empty($morePhoto))
                                            {
                                                foreach ($morePhoto as $key => $photo)
                                                {
                                                    ?>
                                                    <div class="bx-link" style="background-image: url('<?=$photo['SRC']?>');" title="<?=$imgTitle?>">
                                                        <a href="<?=$item['DETAIL_PAGE_URL']?>"></a>
                                                    </div>
                                                    <?
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div id="list_<?echo $itemIds['ID'];?>" class="bx-photo-list">
                                        <?
                                        if (!empty($morePhoto))
                                        {
                                            foreach ($morePhoto as $key => $photo)
                                            {
                                                ?>
                                                <div>
                                                    <div class="bx-photo-list-img" data-key-id="<?=$key;?>">
                                                        <img src="<?=$photo['SRC']?>">
                                                    </div>
                                                </div>
                                                <?
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
							</div>
							<div><h6 class="bx-title" style = "float: right;"><?=$item['DISPLAY_PROPERTIES']['CITY']['VALUE']?></h6></div>
						</div>
						<!--?if($USER->IsAdmin()) {echo '<pre>'; print_r($item['DISPLAY_PROPERTIES']['PHOTO']); echo '</pre>';}?-->
						</dl>
					</div>
					<?
				}

				if ($showProductProps)
				{
					?>
					<div id="<?=$itemIds['BASKET_PROP_DIV']?>" style="display: none;">
						<?
						if (!empty($item['PRODUCT_PROPERTIES_FILL']))
						{
							foreach ($item['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
							{
								?>
								<input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propID?>]"
									value="<?=htmlspecialcharsbx($propInfo['ID'])?>">
								<?
								unset($item['PRODUCT_PROPERTIES'][$propID]);
							}
						}

						if (!empty($item['PRODUCT_PROPERTIES']))
						{
							?>
							<table>
								<?
								foreach ($item['PRODUCT_PROPERTIES'] as $propID => $propInfo)
								{
									?>
									<tr>
										<td><?=$item['PROPERTIES'][$propID]['NAME']?></td>
										<td>
											<?
											if (
												$item['PROPERTIES'][$propID]['PROPERTY_TYPE'] === 'L'
												&& $item['PROPERTIES'][$propID]['LIST_TYPE'] === 'C'
											)
											{
												foreach ($propInfo['VALUES'] as $valueID => $value)
												{
													?>
													<label>
														<? $checked = $valueID === $propInfo['SELECTED'] ? 'checked' : ''; ?>
														<input type="radio" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propID?>]"
															value="<?=$valueID?>" <?=$checked?>>
														<?=$value?>
													</label>
													<br />
													<?
												}
											}
											else
											{
												?>
												<select name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propID?>]">
													<?
													foreach ($propInfo['VALUES'] as $valueID => $value)
													{
														$selected = $valueID === $propInfo['SELECTED'] ? 'selected' : '';
														?>
														<option value="<?=$valueID?>" <?=$selected?>>
															<?=$value?>
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
				?>
			</div>
			<?
		}
	}
	else
	{
		if ($showPropsBlock)
		{
			?>
			<div class="col-xs-12 col-sm-6 <?=($showSkuBlock ? 'col-md-4 col-lg-5' : 'col-md-6 col-lg-7')?>">
				<div class="product-item-info-container" data-entity="props-block">
					<dl class="product-item-properties">
						<?
						if ($showDisplayProps)
						{
							foreach ($item['DISPLAY_PROPERTIES'] as $code => $displayProperty)
							{
								?>
								<dt<?=(!isset($item['PROPERTY_CODE_MOBILE'][$code]) ? ' class="hidden-xs"' : '')?>>
									<?=$displayProperty['NAME']?>
								</dt>
								<dd<?=(!isset($item['PROPERTY_CODE_MOBILE'][$code]) ? ' class="hidden-xs"' : '')?>>
									<?=(is_array($displayProperty['DISPLAY_VALUE'])
										? implode(' / ', $displayProperty['DISPLAY_VALUE'])
										: $displayProperty['DISPLAY_VALUE'])?>
								</dd>
								<?
							}
						}

						if ($showProductProps)
						{
							?>
							<span id="<?=$itemIds['DISPLAY_PROP_DIV']?>" style="display: none;"></span>
							<?
						}
						?>
					</dl>
				</div>
			</div>
			<?
		}

		if ($showSkuBlock)
		{
			?>
			<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2<?=($showPropsBlock ? '' : ' col-md-offset-4 col-lg-offset-5')?>">
				<div id="<?=$itemIds['PROP_DIV']?>">
					<?
					foreach ($arParams['SKU_PROPS'] as $skuProperty)
					{
						$propertyId = $skuProperty['ID'];
						$skuProperty['NAME'] = htmlspecialcharsbx($skuProperty['NAME']);
						if (!isset($item['SKU_TREE_VALUES'][$propertyId]))
							continue;
						?>
						<div class="product-item-info-container" data-entity="sku-block">
							<div class="product-item-scu-container" data-entity="sku-line-block">
								<?=$skuProperty['NAME']?>
								<div class="product-item-scu-block">
									<div class="product-item-scu-list">
										<ul class="product-item-scu-item-list">
											<?
											foreach ($skuProperty['VALUES'] as $value)
											{
												if (!isset($item['SKU_TREE_VALUES'][$propertyId][$value['ID']]))
													continue;

												$value['NAME'] = htmlspecialcharsbx($value['NAME']);

												if ($skuProperty['SHOW_MODE'] === 'PICT')
												{
													?>
													<li class="product-item-scu-item-color-container" title="<?=$value['NAME']?>"
														data-treevalue="<?=$propertyId?>_<?=$value['ID']?>" data-onevalue="<?=$value['ID']?>">
														<div class="product-item-scu-item-color-block">
															<div class="product-item-scu-item-color" title="<?=$value['NAME']?>"
																style="background-image: url('<?=$value['PICT']['SRC']?>');">
															</div>
														</div>
													</li>
													<?
												}
												else
												{
													?>
													<li class="product-item-scu-item-text-container" title="<?=$value['NAME']?>"
														data-treevalue="<?=$propertyId?>_<?=$value['ID']?>" data-onevalue="<?=$value['ID']?>">
														<div class="product-item-scu-item-text-block">
															<div class="product-item-scu-item-text"><?=$value['NAME']?></div>
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
				foreach ($arParams['SKU_PROPS'] as $skuProperty)
				{
					if (!isset($item['OFFERS_PROP'][$skuProperty['CODE']]))
						continue;

					$skuProps[] = array(
						'ID' => $skuProperty['ID'],
						'SHOW_MODE' => $skuProperty['SHOW_MODE'],
						'VALUES' => $skuProperty['VALUES'],
						'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
					);
				}

				unset($skuProperty, $value);

				if ($item['OFFERS_PROPS_DISPLAY'])
				{
					foreach ($item['JS_OFFERS'] as $keyOffer => $jsOffer)
					{
						$strProps = '';

						if (!empty($jsOffer['DISPLAY_PROPERTIES']))
						{
							foreach ($jsOffer['DISPLAY_PROPERTIES'] as $displayProperty)
							{
								$strProps .= '<dt>'.$displayProperty['NAME'].'</dt><dd>'
									.(is_array($displayProperty['VALUE'])
										? implode(' / ', $displayProperty['VALUE'])
										: $displayProperty['VALUE'])
									.'</dd>';
							}
						}
						$item['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;
					}
					unset($jsOffer, $strProps);
				}
				?>
			</div>
			<?
		}
	}
	?>
	<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3<?=($showPropsBlock || $showSkuBlock ? '' : ' col-md-offset-6 col-lg-offset-7')?>" style = "display: none;">
		<div class="product-line-item-info-right-container">
			<?
			foreach ($arParams['PRODUCT_BLOCKS_ORDER'] as $blockName)
			{
				switch ($blockName)
				{
					case 'price': ?>
						<div class="product-item-info-container product-item-price-container" data-entity="price-block">
							<?
							if ($arParams['SHOW_OLD_PRICE'] === 'Y')
							{
								?>
								<span class="product-item-price-old" id="<?=$itemIds['PRICE_OLD']?>"
									<?=($price['RATIO_PRICE'] >= $price['RATIO_BASE_PRICE'] ? 'style="display: none;"' : '')?>>
									<?=$price['PRINT_RATIO_BASE_PRICE']?>
								</span>&nbsp;
								<?
							}
							?>
							<span class="product-item-price-current" id="<?=$itemIds['PRICE']?>">
								<?
								if (!empty($price))
								{
									if ($arParams['PRODUCT_DISPLAY_MODE'] === 'N' && $haveOffers)
									{
										echo Loc::getMessage(
											'CT_BCI_TPL_MESS_PRICE_SIMPLE_MODE',
											array(
												'#PRICE#' => $price['PRINT_RATIO_PRICE'],
												'#VALUE#' => $measureRatio,
												'#UNIT#' => $minOffer['ITEM_MEASURE']['TITLE']
											)
										);
									}
									else
									{
										echo $price['PRINT_RATIO_PRICE'];
									}
								}
								?>
							</span>
						</div>
						<?
						break;

					case 'quantityLimit':
						if ($arParams['SHOW_MAX_QUANTITY'] !== 'N')
						{
							if ($haveOffers)
							{
								if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
								{
									?>
									<div class="product-item-info-container product-item-hidden"
										id="<?=$itemIds['QUANTITY_LIMIT']?>"
										style="display: none;"
										data-entity="quantity-limit-block">
										<div class="product-item-info-container-title">
											<?=$arParams['MESS_SHOW_MAX_QUANTITY']?>:
											<span class="product-item-quantity" data-entity="quantity-limit-value"></span>
										</div>
									</div>
									<?
								}
							}
							else
							{
								if (
									$measureRatio
									&& (float)$actualItem['CATALOG_QUANTITY'] > 0
									&& $actualItem['CATALOG_QUANTITY_TRACE'] === 'Y'
									&& $actualItem['CATALOG_CAN_BUY_ZERO'] === 'N'
								)
								{
									?>
									<div class="product-item-info-container product-item-hidden" id="<?=$itemIds['QUANTITY_LIMIT']?>">
										<div class="product-item-info-container-title">
											<?=$arParams['MESS_SHOW_MAX_QUANTITY']?>:
											<span class="product-item-quantity" data-entity="quantity-limit-value">
												<?
												if ($arParams['SHOW_MAX_QUANTITY'] === 'M')
												{
													if ((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR'])
													{
														echo $arParams['MESS_RELATIVE_QUANTITY_MANY'];
													}
													else
													{
														echo $arParams['MESS_RELATIVE_QUANTITY_FEW'];
													}
												}
												else
												{
													echo $actualItem['CATALOG_QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE'];
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
						if (!$haveOffers)
						{
							if ($actualItem['CAN_BUY'] && $arParams['USE_PRODUCT_QUANTITY'])
							{
								?>
								<div class="product-item-info-container" data-entity="quantity-block">
									<div class="product-item-amount_1">
										<div class="product-item-amount-field-container_2">
											<span class="product-item-amount-field-btn-minus no-select" id="<?=$itemIds['QUANTITY_DOWN']?>"></span>
											<input class="product-item-amount-field" id="<?=$itemIds['QUANTITY']?>" type="number"
												name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>"
												value="<?=$measureRatio?>">
											<span class="product-item-amount-field-btn-plus no-select" id="<?=$itemIds['QUANTITY_UP']?>"></span>
											<div class="product-item-amount-description-container">
												<span id="<?=$itemIds['QUANTITY_MEASURE']?>">
													<?=$actualItem['ITEM_MEASURE']['TITLE']?>
												</span>
												<span id="<?=$itemIds['PRICE_TOTAL']?>"></span>
											</div>
										</div>
									</div>
								</div>
								<?
							}
						}
						elseif ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
						{
							if ($arParams['USE_PRODUCT_QUANTITY'])
							{
								?>
								<div class="product-item-info-container" data-entity="quantity-block">
									<div class="product-item-amount_1">
										<div class="product-item-amount-field-container_1">
											<span class="product-item-amount-field-btn-minus no-select" id="<?=$itemIds['QUANTITY_DOWN']?>"></span>
											<input class="product-item-amount-field" id="<?=$itemIds['QUANTITY']?>" type="number"
												name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>"
												value="<?=$measureRatio?>">
											<span class="product-item-amount-field-btn-plus no-select" id="<?=$itemIds['QUANTITY_UP']?>"></span>
											<div class="product-item-amount-description-container">
												<span id="<?=$itemIds['QUANTITY_MEASURE']?>"></span>
												<span id="<?=$itemIds['PRICE_TOTAL']?>"></span>
											</div>
										</div>
									</div>
								</div>
								<?
							}
						}

						break;

					case 'buttons':
						?>
						<div class="product-item-info-container" data-entity="buttons-block">
							<?
							if (!$haveOffers)
							{
								if ($actualItem['CAN_BUY'])
								{
									?>
									<div class="product-item-button-container" id="<?=$itemIds['BASKET_ACTIONS']?>">
										<a class="btn btn-default <?=$buttonSizeClass?>" id="<?=$itemIds['BUY_LINK']?>"
											href="javascript:void(0)" rel="nofollow">
											<?=($arParams['ADD_TO_BASKET_ACTION'] === 'BUY' ? $arParams['MESS_BTN_BUY'] : $arParams['MESS_BTN_ADD_TO_BASKET'])?>
										</a>
									</div>
									<?
								}
								else
								{
									?>
									<div class="product-item-button-container">
										<?
										if ($showSubscribe)
										{
											$APPLICATION->IncludeComponent(
												'bitrix:catalog.product.subscribe',
												'',
												array(
													'PRODUCT_ID' => $actualItem['ID'],
													'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
													'BUTTON_CLASS' => 'btn btn-default '.$buttonSizeClass,
													'DEFAULT_DISPLAY' => true,
													'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
												),
												$component,
												array('HIDE_ICONS' => 'Y')
											);
										}
										?>
										<a class="btn btn-link <?=$buttonSizeClass?>" id="<?=$itemIds['NOT_AVAILABLE_MESS']?>"
											href="javascript:void(0)" rel="nofollow">
											<?=$arParams['MESS_NOT_AVAILABLE']?>
										</a>
									</div>
									<?
								}
							}
							else
							{
								if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
								{
									?>
									<div class="product-item-button-container">
										<?
										if ($showSubscribe)
										{
											$APPLICATION->IncludeComponent(
												'bitrix:catalog.product.subscribe',
												'',
												array(
													'PRODUCT_ID' => $item['ID'],
													'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
													'BUTTON_CLASS' => 'btn btn-default '.$buttonSizeClass,
													'DEFAULT_DISPLAY' => !$actualItem['CAN_BUY'],
													'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
												),
												$component,
												array('HIDE_ICONS' => 'Y')
											);
										}
										?>
										<a class="btn btn-link <?=$buttonSizeClass?>"
											id="<?=$itemIds['NOT_AVAILABLE_MESS']?>" href="javascript:void(0)" rel="nofollow"
											<?=($actualItem['CAN_BUY'] ? 'style="display: none;"' : '')?>>
											<?=$arParams['MESS_NOT_AVAILABLE']?>
										</a>
										<div id="<?=$itemIds['BASKET_ACTIONS']?>" <?=($actualItem['CAN_BUY'] ? '' : 'style="display: none;"')?>>
											<a class="btn btn-default <?=$buttonSizeClass?>" id="<?=$itemIds['BUY_LINK']?>"
												href="javascript:void(0)" rel="nofollow">
												<?=($arParams['ADD_TO_BASKET_ACTION'] === 'BUY' ? $arParams['MESS_BTN_BUY'] : $arParams['MESS_BTN_ADD_TO_BASKET'])?>
											</a>
										</div>
									</div>
									<?
								}
								else
								{
									?>
									<div class="product-item-button-container">
										<a class="btn btn-default <?=$buttonSizeClass?>" href="<?=$item['DETAIL_PAGE_URL']?>">
											<?=$arParams['MESS_BTN_DETAIL']?>
										</a>
									</div>
									<?
								}
							}
							?>
						</div>
						<?
						break;

					case 'compare':
						if (
							$arParams['DISPLAY_COMPARE']
							&& (!$haveOffers || $arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
						)
						{
							?>
							<div class="product-item-compare-container">
								<div class="product-item-compare">
									<div class="checkbox">
										<label id="<?=$itemIds['COMPARE_LINK']?>">
											<input type="checkbox" data-entity="compare-checkbox">
											<span data-entity="compare-title"><?=$arParams['MESS_BTN_COMPARE']?></span>
										</label>
									</div>
								</div>
							</div>
							<?
						}

						break;
				}
			}
			?>
		</div>
	</div>
    <? if ($slidesToShow!=0):?>
    <script>
        var currentSlideNumber = 1;
        var slider_<?php echo $itemIds['ID'];?> = $("#slider_<?php echo $itemIds['ID'];?>").fotorama({
            allowfullscreen: true,
            nav: false,
            height: 200
        });
        var slider_<?php echo $itemIds['ID'];?>_data = slider_<?php echo $itemIds['ID'];?>.data('fotorama');

        $("#list_<?php echo $itemIds['ID'];?>").slick({
            infinite: true,
            slidesToShow: <?echo $slidesToShow;?>,
            slidesToScroll: 1,
        }).on('afterChange', function(event, slick, currentSlide){
            currentSlideNumber = currentSlide;
        });

        $("#list_<?php echo $itemIds['ID'];?> .bx-photo-list-img").click(function () {
            $('.bx-photo-slider-full').show();
            slider_<?php echo $itemIds['ID'];?>_data.show($(this).attr('data-key-id'));
            slider_<?php echo $itemIds['ID'];?>_data.requestFullScreen();
        });
    </script>
    <? endif;?>
</div>