$(function () {

    // onchange of role selection
    $('#role').on('change', function(){
        if ($(this).val() == 3) {
            $('#addUserForm .dept-group').show();
        } else {
            $('#addUserForm .dept-group').hide();
        }
    });

    $("#addUserForm").on("submit", function (e) {
        e.preventDefault();
        var form = $("#addUserForm").serialize();

        $.ajax({
            type: "POST",
            data: form + "&action=add_user",
            url: "handler/dbhandler.php",
            dataType: "json",
            beforeSend: function () {
                $("#submit").prop("disabled", true);
                $('#submit').html('<i class="fas fa-spinner fa-pulse"></i>');
                $("input, select").prop("disabled", true);
                $("input.is-invalid").removeClass("is-invalid");
                $("select.is-invalid").removeClass("is-invalid");
                $(".err").html("");
            },
            success: function (data) {
                if (data.success) {
                    $('.err_msg').html(`<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>${data.msg}</div>`);
                    setTimeout(function(){
                        location.href="users.php";
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
                    $('#submit').html('Save');
                    $("#submit").prop("disabled", false);
                    $("input, select").prop("disabled", false);
                }
            },
            error: function (err) {
                console.log(err.responseText);
                $('#submit').html('Log In');
                $("#submit").prop("disabled", false);
                $("input, select").prop("disabled", false);
            },
        });
    });

});