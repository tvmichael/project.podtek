$(document).ready(function () {
    var currentPoolType = 'ahc-priamokutna';

    function acVolumePoll(){
        var length = parseFloat($("#select-length").val()),
            width = parseFloat($("#select-width").val()),
            depth = parseFloat($("#select-depth").val()),
            pVolume = parseFloat($("#select-volume").val()),
            diameter = parseFloat($("#select-diameter").val()),
            ddepth = parseFloat($("#select-d-depth").val()),
            volumeMultiple;

        if(currentPoolType == 'ahc-priamokutna' && length && width && depth)
        {
            volumeMultiple = (length * width * depth).toFixed(2);
            $('.ac-panel-default').show();
            $('.ac-volume-poll-number').html(volumeMultiple);
            $('#select-volume').val(volumeMultiple);
            return volumeMultiple;
        }
        else if (currentPoolType == 'ahc-okrugla' && diameter && ddepth)
        {
            volumeMultiple = (0.5 * diameter * 0.5 * diameter * Math.PI * ddepth).toFixed(2);
            $('.ac-panel-default').show();
            $('.ac-volume-poll-number').html(volumeMultiple);
            $('#select-volume').val(volumeMultiple);
            return volumeMultiple;
        }
        else if (pVolume){
            pVolume = pVolume.toFixed(2);
            $('.ac-panel-default').show();
            $('.ac-volume-poll-number').html(pVolume);
            return pVolume;
        }
        else {
            $('.ac-panel-default').hide();
            return false;
        }
    }
    $("#select-length").focusout(function () {
        var $t = $(this);
        if (parseFloat($t.val())) $t.css('border-color','#CCD5DB');
        else $t.css('border-color','red');
        acVolumePoll();
    });
    $("#select-width").focusout(function () {
        var $t = $(this);
        if (parseFloat($t.val())) $t.css('border-color','#CCD5DB');
        else $t.css('border-color','red');
        acVolumePoll();
    });
    $("#select-depth").focusout(function () {
        var $t = $(this);
        if (parseFloat($t.val())) $t.css('border-color','#CCD5DB');
        else $t.css('border-color','red');
        acVolumePoll();
    });

    $("#select-diameter").focusout(function () {
        var $t = $(this);
        if (parseFloat($t.val())) $t.css('border-color','#CCD5DB');
        else $t.css('border-color','red');
        acVolumePoll();
    });
    $("#select-d-depth").focusout(function () {
        var $t = $(this);
        if (parseFloat($t.val())) $t.css('border-color','#CCD5DB');
        else $t.css('border-color','red');
        acVolumePoll();
    });

    $("#select-volume").focusout(function(){
        console.log('#select-volume-volume');
        $("#select-length").val('');
        $("#select-width").val('');
        $("#select-depth").val('');
        $("#select-diameter").val('');
        $("#select-d-depth").val('');
        acVolumePoll();
    });

    $('#select-result').on('click', 'button[data-id]', function () {
        var id = $(this).attr('data-id');
        if(id && acUrlAjax)
            $.post( acUrlAjax, {productId:id, action:"Add2Basket"})
                .done(function( data ) {
                    var data = JSON.parse(data);
                    if(parseInt(data.result) > 0) BX.onCustomEvent('OnBasketChange');
                });
    });

    $("#select-equipment .btn").click(function () {
        var volume = acVolumePoll();
        if(volume && acUrlAjax)
            $.post( acUrlAjax, {volume:volume, action:"equipment"})
                .done(function( data ) { $('#select-result').html(data);});
    });

    $("#select-equipment input[type='radio']").click(function (e) {
        var block = e.target.getAttribute('data-value');

        $('.ac-volume-poll-number').html('0');

        if(block == 'ahc-priamokutna')
        {
            currentPoolType = 'ahc-priamokutna';
            $('#ahc-priamokutna').css('display', 'block');
            $('#ahc-okrugla').css('display', 'none');
        }
        else
        {
            currentPoolType = 'ahc-okrugla';
            $('#ahc-priamokutna').css('display', 'none');
            $('#ahc-okrugla').css('display', 'block');
        }
    });
});