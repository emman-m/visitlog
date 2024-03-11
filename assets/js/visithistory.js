$(function () {

    initializetable();
	function initializetable(){
		$("#visitHisTable").DataTable({
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
		                columns: [0,1,2,3,4]
		            },
		            customize: function (doc) {
				    	var text = `Visit History (${dept})`;
		            	doc.content.splice(0, 0, {
	                        text: [
	                            {text: text , italics: false, fontSize: 12}
	                        ],
	                        margin: [0, 0, 0, 0],
	                        alignment: 'left'
	                    });
	                    doc.styles.tableHeader.alignment = 'left';
						// doc.content[1].table.widths = Array(doc.content[1].table.body[1].length + 1).join('*').split('');
						doc.content[1].table.widths = Array(80,120,150,80,80);
				  	}
		        },
		        {
	           		extend: 'csv',
		            text: '<span title="Export via CSV">CSV</span>',
	           		attr: {class: 'btn btn-success btn-xs' },
	           		footer: true,
		            exportOptions: {
		                columns: [0,1,2,3,4]
		            },
		            title: `Visit History (${dept})`
		          
		        }
	       	],
			columnDefs: [{
				'targets' : [0,1,2,3,4],
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
				url : "handler/datatable/visitHishandler.php",
	            type : 'POST',
		        error: function(e) {
		        	console.log(e);
		        }
	        },
	        stateSave: false,
    		bDestroy: true
		});
	}

});

