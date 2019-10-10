<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/templates/" . SITE_TEMPLATE_ID . "/header.php");
CJSCore::Init(array("fx"));
$curPage = $APPLICATION->GetCurPage(true);
$theme = COption::GetOptionString("main", "wizard_eshop_bootstrap_theme_id", "blue", SITE_ID);
?>
<!DOCTYPE html>
<html xml:lang="<?= LANGUAGE_ID ?>" lang="<?= LANGUAGE_ID ?>">
<head>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-28168734-9"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-28168734-9');
</script>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-W6TKM2Z');</script>
<!-- End Google Tag Manager -->

    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">
    <link rel="shortcut icon" type="image/x-icon" href="<?= SITE_DIR ?>favicon.ico"/>
    <? $APPLICATION->ShowHead(); ?>
    <?
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/colors.css", true);
    $APPLICATION->SetAdditionalCSS("/bitrix/css/main/bootstrap.css");
    $APPLICATION->SetAdditionalCSS("/bitrix/css/main/font-awesome.css");
    $APPLICATION->AddHeadScript('/bitrix/templates/eshop_podtek_Arhicode/js/jquery-1.9.1.min.js');
    $APPLICATION->AddHeadScript('/bitrix/templates/eshop_podtek_Arhicode/js/bootstrap-dropdown.js');
    ?>
    <title><? $APPLICATION->ShowTitle() ?></title>
</head>
<body class="bx-background-image bx-theme-<?= $theme ?>" <?= $APPLICATION->ShowProperty("backgroundImage") ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W6TKM2Z"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
<div class="bx-wrapper" id="bx_eshop_wrap">
    <header class="bx-header">
        <div class="row bx-header-TopRow">
            <div class="container bx-header-1st-row">
                <div class="col-lg-8 col-md-8 hidden-sm hidden-xs">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "horizontal_multilevel_top_menu",
                        array(
                            "ROOT_MENU_TYPE" => "top",
                            "MENU_CACHE_TYPE" => "A",
                            "MENU_CACHE_TIME" => "36000000",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "MENU_THEME" => "site",
                            "CACHE_SELECTED_ITEMS" => "N",
                            "MENU_CACHE_GET_VARS" => array(),
                            "MAX_LEVEL" => "3",
                            "CHILD_MENU_TYPE" => "left",
                            "USE_EXT" => "Y",
                            "DELAY" => "N",
                            "ALLOW_MULTI_SELECT" => "N",
                            "COMPONENT_TEMPLATE" => "horizontal_multilevel_top_menu"
                        ),
                        false
                    ); ?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="bx-inc-orginfo">
                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/telephone.php"), false); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="bx-header-section container">
		
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                    <div class="bx-logo">
                        <a class="bx-logo-block hidden-xs" href="<?= SITE_DIR ?>">
                            <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/company_logo.php"), false); ?>
                        </a>
                        <a class="bx-logo-block hidden-lg hidden-md hidden-sm text-center" href="<?= SITE_DIR ?>">
                            <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/company_logo_mobile.php"), false); ?>
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-4 col-xs-12">
                    <? $APPLICATION->IncludeComponent(
	"bitrix:search.title", 
	"visual_old", 
	array(
		"NUM_CATEGORIES" => "1",
		"TOP_COUNT" => "5",
		"CHECK_DATES" => "N",
		"SHOW_OTHERS" => "N",
		"PAGE" => SITE_DIR."search/",
		"CATEGORY_0_TITLE" => GetMessage("SEARCH_GOODS"),
		"CATEGORY_0" => array(
			0 => "iblock_1c_catalog",
		),
		"CATEGORY_0_iblock_catalog" => array(
			0 => "all",
		),
		"CATEGORY_OTHERS_TITLE" => GetMessage("SEARCH_OTHER"),
		"SHOW_INPUT" => "Y",
		"INPUT_ID" => "title-search-input",
		"CONTAINER_ID" => "search",
		"PRICE_CODE" => array(
			0 => "Интернет-магазин",
		),
		"SHOW_PREVIEW" => "Y",
		"PREVIEW_WIDTH" => "75",
		"PREVIEW_HEIGHT" => "75",
		"CONVERT_CURRENCY" => "Y",
		"COMPONENT_TEMPLATE" => "visual_old",
		"ORDER" => "date",
		"USE_LANGUAGE_GUESS" => "Y",
		"PRICE_VAT_INCLUDE" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"CURRENCY_ID" => "RUB",
		"CATEGORY_0_iblock_1c_catalog" => array(
			0 => "7",
		),
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
); ?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hidden-xs">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:sale.basket.basket.line",
                        "arhi.basket.line",
                        array(
                            "PATH_TO_BASKET" => SITE_DIR . "personal/cart/",
                            "PATH_TO_PERSONAL" => SITE_DIR . "personal/",
                            "SHOW_PERSONAL_LINK" => "Y",
                            "SHOW_NUM_PRODUCTS" => "Y",
                            "SHOW_TOTAL_PRICE" => "N",
                            "SHOW_PRODUCTS" => "N",
                            "POSITION_FIXED" => "N",
                            "SHOW_AUTHOR" => "Y",
                            "PATH_TO_REGISTER" => SITE_DIR . "login/",
                            "PATH_TO_PROFILE" => SITE_DIR . "personal/",
                            "COMPONENT_TEMPLATE" => "arhi.basket.line",
                            "PATH_TO_ORDER" => SITE_DIR . "personal/order/make/",
                            "SHOW_EMPTY_VALUES" => "N",
                            "PATH_TO_AUTHORIZE" => SITE_DIR . "personal/",
                            "SHOW_REGISTRATION" => "Y",
                            "SHOW_DELAY" => "Y",
                            "SHOW_NOTAVAIL" => "Y",
                            "SHOW_IMAGE" => "Y",
                            "SHOW_PRICE" => "Y",
                            "SHOW_SUMMARY" => "Y",
                            "HIDE_ON_BASKET_PAGES" => "N",
                            "SHOW_CALL" => "Y",
                            "TYPE_BASKET" => "top",
                            "IBLOCK_TYPE_COMPARE" => "",
                            "IBLOCK_ID_COMPARE" => "",
                            "COMPARE_NAME" => "CATALOG_COMPARE_LIST",
                            "COMPOSITE_FRAME_MODE" => "A",
                            "COMPOSITE_FRAME_TYPE" => "AUTO"
                        ),
                        false
                    ); ?>
                </div>
            </div>
            <div class="row bx-header-BottomRow">
                <div class="col-md-3 hidden-xs">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "gopro",
                        array(
                            "ROOT_MENU_TYPE" => "left",
                            "MENU_CACHE_TYPE" => "N",
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "MENU_THEME" => "site",
                            "MENU_CACHE_GET_VARS" => array(),
                            "MAX_LEVEL" => "3",
                            "CHILD_MENU_TYPE" => "left",
                            "USE_EXT" => "Y",
                            "DELAY" => "N",
                            "COMPONENT_TEMPLATE" => "gopro",
                            "ALLOW_MULTI_SELECT" => "N",
                        ),
                        false
                    ); ?>

                    <!-- MOBILE -->
                    <div style="display:none;">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "arhi_catalog_horizontal",
                            array(
                                "ROOT_MENU_TYPE" => "left",
                                "MENU_CACHE_TYPE" => "N",
                                "MENU_CACHE_TIME" => "3600",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "MENU_THEME" => "site",
                                "MENU_CACHE_GET_VARS" => array(),
                                "MAX_LEVEL" => "3",
                                "CHILD_MENU_TYPE" => "left",
                                "USE_EXT" => "Y",
                                "DELAY" => "N",
                                "COMPONENT_TEMPLATE" => "gopro",
                                "ALLOW_MULTI_SELECT" => "N",
                            ),
                            false
                        ); ?>
                    </div>
                </div>

                <div class="col-md-9 hidden-xs">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "arhi_catalog_horizontal",
                        array(
                            "ROOT_MENU_TYPE" => "top_site",
                            "MENU_CACHE_TYPE" => "A",
                            "MENU_CACHE_TIME" => "360000",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "MENU_THEME" => "site",
                            "MENU_CACHE_GET_VARS" => array(),
                            "MAX_LEVEL" => "1",
                            "CHILD_MENU_TYPE" => "left",
                            "USE_EXT" => "Y",
                            "DELAY" => "N",
                            "COMPONENT_TEMPLATE" => "arhi_catalog_horizontal",
                            "ALLOW_MULTI_SELECT" => "N",

                            "CACHE_SELECTED_ITEMS" => "N",
                            "COMPOSITE_FRAME_MODE" => "A",
                            "COMPOSITE_FRAME_TYPE" => "AUTO"
                        ),
                        false
                    );?>
                </div>
            </div>


            <? if ($curPage != SITE_DIR . "index.php"): ?>
                <div class="row">
                    <div class="col-lg-12" id="navigation">
                        <? $APPLICATION->IncludeComponent("bitrix:breadcrumb", "", array(
                            "START_FROM" => "0",
                            "PATH" => "",
                            "SITE_ID" => "-"
                        ),
                            false,
                            Array('HIDE_ICONS' => 'Y')
                        ); ?>
                    </div>
                </div>
            <? endif ?>
        </div>
    </header>

    <div class="workarea">
        <div class="container bx-content-seection">
            <div class="bx-content col-xs-12">