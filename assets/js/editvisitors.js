$(document).on('change','#img',function(){
    if(this.value.length == 0){
        $('.attachment').attr('src', '/assets/img/empty.jpg');
    }else{
        var file = this.files[0];
        var fileType = file["type"];

        if (file['size'] > 5240000) {
            console.log('exceed');
        } else {
            console.log('valid');
        }
        var validImageTypes = ["image/jpeg", "image/png"];
        if ($.inArray(fileType, validImageTypes) < 0) {
            $('.attachment').attr('src', 'assets/img/empty-pic.jpg');
            
        } else {
            readURL(this,'attachment');
        }
    }
})

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(input).parent('.image-upload').find('.attachment').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
// Camera Script
var video = document.getElementById('cameraFeed')
// Access the user's camera
function openCamera() {
    $('#cameraFeed').show();
    $('.open-camera').hide();
    $('.capture-camera').show();
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(function (stream) {
            // Assign the camera stream to the video element
            const videoElement = document.getElementById('cameraFeed');
            videoElement.srcObject = stream;
        })
        .catch(function (error) {
            console.error('Error accessing the camera:', error);
        });
}
function stopCamera() {
    var stream = video.srcObject;
    var tracks = stream.getTracks();

    for (var i = 0; i < tracks.length; i++) {
        var track = tracks[i];
        track.stop();
    }

    video.srcObject = null;
    $('#cameraFeed').hide();
    $('.open-camera').show();
    $('.capture-camera').hide();
}

// Function to capture a photo from the camera feed
function capturePhoto() {
    const videoElement = document.getElementById('cameraFeed');
    const canvas = document.createElement('canvas');

    canvas.width = videoElement.videoWidth;
    canvas.height = videoElement.videoHeight;
    const context = canvas.getContext('2d');
    context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);

    const capturedPhoto = canvas.toDataURL('image/png');

    const capturedPhotoInput = document.getElementById('img');
    capturedPhotoInput.value = capturedPhoto;

    const capturedPhotoDisplay = document.getElementById('preview');
    capturedPhotoDisplay.src = capturedPhoto;
    stopCamera();
}

$(function () {

    // purpose of visit
    function shoHideOption(ele, clss, option) {
        $(`.${option}`).prop('checked', false);
        $(`.${option}-other`).val("");
        if (ele.is(':checked')) {
            $(clss).slideDown();
        } else {
            $(clss).slideUp();
        }
    }

    var purposeCount = 1;
    function addPurpose(inputVal, inputName, div, addInput) {
        var html = `<div class="input-group mb-3" id="pcount${purposeCount}">
                        <input type="text" name="${inputName}" class="form-control" value="${inputVal}" readonly />
                        <div class="input-group-append">
                            <button type="button" class="btn input-group-text remove-input" data-input="pcount${purposeCount}"><i class="fas fa-times" style="color: #ff0000;"></i></button>
                        </div>
                    </div>`;
        $(div).append(html);
        $(addInput).val("");

        const btn = '.'+$(addInput).data('btn');
        $(btn).prop('disabled', true);
        purposeCount ++;
    }
    
    $(document).on('input', '.other-input', function(e) {
        const ele = $(this).data('btn');
        if ($(this).val() == "") {
            $(`.${ele}`).prop('disabled', true);
        } else {
            $(`.${ele}`).prop('disabled', false);
        }
    })

    $(document).on('click', '.remove-input', function() {
        const input = $(this).data('input');
        $(`#${input}`).remove();
        purposeCount--;
    })

    $('.p-cash-add').on('click', function() {
        var inputVal = $('input[name=p-cash-other]').val();
        addPurpose(inputVal, 'p-cash-input[]', '.cashier-option-div', 'input[name=p-cash-other]');
    })

    $('.p-reg-add').on('click', function() {
        var inputVal = $('input[name=p-reg-other]').val();
        addPurpose(inputVal, 'p-reg-input[]', '.reg-option-div', 'input[name=p-reg-other]');
    })

    $('.p-clinic-add').on('click', function() {
        var inputVal = $('input[name=p-clinic-other]').val();
        addPurpose(inputVal, 'p-clinic-input[]', '.clinic-option-div', 'input[name=p-clinic-other]');
    })

    $('.p-discipline-add').on('click', function() {
        var inputVal = $('input[name=p-discipline-other]').val();
        addPurpose(inputVal, 'p-discipline-input[]', '.discipline-option-div', 'input[name=p-discipline-other]');
    })

    $('input[type=checkbox]').on('click', function() {
        shoHideOption($(this), `.${$(this).data('target')}`, `${$(this).data('options')}`);
    })

    $.ajax({
        url:        'handler/dbhandler.php',
        type:       'post',
        data:       {action: 'get_purpose'},
        dataType:   'json',
        success:    function(data) {
            console.log(data);
            $('.cashier-option-div').prepend(data.cashier);
            $('.reg-option-div').prepend(data.registrar);
            $('.clinic-option-div').prepend(data.clinic);
            $('.discipline-option-div').prepend(data.discipline);
        },
        error:      function(err) {
            console.log(err.responseText);
        }
    });

    $('#birthday').on('change', function() {
        const birthdate = new Date($(this).val());
        const currentDate = new Date();
        const differenceInMilliseconds = currentDate - birthdate;
        const ageInYears = Math.floor(differenceInMilliseconds / (365.25 * 24 * 60 * 60 * 1000));

        $('#age').val(ageInYears);
    });

    $("#addVisitorForm").on("submit", function (e) {
        e.preventDefault();
    });

    $('#submit').on('click', function() {
        var form = $("#addVisitorForm").serialize();

        $.ajax({
            type: "POST",
            data: form + "&action=edit_visitor",
            url: "handler/dbhandler.php",
            dataType: "json",
            beforeSend: function () {
                $("#submit").prop("disabled", true);
                $('#submit').html('Saving <i class="fas fa-spinner fa-pulse"></i>');
                $("input, textarea, select").prop("disabled", true);
                $("input.is-invalid").removeClass("is-invalid");
                $("textarea.is-invalid").removeClass("is-invalid");
                $("select.is-invalid").removeClass("is-invalid");
                $(".err").html("");
            },
            success: function (data) {
                if (data.success) {
                    $('.err_msg').html(`<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>${data.msg}</div>`);
                    setTimeout(function(){
                        location.href="visitors.php";
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
                    $("input, textarea, select").prop("disabled", false);
                }
            },
            error: function (err) {
                console.log(err.responseText);
                $('#submit').html('Save');
                $("#submit").prop("disabled", false);
                $("input, textarea, select").prop("disabled", false);
            },
        });
    })
})