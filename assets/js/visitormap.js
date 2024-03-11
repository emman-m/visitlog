$('#mapForm').on('submit', function(e) {
    e.preventDefault();

    $('#qrcode').html("");
    var visitor = $('#visitor').val();
    var dept = $('#department').val();
    var ip = $('#address').val() == '::1' ? '192.168.1.4' : $('#address').val();
    var err = 0;

    if (isNull(visitor)) {
        $('#visitor').removeClass('is-invalid').addClass('is-invalid');
        $('.err_visitor').html("This field is required");
        err++;
    } else {
        $('#visitor').removeClass('is-invalid');
        $('.err_visitor').html("");
    }

    if (isNull(dept)) {
        $('#department').removeClass('is-invalid').addClass('is-invalid');
        $('.err_department').html("This field is required");
        err++;
    } else {
        $('#department').removeClass('is-invalid');
        $('.err_department').html("");
    }

    if (err == 0) {
        generateQr(`http://${ip}/visitlog/map/mp.php?id=${visitor}&destination=${dept}`);
    }
    

});

function generateQr(text) {
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: text,
        width: 400,
        height: 400,
        colorDark : "#343A40",
        colorLight : "#F4F6F9",
        correctLevel : QRCode.CorrectLevel.H
    });
    
    $("#qrcodeVal").html(text);
}

function isNull($val) {
    if ($val == null) {
        return true;
    }

    return false;
}