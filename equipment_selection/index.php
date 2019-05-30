<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
CJSCore::Init(array("jquery"));
$APPLICATION->SetAdditionalCss($APPLICATION->GetCurPage() . "style.css");
$APPLICATION->AddHeadScript($APPLICATION->GetCurPage() . 'script.js');
$APPLICATION->SetTitle("Подбор оборудования");
?>
    <br>
    <script>var acUrlAjax = '<?=$APPLICATION->GetCurPage() . 'volume_ajax.php';?>';</script>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="col-xs-12 col-md-9">Подбор оборудования для басейна</h3>
            <div class="ac-panel-default col-md-3 hidden-xs hidden-sm">
                <div class="ac-volume-poll-number">0</div>
                м<span>3</span>
            </div>
        </div>
        <div class="panel-body" id="select-equipment">
            <div class="row">

                <div class="col-xs-12 col-md-3 color-question title-question">
                    Форма бассейна:
                </div>
                <label class="col-xs-12 col-md-3 rb-q">
                    <input class="radio-dot" type="radio" selected="" checked="" name="form_radio_new_field_0006" data-value="ahc-priamokutna">
                    <span class="ans">Прямоугольная</span>
                </label>
                <label class="col-xs-12 col-md-3 rb-q">
                    <input class="radio-dot" type="radio" name="form_radio_new_field_0006" data-value="ahc-okrugla">
                    <span class="ans">Округлая</span>
                </label>

            </div>
            <div class="row">
                <div class="col-xs-12 razm-bas color-question title-question">
                    Размер бассейна:*
                </div>
                <div id="ahc-priamokutna">
                    <div class="col-xs-12 col-md-4">
                        <label class="form-group" for="select-length">
                            <input class="form-control inputtext" type="number" maxlength="20" min="0" id="select-length"
                                   placeholder="длинна" value="">
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label class="form-group" for="select-width">
                            <input class="form-control inputtext" type="number" maxlength="20" min="0" id="select-width"
                                   placeholder="ширина" value="">
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label class="form-group" for="select-depth">
                            <input class="form-control inputtext" type="number" maxlength="20" min="0" id="select-depth"
                                   placeholder="глубина" value="">
                        </label>
                    </div>
                </div>
                <div id="ahc-okrugla">
                    <div class="col-xs-12 col-md-4">
                        <label class="form-group" for="select-depth">
                            <input class="form-control inputtext" type="number" maxlength="20" min="0" id="select-diameter"
                                   placeholder="диаметр" value="">
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label class="form-group" for="select-depth">
                            <input class="form-control inputtext" type="number" maxlength="20" min="0" id="select-d-depth"
                                   placeholder="глубина" value="">
                        </label>
                    </div>
                </div>
                <div class="col-md-12"></div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-group" for="select-volume"></label>
                        <input class="form-control inputtext" type="number" maxlength="20" min="0" id="select-volume"
                               placeholder="Объем бассейна" value="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-9 opis">Введите длинну, ширину и глубину басейна в метрах
                </div>
                <div class="col-xs-12 hidden-md hidden-lg hidden-xl">

                    <div class="ac-panel-default">Объем бассейна:
                        <div class="ac-volume-poll-number">0</div>
                        м<span>3</span>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">

                    <!--div class="text-right"-->
                    <button class="btn sv-view-n" type="button"> Подобрать</button>
                    <!--/div-->
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12" id="select-result"></div>
    </div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>