// var DataTable = require( 'datatables.net' );
$(function() {
var table;
    function initializetable(){
		table = $("#visitorTable").DataTable({
			responsive: true,
            pagingType: 'simple_numbers',
			aaSorting: [],
			dom: 'tp',
			columnDefs: [{
				'targets' : [0, 1],
				'orderable' : false
			}],
			info: false,
		    processing: true,
        	serverSide: true,
        	paging: true,
        	fixedColumns:   {
		        "leftColumns": 0
		    },
			pageLength : 10,
		    ajax:{
				url : "handler/datatable/appointmenthandler.php",
	            type : 'POST',
		        error: function(e) {
		        	$('.err_msg').html(e.responseText);
		        }
	        },
	        stateSave: true,
    		bDestroy: true,
			initComplete: function () {
				if (table.rows().data().length == 0) {
					// $('.visitor-list').html("No Appointment Today");
				}
			},
			createdRow: function (row, data, dataIndex) {
				$(row).css("cursor", "pointer");
				$(row).attr("onClick",`location.href='details.php?uid=${data[2]}'`);
				// console.log(data[2]);
			}
		});
	}
    initializetable();
});