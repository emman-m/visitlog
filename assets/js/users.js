$(function () {

	function initializetable(){
		$("#usersTable").DataTable({
			responsive: true,
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
		                columns: [1,2,3,4,5]
		            },
		            customize: function (doc) {
				    	var text = "Users List";
		            	doc.content.splice(0, 0, {
	                        text: [
	                            {text: text , italics: false, fontSize: 12}
	                        ],
	                        margin: [0, 0, 0, 12, 8],
	                        alignment: 'left'
	                    });
	                    doc.styles.tableHeader.alignment = 'left';
						// doc.content[1].table.widths = Array(doc.content[1].table.body[1].length + 1).join('*').split('');
						doc.content[1].table.widths = Array(100,120,80,70,'auto');
				  	}
		        },
		        {
	           		extend: 'csv',
		            text: '<span title="Export via CSV">CSV</span>',
	           		attr: {class: 'btn btn-success btn-xs' },
	           		footer: true,
		            exportOptions: {
		                columns: [1,2,3,4,5]
		            },
		            title: "Users List"
		          
		        }
	       	],
			columnDefs: [{
				'targets' : [0, 6],
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
				url : "handler/datatable/usershandler.php",
	            type : 'POST',
		        error: function(e) {
		        	console.log(e.responseText);
		        }
	        },
	        stateSave: true,
    		bDestroy: true
		});
	}
    initializetable();

	
	function initializetableInactive(){
		$("#usersTableInactive").DataTable({
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
		                columns: [1,2,3,4,5]
		            },
		            customize: function (doc) {
				    	var text = "Users List";
		            	doc.content.splice(0, 0, {
	                        text: [
	                            {text: text , italics: false, fontSize: 12}
	                        ],
	                        margin: [0, 0, 0, 12, 8],
	                        alignment: 'left'
	                    });
	                    doc.styles.tableHeader.alignment = 'left';
						// doc.content[1].table.widths = Array(doc.content[1].table.body[1].length + 1).join('*').split('');
						doc.content[1].table.widths = Array(100,120,80,70,'auto');
				  	}
		        },
		        {
	           		extend: 'csv',
		            text: '<span title="Export via CSV">CSV</span>',
	           		attr: {class: 'btn btn-success btn-xs' },
	           		footer: true,
		            exportOptions: {
		                columns: [1,2,3,4,5]
		            },
		            title: "Users List"
		          
		        }
	       	],
			columnDefs: [{
				'targets' : [0, 6],
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
				url : "handler/datatable/usershandlerInactive.php",
	            type : 'POST',
		        error: function(e) {
		        	console.log(e.responseText);
		        }
	        },
	        stateSave: true,
    		bDestroy: true
		});
	}
    initializetableInactive();

	$(document).on('change', '.active-inactive', function(e) {
		const checkbox = $(this);
		const id = checkbox.data('id');
		const checkState = checkbox.is(':checked');
		var state = '"Enable"';
		if (!checkState) {
			state = '"Disable"';
		}

		Swal.fire({
			title: "Enable/Disable",
			text: "Are you sure to "+state+" this user?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes, "+state+" it!"
		}).then((result) => {
			if (result.isConfirmed) {

				$.ajax({
					url: "handler/dbhandler.php",
					type: "post",
					data: {
						id:id,
						action:'enable_disable',
						state: checkState
					},
					dataType: "json",
					success: function(data) {
						// console.log(data);
						if (data.success) {
							Swal.fire({
								title: "Enable/Disable",
								text: "Successfully "+state+"d user",
								icon: "success"
							});
							
							initializetable();
							initializetableInactive();
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
				
			} else {
				if (checkState) {
					checkbox.prop('checked', false);
				} else {
					checkbox.prop('checked', true);
				}
			}
			
		});
	});

});

