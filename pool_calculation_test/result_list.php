<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
echo \Bitrix\Main\Web\Json::encode($_REQUEST);
die();
