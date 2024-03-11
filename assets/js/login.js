$(function () {
    // login
    $("#loginForm").on("submit", function (e) {
        e.preventDefault();
        var form = $("#loginForm").serialize();

        $.ajax({
            type: "POST",
            data: form + "&action=login",
            url: "handler/dbhandler.php",
            dataType: "json",
            beforeSend: function () {
                $(".btn-primary").prop("disabled", true);
                $("input").prop("disabled", true);
                $('#submit').html('Sign In <i class="fas fa-spinner fa-pulse"></i>');
                $("input.is-invalid").removeClass("is-invalid");
                $(".err").html("");
            },
            success: function (data) {
                console.log(data);
                if (data.success) {
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                } else {
                    $.each(data.error, function (key, val) {
                        console.log(key + "=" + val);
                        if (key == "err_msg") {
                            $('.'+key).html(`<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>${val}</div>`);
                        } else {
                            $("." + key).html(val);

                            $("#" + key.split("_")[1])
                                .removeClass("is-invalid")
                                .addClass("is-invalid");
                        }
                    });
                    $('.btn-primary').html('Sign In');
                    $(".btn-primary").prop("disabled", false);
                    $("input").prop("disabled", false);
                }
            },
            error: function (err) {
                console.log(err.responseText);
                $('.btn-primary').html('Sign In');
                $(".btn-primary").prop("disabled", false);
                $("input, textarea").prop("disabled", false);
            },
        });
    });
});
