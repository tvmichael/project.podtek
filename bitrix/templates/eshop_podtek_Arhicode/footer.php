<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
                </div><!--//row-->
            <? /* $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "sect",
                                "AREA_FILE_SUFFIX" => "bottom",
                                "AREA_FILE_RECURSIVE" => "N",
                                "EDIT_MODE" => "html",
                            ),
                            false,
                            Array('HIDE_ICONS' => 'Y')
                        ); */ ?>
            </div><!--//container bx-content-seection-->
        </div><!--//workarea-->
        <? $page = $APPLICATION->GetCurPage(false); ?>

            <div class="col-xs-12 hidden-lg hidden-md hidden-sm">
                <? $APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "", array(
                    "PATH_TO_BASKET" => SITE_DIR . "personal/cart/",
                    "PATH_TO_PERSONAL" => SITE_DIR . "personal/",
                    "SHOW_PERSONAL_LINK" => "N",
                    "SHOW_NUM_PRODUCTS" => "Y",
                    "SHOW_TOTAL_PRICE" => "Y",
                    "SHOW_PRODUCTS" => "N",
                    "POSITION_FIXED" => "Y",
                    "POSITION_HORIZONTAL" => "center",
                    "POSITION_VERTICAL" => "bottom",
                    "SHOW_AUTHOR" => "Y",
                    "PATH_TO_REGISTER" => SITE_DIR . "login/",
                    "PATH_TO_PROFILE" => SITE_DIR . "personal/"
                ),
                    false,
                    array()
                ); ?>
            </div>

        <footer class="bx-footer <? if ($page == '/personal/cart/' || $page == '/personal/order/make/') echo 'no_link_bascet'; ?>">
            <?/*
                        <!--div class="bx-footer-section container bx-center-section">
                            <div class="col-sm-5 col-md-3 col-md-push-6">

                                <!--?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_menu", array(
                                        "ROOT_MENU_TYPE" => "bottom",
                                        "MAX_LEVEL" => "1",
                                        "MENU_CACHE_TYPE" => "A",
                                        "CACHE_SELECTED_ITEMS" => "N",
                                        "MENU_CACHE_TIME" => "36000000",
                                        "MENU_CACHE_USE_GROUPS" => "Y",
                                        "MENU_CACHE_GET_VARS" => array(
                                        ),
                                    ),
                                    false
                                );?>
                            </div>
                            <div class="col-sm-5 col-md-3">

                                <!--?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_menu", array(
                                        "ROOT_MENU_TYPE" => "left",
                                        "MENU_CACHE_TYPE" => "A",
                                        "MENU_CACHE_TIME" => "36000000",
                                        "MENU_CACHE_USE_GROUPS" => "Y",
                                        "MENU_CACHE_GET_VARS" => array(
                                        ),
                                        "CACHE_SELECTED_ITEMS" => "N",
                                        "MAX_LEVEL" => "1",
                                        "USE_EXT" => "Y",
                                        "DELAY" => "N",
                                        "ALLOW_MULTI_SELECT" => "N"
                                    ),
                                    false
                                );?>
                            </div>
                            <div class="col-sm-5 col-md-3 col-md-push-3">
                                <div style="padding: 20px;background:#eaeaeb">
                                    <!--?$APPLICATION->IncludeComponent(
                                        "bitrix:main.include",
                                        "",
                                        Array(
                                            "AREA_FILE_SHOW" => "file",
                                            "PATH" => SITE_DIR."include/sender.php",
                                            "AREA_FILE_RECURSIVE" => "N",
                                            "EDIT_MODE" => "html",
                                        ),
                                        false,
                                        Array('HIDE_ICONS' => 'Y')
                                    );?>
                                </div>
                                <div id="bx-composite-banner" style="padding-top: 20px"></div>
                            </div>
                            <div class="col-sm-5 col-md-3 col-md-pull-9">
                                <div class="bx-inclogofooter">
                                    <div class="bx-inclogofooter-block">
                                        <a class="bx-inclogofooter-logo" href="<!--?=SITE_DIR?>">
                                            <!--?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/company_logo_mobile.php"), false);?>
                                        </a>
                                    </div>
                                    <div class="bx-inclogofooter-block">
                                        <div class="bx-inclogofooter-tel"><!--?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/telephone.php"), false);?></div>
                                        <div class="bx-inclogofooter-worktime"><!--?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/schedule.php"), false);?></div>
                                    </div>
                                    <div>
                                        <!--?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/personal.php"), false);?>
                                    </div>
                                </div>
                            </div>
                        </div-->
                        */?>
            <div class="bx-footer-bottomline">
                <div class="bx-footer-section container">
                    <div class="hidden-xs hidden-sm col-md-4  col-lg-3">
                        <div class="bx-inclogofooter-block col-xs-12">
                            <div class="bx-inclogofooter-tel"><? $APPLICATION->IncludeComponent("bitrix:main.include", "", array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_DIR . "include/telephone_footer.php"
                                ),
                                    false,
                                    array("ACTIVE_COMPONENT" => "Y")
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="hidden-xs hidden-sm col-md-4  col-lg-3">
                        <!--div class="col-xs-12 col-md-4  col-lg-3"-->
                        <? $APPLICATION->IncludeComponent("bitrix:menu", "bottom_menu", array(
                            "ROOT_MENU_TYPE" => "bottom",
                            "MENU_CACHE_TYPE" => "A",
                            "MENU_CACHE_TIME" => "36000000",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "MENU_CACHE_GET_VARS" => "",
                            "CACHE_SELECTED_ITEMS" => "N",
                            "MAX_LEVEL" => "1",
                            "USE_EXT" => "Y",
                            "DELAY" => "N",
                            "ALLOW_MULTI_SELECT" => "N",
                            "COMPONENT_TEMPLATE" => "bottom_menu",
                            "CHILD_MENU_TYPE" => "left",
                            "COMPOSITE_FRAME_MODE" => "A",
                            "COMPOSITE_FRAME_TYPE" => "AUTO"
                        ),
                            false,
                            array(
                                "ACTIVE_COMPONENT" => "Y"
                            )
                        ); ?>
                    </div>

                    <div class="col-sm-6 col-md-4  col-lg-6">
                        <div class="col-xs-12 col-sm-12 hidden-md  hidden-lg">
                            <ul class="bx-inclinksfooter-list">
                                <li class="bx-inclinksfooter-item"><a href="/about/delivery/">
                                        <p1>Доставка</p1>
                                    </a></li>
                                <li class="bx-inclinksfooter-item"><a href="/paying/">
                                        <p1>Оплата</p1>
                                    </a></li>
                                <li class="bx-inclinksfooter-item"><a href="/about/contacts/">
                                        <p1>Контакты</p1>
                                    </a></li>
                            </ul>
                        </div>
                        <div class="bx-inclogofooter-worktime">
                            <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/schedule.php"), false); ?>
                        </div>

                    </div>
                </div>
        </footer>

    </div> <!-- //bx-wrapper -->

        <script>
            BX.ready(function () {
                var upButton = document.querySelector('[data-role="eshopUpButton"]');
                BX.bind(upButton, "click", function () {
                    var windowScroll = BX.GetWindowScrollPos();
                    (new BX.easing({
                        duration: 500,
                        start: {scroll: windowScroll.scrollTop},
                        finish: {scroll: 0},
                        transition: BX.easing.makeEaseOut(BX.easing.transitions.quart),
                        step: function (state) {
                            window.scrollTo(0, state.scroll);
                        },
                        complete: function () {
                        }
                    })).animate();
                })
            });
        </script>
        <!-- Yandex.Metrika counter -->
        <script type="text/javascript">
            (function (m, e, t, r, i, k, a) {
                m[i] = m[i] || function () {
                    (m[i].a = m[i].a || []).push(arguments)
                };
                m[i].l = 1 * new Date();
                k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
            })
            (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

            ym(51713657, "init", {
                id: 51713657,
                clickmap: true,
                trackLinks: true,
                accurateTrackBounce: true,
                webvisor: true,
                ecommerce: "dataLayer"
            });
        </script>
        <noscript>
            <div><img src="https://mc.yandex.ru/watch/51713657" style="position:absolute; left:-9999px;" alt=""/></div>
        </noscript>
        <!-- /Yandex.Metrika counter -->
    </body>
</html>