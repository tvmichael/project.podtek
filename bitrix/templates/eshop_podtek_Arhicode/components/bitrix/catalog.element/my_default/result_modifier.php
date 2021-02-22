<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

$arResult["COUNT_PRODUCT"] = 0;

$db_sales = CSaleOrder::GetList(array(), false, false, false, ['ID']);
while ($ar_sales = $db_sales->Fetch())
{
    $dbBasketItems = CSaleBasket::GetList(array(), array("ORDER_ID" => $ar_sales['ID']), false, false, ['PRODUCT_ID']);
    while ($arItems = $dbBasketItems->Fetch())
    {
        if($arItems['PRODUCT_ID'] == $arResult['ID'])
        {
            $arResult["COUNT_PRODUCT"]++;
        }
    }
}

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();