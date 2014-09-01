$(document).ready( function () {
	$('#grid_line_managers_list').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable = $('#grid_line_managers_list').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "line_managers_list/index_json",
		/*"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },*/
		"bLengthChange": false,
		"iDisplayLength": 100,
		aoColumns: [
						null ,
						null ,
						{"sName": "staff_name"},
						{"sName": "elsd_id"},
		            	{"sName": "job_title"},
		            	{"sName": "personal_email"},
						{"sName": "email"},
		            	{"sName": "cell_phone"},
		            	{"sName": "contractor"}
		           ],
		sPaginationType: "bootstrap"
	});
		$('#grid_line_managers_list_wrapper .dataTables_filter input').addClass("input-medium ");
		$('#grid_line_managers_list_wrapper .dataTables_length select').addClass("select2-wrapper span12"); 
		dTable.columnFilter({
	        aoColumns: [    
	                 null,
					 null ,
					 { type: "text" },
	        		 null,
	                 { type: "text" },
	                 { type: "text" },
	                 null,
	                 null
	            ]
        });	
	
});
function fnShowHide( iCol )
{
	if($("#Col-"+iCol).is(':checked'))
		$("#Col-"+iCol).attr("checked", "checked");
	else
		$("#Col-"+iCol).removeAttr("checked");	
		
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_line_managers_list').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}