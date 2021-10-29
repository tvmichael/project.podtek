<?php
define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC','Y');
define('DisableEventsCheck', true);
define('BX_SECURITY_SHOW_MESSAGE', true);
define('NOT_CHECK_PERMISSIONS', true);

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

global $APPLICATION;

use Bitrix\Main\Context;

$BX_COND_MAIN_USER_GROUP_ID = 'BX:CondMainUserGroupId';
$exel_request = [
    'status' => false
];

$request = Context::getCurrent()->getRequest();

$iblock_id = $request->get("IBLOCK_ID");
$arrDiscountIds = $request->get("DISCOUNT_LIST"); // список скидок по яким визначаємо товар
$userGroupArray = $request->get("USER_GROUP_LIST"); // список груп для яких знаходимо товари
$user_id = $request->get("USER_ID");
$priceTypeId = $request->get("PRICE_TYPE_ID");
$propertyList = $request->get("PROPERTY_LIST");
$imageList = $request->get("IMAGE_LIST");

if(empty($userGroupArray) || empty($iblock_id))
{
    echo json_encode($exel_request);
    die();
}

if(!CModule::IncludeModule('iblock'))
{
    $exel_request['error'] = "IncludeModule('iblock')";
    echo json_encode($exel_request);
    die();
}

if(empty($priceTypeId)) $priceTypeId = 2;

// --- перевіряємо масив, чи додавати посилання на фото товару ---
if(empty($imageList) || !is_array($imageList)) $imageList = [];
else foreach ($imageList as $k => $item)
    if(!in_array($item, ["PREVIEW_PICTURE", 'DETAIL_PICTURE'])) unset($imageList[$k]);

$imageList = array_values($imageList);

// --- перевіряємо чи є масив з властивостями ---
if(isset($propertyList[0]) && $propertyList[0] == '0') unset($propertyList[0]);
elseif (!empty($propertyList) && !is_array($propertyList)) $propertyList = [];

$arrPropFieldsNames = [];
$resProperties = CIBlockProperty::GetList(
    Array("NAME" => "ASC"),
    Array("ACTIVE" => "Y", "IBLOCK_ID" => $iblock_id)
);

$k = count($imageList);
while ($propFields = $resProperties->GetNext())
    if(in_array($propFields['ID'], $propertyList)) {
        $arrPropFieldsNames[$k] = $propFields['NAME'];
        $k++;
    }
//Bitrix\Main\Diag\Debug::writeToFile(array($imageList, count($imageList), $propertyList, $arrPropFieldsNames), "", "/test-1234/l.txt");
unset($resProperties, $propFields);

$propsArticleId = 75; // Артикул;
if(!in_array($propsArticleId, $propertyList)) $propertyList[] = $propsArticleId;

// --- перевіряємо чи є масив зі знижками ---
if(isset($arrDiscountIds[0]) && $arrDiscountIds[0] == '0') unset($arrDiscountIds[0]);
elseif (!empty($arrDiscountIds) && !is_array($arrDiscountIds)) $arrDiscountIds = [];

if(empty($arrDiscountIds))
{
    $arrDiscountIds = [];
    \Bitrix\Main\Loader::includeModule('sale');

    $discountIterator = \Bitrix\Sale\Internals\DiscountTable::getList(array(
        'select' => array('ID', 'CONDITIONS_LIST'),
        'filter' => array('ACTIVE' => 'Y'),
        'order' => array('ID' => 'ASC')
    ));

    while ($item = $discountIterator->Fetch()){
        if(isset($item['CONDITIONS_LIST']['CHILDREN']) && is_array($item['CONDITIONS_LIST']['CHILDREN']))
            foreach ($item['CONDITIONS_LIST']['CHILDREN'] as $child)
                if(isset($child['CLASS_ID']) && $child['CLASS_ID'] == $BX_COND_MAIN_USER_GROUP_ID)
                    if(!empty(array_intersect($userGroupArray, ($child['DATA']['value'] ?? []))))
                        $arrDiscountIds[] = $item['ID'];
    }
}
unset($discountIterator, $item);

// PHP_SPREADSHEET -----------------------------------------------------------------------------------------------------

$PHP_EXCEL_PATH = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/PhpSpreadsheet';
require $PHP_EXCEL_PATH . '/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

$spreadsheet = new Spreadsheet();

$spreadsheet->getProperties()
    ->setCreator("https://podtek.ru")
    ->setTitle("Прайс-лист");

// Формуємо заголовок таблиці
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getActiveSheet()->setShowSummaryBelow(false);

$sheet = $spreadsheet->getActiveSheet();

$lastRightColumn = 7;
//$extremeRightCell = $sheet->getCellByColumnAndRow($lastRightColumn /*+ count($arrPropFieldsNames)*/, 1)->getColumn();
//$extremeRightCell = $cell->getColumn();

$styleArray = [
    'font' => [
        'size' => 18,
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'outline' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
];


$sheet->getStyle('A1:F1')->applyFromArray($styleArray);
$sheet->setCellValue('A1', 'Прайс-лист')->mergeCells('A1:F1');

$styleArray['font']['size'] = 11;
$styleArray['font']['bold'] = false;
$sheet->getStyle('A2:F2')->applyFromArray($styleArray);
$sheet->setCellValue('A2', date('d.m.Y').'г.')->mergeCells("A2:F2");

$sheet->getColumnDimension('A')->setWidth(17);
$sheet->getColumnDimension('B')->setWidth(71);
$sheet->getColumnDimension('C')->setWidth(11);
$sheet->getColumnDimension('D')->setWidth(11);
$sheet->getColumnDimension('E')->setWidth(11);
$sheet->getColumnDimension('F')->setWidth(8);

$styleArray['borders'] = [
    'allBorders' => [
        'borderStyle' => Border::BORDER_THIN,
    ]
];
$styleArray['alignment']['wrapText'] = true;
$styleArray['fill'] = [
    'fillType' => Fill::FILL_SOLID,
    'startColor' => ['rgb' => 'c0dcf7']
];
$styleArray['font']['bold'] = true;

$sheet->getStyle('A3:F3')->applyFromArray($styleArray);
$sheet->setCellValue('A3', 'Артикул');
$sheet->setCellValue('B3', 'Номенклатура');
$sheet->setCellValue('C3', 'Цена');
$sheet->setCellValue('D3', 'Процент скидки');
$sheet->setCellValue('E3', 'Цена со скидкой');
$sheet->setCellValue('F3', 'Валюта');

// посилання на фото товару
$itemName = ['DETAIL_PICTURE' => 'Детальная картинка', 'PREVIEW_PICTURE' => 'Картинка для анонса'];
foreach ($imageList as $k => $item)
    if(isset($itemName[$item]))
    {
        $sheet->setCellValueByColumnAndRow($lastRightColumn + $k, 3, $itemName[$item]);
        $cell = $sheet->getCellByColumnAndRow($lastRightColumn + $k, 3);
        $sheet->getColumnDimension($cell->getColumn())->setWidth(20);
    }

// Додаємо заголовки для властивостей товару
foreach ($arrPropFieldsNames as $k => $itemName)
{
    $sheet->setCellValueByColumnAndRow($lastRightColumn + $k, 3, $itemName);
    $cell = $sheet->getCellByColumnAndRow($lastRightColumn + $k, 3);
    //$sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    $sheet->getColumnDimension($cell->getColumn())->setWidth(15);
}

$sheet->freezePane('A4');

// початкові значення
$line = 4;
$groupLevel = [];
$category = [];
$depth_level = 1;

// отримати список для дерева категорій
$tree = CIBlockSection::GetTreeList(
    Array('IBLOCK_ID' => $iblock_id, 'ACTIVE' => 'Y'),
    Array('ID', 'NAME', 'IBLOCK_SECTION_ID', 'DEPTH_LEVEL')
);

// будуємо дерево категорій і заносимо дані по категоріях в `excel`
while($section = $tree->GetNext())
{
    if($section['DEPTH_LEVEL'] == 1)
    {
        $depth_level = $section['DEPTH_LEVEL'];
        $category[$depth_level] = $section['NAME'];
    }
    elseif($section['DEPTH_LEVEL'] > $depth_level)
    {
        $depth_level = $section['DEPTH_LEVEL'];
        $category[$depth_level] = $section['NAME'];
    }
    elseif($section['DEPTH_LEVEL'] <= $depth_level)
    {
        //array_pop($category);
        $depth_level = $section['DEPTH_LEVEL'];

        array_slice($category, $depth_level);
        array_slice($groupLevel, $depth_level);

        $category[$depth_level] = $section['NAME'];
    }

    $arrProductPrint = [];
    // для кожної внутрішньої категорії беремо список товарів (що їй належить)
    if($section['DEPTH_LEVEL'] > 1)
    {
        $arSelect = Array("ID", "NAME", "PREVIEW_PICTURE", 'DETAIL_PICTURE');
        $arFilter = Array("ACTIVE" => "Y", "IBLOCK_ID" => $iblock_id, "SECTION_ID" => $section['ID']);
        $resElement = CIBlockElement::GetList(array("ID" => "asc"), $arFilter, false, false, $arSelect);

        // перебираємо всі товари для поточної підкатегорії
        while($elementItem = $resElement->GetNextElement())
        {
            $arElement = $elementItem->GetFields();

            // берем активні скидки для даного товару
            $arDiscounts = CCatalogDiscount::GetDiscountByProduct($arElement['ID'], $userGroupArray, "N", 2, SITE_ID);

            if(!empty($arDiscounts) )
            {
                // перевіряємо чи дані скидки є серед вибраних
                $result = array_intersect($arrDiscountIds, array_keys($arDiscounts));

                if(!empty($result))
                {
                    //$arPrice = CCatalogProduct::GetOptimalPrice($arElement["ID"], 1, $userGroupArray, 'N');
                    $dbPrice = CPrice::GetList(
                        array(),
                        array("PRODUCT_ID" => $arElement["ID"], "CATALOG_GROUP_ID" => $priceTypeId)
                    );
                    $arPrice = $dbPrice->Fetch();

                    //$dbProps = CIBlockElement::GetProperty($iblock_id, $arElement['ID'], array(), Array("ID" => $propertyList));
                    //$arProps = $dbProps->Fetch();

                    $arProps = ['ARTICLE' => '', 'ALL' => []];
                    $dbProps = CIBlockElement::GetProperty($iblock_id, $arElement['ID'], array(), Array("ID" => $propertyList));
                    while ($obProps = $dbProps->GetNext())
                        if($obProps['ID'] == $propsArticleId) $arProps['ARTICLE'] = $obProps['VALUE'];
                        else $arProps['ALL'][$obProps['NAME']] = $obProps['VALUE_ENUM'];

                    $arImageList = [];
                    foreach ($imageList as $img)
                    {
                        $k = CFile::GetPath($arElement[$img] ?? '');
                        if(empty($k)) $arImageList[] = '';
                        else $arImageList[] = 'https://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($arElement[$img]);
                    }

                    $arDiscounts = array_shift($arDiscounts);
                    $arrProductPrint[] = [
                        'ELEMENT' => $arElement,
                        'PRICE' => $arPrice,
                        'PROPS' => $arProps,
                        'DISCOUNTS' => $arDiscounts['VALUE'],
                        'IMG' => $arImageList,
                    ];
                }
            }
        }
    }

    if(!empty($arrProductPrint))
    {
        foreach ($category as $i => $c)
        {
            if(!in_array($c, $groupLevel))
            {
                $styleArray = [
                    'font' => [
                        'color' => array('rgb' => ($i > 1 ? '3281FC': '000000')),
                        'bold'  => ($i > 1 ? false : true),
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ];

                $sheet->setCellValue('A' . $line, $c)->mergeCells("A$line:F$line");
                $sheet->getStyle("A$line:F$line")->applyFromArray($styleArray);

                $sheet->getRowDimension($line)->setOutlineLevel($i-1);
                //$sheet->getStyle('A' . $line)->getAlignment()->setIndent(($i-1) * 2);

                $groupLevel[$i] = $c;
                $line++;
            }
        }

        foreach ($arrProductPrint as $pItem)
        {
            $sheet->setCellValue('A' . $line,  $pItem['PROPS']['ARTICLE']);
            $sheet->getStyle('A' . $line)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('A' . $line)->getFont()->getColor()->setRGB('676767');

            $sheet->setCellValue('B' . $line,  $pItem['ELEMENT']['NAME']);
            $sheet->getStyle('B' . $line)->getAlignment()->setWrapText(true);

            // PRICE
            $price = floatval($pItem['PRICE']['PRICE'] ?? '');
            $sheet->setCellValue('C' . $line,  $price);
            $sheet->getStyle('C' . $line)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            // DISCOUNTS
            $v = floatval($pItem['DISCOUNTS']);
            $percent = ($v > 0 ? $v / 100 : 0);
            $sheet->setCellValue('D' . $line,  $percent);
            $sheet->getStyle('D' . $line)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

            // DISCOUNTS PRICE
            $sheet->setCellValue('E' . $line,  $percent > 0 ? $price * (1-$percent) : $price);
            $sheet->getStyle('E' . $line)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);

            $sheet->setCellValue('F' . $line,  $pItem['PRICE']['CURRENCY']);
            $sheet->getStyle('F' . $line)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            //$sheet->getStyle('E' . $line)->getAlignment()->setIndent((($i-1) * 2) + 4);

            foreach ($pItem['IMG'] as $k => $itemName)
            {
                $sheet->setCellValueByColumnAndRow($lastRightColumn + $k, $line, $itemName);
                if(!empty($itemName))
                {
                    $cell = $sheet->getCellByColumnAndRow($lastRightColumn + $k, $line);
                    $cell->getHyperlink()->setUrl($itemName);
                }
            }

            if(!empty($pItem['PROPS']['ALL']))
                foreach ($arrPropFieldsNames as $k => $itemName)
                    if(isset($pItem['PROPS']['ALL'][$itemName]))
                        $sheet->setCellValueByColumnAndRow($lastRightColumn + $k, $line, $pItem['PROPS']['ALL'][$itemName]);

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ];
            $sheet->getStyle("A$line:F$line")->applyFromArray($styleArray);

            $sheet->getRowDimension($line)->setOutlineLevel($i);
            $line++;
        }
    }
}

$writer = new Xlsx($spreadsheet);
ob_start();
$writer->save('php://output');
$exel_request['xlsData'] = base64_encode(ob_get_contents());
ob_end_clean();
$exel_request['linkSource'] = 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,';

$exel_request['status'] = true;

echo json_encode($exel_request);