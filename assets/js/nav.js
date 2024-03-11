$(document).ready(function(){
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    var query = false;
    
    setInterval(() => {
        if (!query) {
            $.ajax({
                url:'handler/dbhandler.php',
                data:{
                    action:'get_notif',
                    notif:notif
                },
                type:'post',
                dataType:'json',
                beforeSend: function() {
                    query = true;
                },
                success: function(data) {
                    if (notif < data.count) {
                        console.log(data);
                        $("#notif").html(data.notif);
                        $(".notif-count-badge").html(data.count);
                        toastr.info(`${data.count} New Notification`);
                        notif = data.count;
                        
                        // Audio
                        var audio = new Audio('assets/sound/notif.wav');
                        audio.play();
                        
                        // Notification.requestPermission().then(perm => {
                        //     if(perm === 'granted') {
                        //         new Notification(`${data.count} New Notification`)
                        //     }
                        // });
                    } else if (notif >= data.count && $("#notif").is(':empty')){
                        // console.log(data);
                        $("#notif").html(data.notif);
                        $(".notif-count-badge").html(data.count);
                    }
                    query = false;
                },
                error: function(err) {
                    console.log(err.responseText);
                }
            })
        }
        updateClock();
    }, 1000);


    function updateClock() {
        const now = new Date();

        const formattedTime = now.toLocaleString('en-US', {
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
            hour12: true
        }).replace(/,/g, '');

        
        const realTimeClock = document.getElementById('cur_time');
        realTimeClock.textContent = formattedTime;
    }
    
});