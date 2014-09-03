var asInitVals = new Array();
$(document).ready( function () {
	$('#grid_profile_comment').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable=	$('#grid_profile_comment').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: CI.base_url+"list_user/get_profile_comment_json/",
		"sDom": "<'div class=col-md-12'fClT><'clear'>rtip",
		"oTableTools": {
            "aButtons": [
            ]
        },
		aoColumns: [
		            	{"sName": "id"},
						{"sName": "staff_name"},
						{"sName": "note_type"},
						{"sName": "department_name"},
						{"sName": "recommendation"},
						{"sName": "detail"},
						{"sName": "created_by"},
						{"sName": "created_at"}
		           ],
		sPaginationType: "bootstrap"});
	dTable.fnSort( [ [7,'desc'] ] );
	$('#grid_profile_comment_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_profile_comment_wrapper .dataTables_length select').addClass("select2-wrapper span12"); 
	dTable.columnFilter({
        aoColumns: [ 
				null,
				{ type: "text" },
				{ type: "text" },
				null,
				null,
				null,
				null,
				null
            ]
        });	
});
function comment_fnShowHide( iCol )
{
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_profile_comment').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}