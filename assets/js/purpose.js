$(function () {
    var dept = 0;

	function initializetable(){
		$("#purposeTable").DataTable({
			responsive: true,
			aaSorting: [],
			dom: 'lfrtip',
			columnDefs: [{
				'targets' : [0,1,2],
				'orderable' : false
			}],
			info: true,
		    processing: true,
        	serverSide: true,
        	paging: true,
        	fixedColumns:   {
		        "leftColumns": 0
		    },
			pageLength : 10,
			lengthMenu: [10,20,50, 100, 500, 1000],
		    ajax:{
				url : "handler/datatable/purposehandler.php",
	            type : 'POST',
                data: function(data) {
					data.dept = dept;
				},
		        error: function(e) {
		        	console.log(e.responseText);
		        }
	        },
	        stateSave: true,
    		bDestroy: true
		});
	}
    initializetable();

    $('#dept').on('change', function() {
        dept = $(this).val();
        initializetable();
    })

    //Not using yet
	$(document).on('click', '.btn-delete', function(e) {
		const id = $(this).data('id');

		Swal.fire({
			title: "Delete",
			text: "Are you sure to Delete this option?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes, Delete it!"
		}).then((result) => {
			if (result.isConfirmed) {

				$.ajax({
					url: "handler/dbhandler.php",
					type: "post",
					data: {
						id:id,
						action:'delete_purpose'
					},
					dataType: "json",
					success: function(data) {
						// console.log(data);
						if (data.success) {
							Swal.fire({
								title: "Delete",
								text: "Option successfully deleted!",
								icon: "success"
							});
							
							initializetable();
						}
						
					},
					error: function(err) {
						console.log(err.responseText);
					}
				});
				
			}
			
		});
	});

});

