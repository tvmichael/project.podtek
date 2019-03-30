<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
CJSCore::Init(array("jquery"));
$APPLICATION->SetAdditionalCss($APPLICATION->GetCurPage()."style.css");
$APPLICATION->AddHeadScript($APPLICATION->GetCurPage().'script.js');
$APPLICATION->SetTitle("Подбор оборудования");
?>
<br>
<script>var acUrlAjax = '<?=$APPLICATION->GetCurPage().'volume_ajax.php';?>';</script>
<div class="panel panel-default">
    <div class="panel-heading">
        <h4>Подбор оборудования для басейна</h4>
        <div class="ac-panel-default">
            <div class="ac-volume-poll-number">0</div>
            м<span>3</span>
        </div>
    </div>
    <div class="panel-body" id="select-equipment">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-group" for="select-length">Длинна:</label>
                    <input class="form-control" type="text" maxlength="20" id="select-length" placeholder="0" value="">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-group" for="select-width">Ширина:</label>
                    <input class="form-control" type="text" maxlength="20" id="select-width" placeholder="0" value="">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-group" for="select-depth">Глубина:</label>
                    <input class="form-control" type="text" maxlength="20" id="select-depth" placeholder="0" value="">
                </div>
            </div>

            <div class="col-sm-6">
                <small>Введите длинну, ширину и глубину басейна в метрах</small> <br />
            </div>
            <div class="col-sm-6">
                <div class="text-right">
                    <button class="btn sv-view-n" type="button"> Подобрать оборудование </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12" id="select-result"></div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>