$(document).ready(function () {

    function acVolumePoll(){
        var length = parseFloat($("#select-length").val()),
            width = parseFloat($("#select-width").val()),
            depth = parseFloat($("#select-depth").val()),
            volume;

        if(length && width && depth){
            volume = (length * width * depth).toFixed(2);
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

    $("#select-equipment .btn").click(function () {
        var volume = acVolumePoll();
        if(volume)
            $.post( "/test/p/volume_ajax.php", { volume: volume, action: "equipment" })
                .done(function( data ) { $('#select-result').html(data);});
    });
});