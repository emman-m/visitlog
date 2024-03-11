// Function to update the real-time clock
function updateClock() {
    const now = new Date();

    const formattedTime = now.toLocaleString('en-US', {
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
        hour12: true
    }).replace(/,/g, '');

    
    const realTimeClock = document.getElementById('time');
    realTimeClock.textContent = formattedTime;
}

setInterval(updateClock, 1000);
updateClock();

$(function(){
    var scanStatus = false;
    var secAction = 0;
    var timer2Val = 8;

    var timer = setInterval(() => {
        secAction++;

        if (secAction == 1 && scanStatus) {
            scanStatus = false;
            const rfid = $('#idField').val();
            console.log("Send Request");

            $.ajax({
                url: 'handler/dbhandler.php',
                data:{rfid:rfid, action:'tap_rfid'},
                type:'post',
                dataType: 'json',
                beforeSend: function() {
                    $('.scan-panel').hide();
                    $('.loading-panel').show();
                },
                success: function(data) {
                    setTimeout(() => {
                        if (data.success) {
                            $('#imageData').attr('src', data.image);
                            $('#nameData').html(data.name);
                            $('#timeInData').html(data.timeIn);
                            $('#timeOutData').html(data.timeOut);
                            $('#purposeData').html(data.purpose);
                            
                            $('.loading-panel').hide();
                            $('.result-panel').show();

                            var timer2 = setInterval(() => {
                                $('.closing-timer').html(timer2Val);
            
                                if (timer2Val == 0) {
                                    timer2Val = 8;
                                    $('#idField').val("");
                                    $('.result-panel').hide();
                                    $('.scan-panel').show();
                                    document.getElementById("idField").focus();
                                    $('.closing-timer').html("8");
                                    clearInterval(timer2);
                                }
                                timer2Val--;
                            }, 1000);
                        } else {
                            $('.loading-panel').hide();
                            $('.nodata-panel').show();

                            var timer2 = setInterval(() => {
                                $('.closing-timer').html(timer2Val);
                                
                                if (timer2Val == 5) {
                                    timer2Val = 8;
                                    $('#idField').val("");
                                    $('.nodata-panel').hide();
                                    $('.scan-panel').show();
                                    
                                    document.getElementById("idField").focus();
                                    $('.closing-timer').html("8");
                                    clearInterval(timer2);
                                }
                                timer2Val--;
                            }, 1000);
                        }
                        
                    }, 2000);
                    
                },
                error : function (err) {
                    console.log(err.responseText);
                }
            });

        }
    }, 1000);

// backup
    // $('#idField').on('input', function() {
    //     const value = $(this).val();
    //     secAction = 0;
    //     scanStatus = true;
        
    //     // console.log(value);
    // });

    $('#RFID').on('submit', function(e) {
        e.preventDefault();
        $('#idField').blur();
        const value = $(this).val();
        secAction = 0;
        scanStatus = true;
        
        // console.log(value);
    });
});