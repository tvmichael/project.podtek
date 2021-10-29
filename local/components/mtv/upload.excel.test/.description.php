<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => 'Кнопка для загрузки Excel', // GetMessage("Название кнопки"),
    "DESCRIPTION" => GetMessage("Выводим название кнопки для скачивания"),
    "ICON" => "/images/icon.png",
    "PATH" => array(
        "ID" => "content",
        "CHILD" => array(
            "ID" => "excel_btn",
            "NAME" => "Загрузка файла Excel"
        )
    ),
);
?>
