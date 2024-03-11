$(function(){
	$('#rptDate').daterangepicker({
		locale: {
		format: 'MMMM D, YYYY'
		}
	});

	function initializetable(from, to){
		$("#visitorTable").DataTable({
			aaSorting: [],
			dom: 'lrtip',
			columnDefs: [{
				'targets' : [0,1],
				'orderable' : false
			}],
			info: true,
		    processing: true,
        	serverSide: true,
        	paging: false,
        	fixedColumns:   {
		        "leftColumns": 0
		    },
			pageLength : 10,
			lengthMenu: [10,20,50, 100, 500, 1000],
		    ajax:{
				url : "handler/datatable/rpt-busy-days.php",
	            type : 'POST',
				data: function(data) {
					// console.log(from, to);
					data.from = from;
					data.to = to;
					
				},
		        error: function(e) {
		        	console.log(e.responseText);
		        }
	        },
	        stateSave: true,
    		bDestroy: true
		});
	}
    initializetable(from, to);

    function areaChart(f, t) {
        $.ajax({
            url:'handler/dbhandler.php',
            data:{
                action:'rpt_top_day_month_page',
                from:f,
                to:t
            },
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
                console.log(err.responseText);
                $('#result').html(err.responseText);
            }
        });
    } areaChart(from, to);
	

	$('.f-report').on('click', function() {
		let date = $('#rptDate').val().split(' - ');
		$('.header-title').html($('#rptDate').val());

		let startDateString = date[0];
		let endDateString = date[1];

		// Convert start date to yyyy-mm-dd format
		let startDate = new Date(startDateString);
		let formattedStartDate = startDate.toISOString().slice(0,10);

		// Convert end date to yyyy-mm-dd format
		let endDate = new Date(endDateString);
		let formattedEndDate = endDate.toISOString().slice(0,10);

		initializetable(formattedStartDate, formattedEndDate);
		areaChart(formattedStartDate, formattedEndDate);
	});

});