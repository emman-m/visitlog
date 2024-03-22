$(function () {


    function pieChart() {
        $.ajax({
            url:'handler/dbhandler.php',
            data:{action:'rpt_old_vs_new_visitor'},
            type:'post',
            dataType:'json',
            success: function(data) {
                // console.log(data);
    
                var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
                var pieData        = data.result;
                var pieOptions     = {
                    maintainAspectRatio : false,
                    responsive : true,
                }
                //Create pie or douhnut chart
                // You can switch between pie and douhnut using the method below.
                new Chart(pieChartCanvas, {
                    type: 'pie',
                    data: pieData,
                    options: pieOptions
                })
            },
            error: function(err) {
                console.log(err.responseText);
            }
        });
    }

    function stackedBarChart() {
        $.ajax({
            url:'handler/dbhandler.php',
            data:{action:'rpt_top_office_month'},
            type:'post',
            dataType:'json',
            success: function(data) {
                // console.log(data);
                $('#result').html(data);
                var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d');
                var stackedBarChartData = $.extend(true, {}, data.result);
    
                var stackedBarChartOptions = {
                    responsive              : true,
                    maintainAspectRatio     : false,
                    scales: {
                        xAxes: [{
                        stacked: false,
                        }],
                        yAxes: [{
                        stacked: false
                        }]
                    }
                }
    
                new Chart(stackedBarChartCanvas, {
                    type: 'bar',
                    data: stackedBarChartData,
                    options: stackedBarChartOptions
                });
            },
            error: function(err) {
                console.log(err.responseText);
                $('#result').html(err.responseText);
            }
        });
    }

    function areaChart() {
        $.ajax({
            url:'handler/dbhandler.php',
            data:{action:'rpt_top_day_month'},
            type:'post',
            dataType:'json',
            success: function(data) {
                // console.log(data);
                var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

                var areaChartOptions = {
                    maintainAspectRatio : false,
                    responsive : true,
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            gridLines : {
                                display : false,
                            }
                        }],
                        yAxes: [{
                            gridLines : {
                                display : false,
                            }
                        }]
                    }
                }

                // This will get the first returned node in the jQuery collection.
                new Chart(areaChartCanvas, {
                type: 'line',
                data: data.result,
                options: areaChartOptions
                })
            },
            error: function(err) {
                // console.log(err.responseText);
                // $('#result').html(err.responseText);
            }
        });
    }
    
    pieChart();
    stackedBarChart();
    areaChart();
  })