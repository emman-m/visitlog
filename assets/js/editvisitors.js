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
    // Get the video element
    const videoElement = document.getElementById('cameraFeed');

    // Create a canvas to draw the current frame from the video
    const canvas = document.createElement('canvas');
    canvas.width = videoElement.videoWidth;
    canvas.height = videoElement.videoHeight;

    // Draw the current frame onto the canvas
    const context = canvas.getContext('2d');
    context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);

    // Convert the canvas content to a data URL (base64-encoded image)
    const capturedPhoto = canvas.toDataURL('image/png');

    // Set the captured photo data URL as the value of the input field
    const capturedPhotoInput = document.getElementById('img');
    capturedPhotoInput.value = capturedPhoto;

    // Optionally, display the captured photo (for demonstration purposes)
    const capturedPhotoDisplay = document.getElementById('preview');
    capturedPhotoDisplay.src = capturedPhoto;
    stopCamera();
}

$(function () {

    function shoHideOption(ele, val, clss, radio) {
        if (ele.val() == val && ele.is(':checked')) {
            $(clss).show();
        } else if (ele.val() == val && !ele.is(':checked')) {
            $(clss).hide();
            $(`input[name=${radio}]`).prop('checked', false);
            $(`textarea[name=${radio}-other]`).val("");
        }
    }

    $('textarea').on('input', function() {
        const radio = $(this).attr('name').split('-other')[0];
        $(`input[type=radio][name=${radio}]`).prop('checked', false);
        $(this).siblings('input').prop('checked', true);
    })

    $('input[type=radio]').on('click', function() {
        if  ($(this).val() !== 'Other') {
            const textarea = $(this).attr('name')+'-other';
            $(`textarea[name=${textarea}]`).val("");
        }
        
    })

    $('input[type=checkbox]').on('click', function() {
        shoHideOption($(this), '1', '.cashier-option', 'p-cash');
        shoHideOption($(this), '2', '.reg-option', 'p-reg');
        shoHideOption($(this), '3', '.clinic-option', 'p-clinic');
        shoHideOption($(this), '4', '.discipline-option', 'p-discipline');
    })

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