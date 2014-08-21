var asInitVals = new Array();
$(document).ready( function () {
	$('#grid_my_attendance').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable=	$('#grid_my_attendance').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: CI.base_url+"my_attendance/index_json",
		"sDom": "<'div class=col-md-12'fClT><'clear'>rtip",
		"oTableTools": {
            "aButtons": [
            ]
        },
		aoColumns: [
		            	{"sName": "id"},
						{"sName": "dwEnrollNumber"},
						{"sName": "Logdate"},
						{"sName": "InTime"},
						{"sName": "OutTime"},
						{"sName": "TotalHours"},
						{"sName": "Late"},
						{"sName": "Approved"},
						{"sName": "StartTime"},
						{"sName": "EndTime"}
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_my_attendance_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_my_attendance_wrapper .dataTables_length select').addClass("select2-wrapper span12"); 
	dTable.columnFilter({
        aoColumns: [ 
				null,
				{ type: "text" },
				null,
				null,
				null
            ]
        });	
});
function fnShowHide( iCol )
{
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_my_attendance').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}