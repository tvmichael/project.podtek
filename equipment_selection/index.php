<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
CJSCore::Init(array("jquery"));
$APPLICATION->AddHeadScript('/equipment_selection/script.js');
$APPLICATION->SetTitle("Подбор оборудования");
?>
<style>
    .panel.panel-default .panel-heading{
        position: relative;
    }
    .panel .ac-panel-default{
        display: none;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        right: 20px;
        font-size: 18px;
    }
    .panel .ac-panel-default > div{
        display: inline-block;
        font-weight: 700;
    }
    .panel .ac-panel-default > span{
        position: relative;
        top: -5px;
        font-size: 14px;
    }
    .ac-table-poll{
        width: 100%;
        line-height: 2;
    }
    .ac-table-poll td[colspan]{
        font-weight: bold;
    }
</style>
<br>
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

<div class="panel panel-default">
    <div class="panel-body" id="select-result"></div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>