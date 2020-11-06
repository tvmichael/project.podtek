<?php
/**
 * User: Michael
 * Date: 24.09.2020
 */

use Bitrix\Main\Diag;
use Bitrix\Main\Loader;

define('STOP_STATISTICS', true);
define('NOT_CHECK_PERMISSIONS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

Loader::includeModule("catalog");

$request = $context->getRequest();
$valueAction = $request->getPost("action");

if($valueAction == 'Add2Basket')
{
    $PRODUCT_ID = intval($request->getPost("productId"));
    $QUANTITY = 1;
    $addProduct = false;

    if (CModule::IncludeModule("catalog")) {
        if($PRODUCT_ID) $addProduct = Add2BasketByProductID($PRODUCT_ID, $QUANTITY, array(), array());
    }

    echo json_encode(['action'=>'add2basket', 'result'=>$addProduct]);
}
?>