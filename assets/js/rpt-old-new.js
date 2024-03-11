
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
	        buttons: [
	     		{
		            extend: 'pdf',
		            text: '<span title="Export via PDF">PDF</span>',
		            attr: {class: 'btn btn-danger btn-xs' },
		            footer: true,
		            title:"",
		            exportOptions: {
		                columns: [0,1]
		            },
		            customize: function (doc) {
				    	var text = "Users List";
		            	doc.content.splice(0, 0, {
	                        text: [
	                            {text: text , italics: false, fontSize: 12}
	                        ],
	                        margin: [0, 0],
	                        alignment: 'left'
	                    });
	                    doc.styles.tableHeader.alignment = 'left';
						// doc.content[1].table.widths = Array(doc.content[1].table.body[1].length + 1).join('*').split('');
						doc.content[1].table.widths = Array(100,'auto');
				  	}
		        },
		        {
	           		extend: 'csv',
		            text: '<span title="Export via CSV">CSV</span>',
	           		attr: {class: 'btn btn-success btn-xs' },
	           		footer: true,
		            exportOptions: {
		                columns: [0,1]
		            },
		            title: "Users List"
		          
		        }
	       	],
			columnDefs: [{
				'targets' : [0,1,2],
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
				url : "handler/datatable/rpt-old-new.php",
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

    function pieChart(f, t, destroy) {
		$.ajax({
            url:'handler/dbhandler.php',
            data:{
				action:'rpt_old_vs_new_visitor_page',
				from:f,
				to:t
			},
            type:'post',
            dataType:'json',
			beforeSend: function() {
				$("canvas#pieChart").remove();
				$("div.chartreport").append('<canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
			},
            success: function(data) {
                // console.log(data);
				var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
				var pieData        = data.result;
				var pieOptions     = {
					maintainAspectRatio : false,
					responsive : true,
				}
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
    pieChart(from, to, false);

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
		pieChart(formattedStartDate, formattedEndDate, true);
	});

});