var asInitVals = new Array();
$(document).ready( function () {
	dTable=	$('#grid_contracted_summary').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: CI.base_url+"contracted_summary/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [
		            	{"sName": "id"},
						{"sName": "contractor_id"},
						{"sName": "campus_id"},
						{"sName": "user_roll_name"},
						{"sName": "contracted_numbers"},
						{"sName": "ID","bSearchable": false,"bSortable": false,},
						{"sName": "ID","bSearchable": false,"bSortable": false,},
						{"sName": "updated_at"},
		            	
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_contracted_summary_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_contracted_summary_wrapper .dataTables_length select').addClass("select2-wrapper span12"); 
	
});