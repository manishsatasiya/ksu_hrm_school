$(document).ready( function () {
							 //alert(js_dt_columns);
	$('#grid_other_user').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable = $('#grid_other_user').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "user_qualification/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: js_dt_columns,
		sPaginationType: "bootstrap"});
		$('#grid_other_user_wrapper .dataTables_filter input').addClass("input-medium ");
		$('#grid_other_user_wrapper .dataTables_length select').addClass("select2-wrapper span12"); 
		dTable.columnFilter({
	        aoColumns: js_dt_columns_filter
        });
	
});
function fnShowHide( iCol )
{
	if($("#showhide_"+iCol).is(':checked'))
		$("#showhide_"+iCol).attr("checked", "checked");
	else
		$("#showhide_"+iCol).removeAttr("checked");	
		
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_other_user').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}