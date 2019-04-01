$(document).ready(function () {
    function acVolumePoll(){
        var length = parseFloat($("#select-length").val()),
            width = parseFloat($("#select-width").val()),
            depth = parseFloat($("#select-depth").val()),
            volume = parseFloat($("#select-volume").val()),
            volumeMultiple;

        if(length && width && depth){
            volumeMultiple = (length * width * depth).toFixed(2);
            $('.ac-panel-default').show();
            $('.ac-volume-poll-number').html(volumeMultiple);
            $('#select-volume').val(volumeMultiple);
            return volumeMultiple;
        }
        else if (volume){
            volume = volume.toFixed(2);
            $('.ac-panel-default').show();
            $('.ac-volume-poll-number').html(volume);
            return volume;
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
    $("#select-volume").focusout(function(){acVolumePoll();});

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
});