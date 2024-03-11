
	// import { jsPDF } from "jspdf";
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
				'targets' : [0,1,2,3,4],
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
				url : "handler/datatable/rpt-top-offices.php",
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

    function stackedBarChart(f, t) {
        $.ajax({
            url:'handler/dbhandler.php',
            data:{
                action:'rpt_top_office_month_page',
                from:f,
                to:t
            },
            type:'post',
            dataType:'json',
            beforeSend: function(){
                $("canvas#stackedBarChart").remove();
				$("div.chartreport").append('<canvas id="stackedBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
            },
            success: function(data) {
                console.log(data);
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
    }stackedBarChart(from, to);
	

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
		stackedBarChart(formattedStartDate, formattedEndDate);
	});

});