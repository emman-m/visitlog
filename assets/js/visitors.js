$(function () {

    initializetable();
	function initializetable(){
		$("#visitorsTable").DataTable({
			aaSorting: [],
			dom: 'Blfrtip',
	        buttons: [
	     		{
		            extend: 'pdf',
		            text: '<span title="Export via PDF">PDF</span>',
		            attr: {class: 'btn btn-danger btn-xs' },
		            footer: true,
		            title:"",
		            exportOptions: {
		                columns: [0,1,2]
		            },
		            customize: function (doc) {
				    	var text = "Visitor List";
		            	doc.content.splice(0, 0, {
	                        text: [
	                            {text: text , italics: false, fontSize: 12}
	                        ],
	                        margin: [0, 0, 0,0],
	                        alignment: 'left'
	                    });
	                    doc.styles.tableHeader.alignment = 'left';
						// doc.content[1].table.widths = Array(doc.content[1].table.body[1].length + 1).join('*').split('');
						doc.content[1].table.widths = Array(80,120,150);
				  	}
		        },
		        {
	           		extend: 'csv',
		            text: '<span title="Export via CSV">CSV</span>',
	           		attr: {class: 'btn btn-success btn-xs' },
	           		footer: true,
		            exportOptions: {
		                columns: [0,1,2]
		            },
		            title: "Visitor List"
		          
		        }
	       	],
			columnDefs: [{
				'targets' : [3],
				'orderable' : false
			}],
		    processing: true,
        	serverSide: true,
        	paging: true,
        	fixedColumns:   {
		        "leftColumns": 0
		    },
			pageLength : 10,
			lengthMenu: [10, 20,50, 100, 500, 1000],
		    ajax:{
				url : "handler/datatable/visitorshandler.php",
	            type : 'POST',
		        error: function(e) {
		        	console.log(e);
		        }
	        },
	        stateSave: false,
    		bDestroy: true
		});
	}

	$(document).on('click', '.btn-delete', function() {
		const id = $(this).data('id');

		Swal.fire({
			title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes, delete it!"
		}).then((result) => {
			if (result.isConfirmed) {

				$.ajax({
					url: "handler/dbhandler.php",
					type: "post",
					data: {id:id, action:'delete_visitor'},
					dataType: "json",
					success: function(data) {
						if (data.success) {
							Swal.fire({
								title: "Deleted!",
								text: "Your file has been deleted.",
								icon: "success"
							});
							initializetable();
						} else {
							$.each(data.error, function (key, val) {
								$('.err_msg').html(`<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>${val}</div>`);
							
							});
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

