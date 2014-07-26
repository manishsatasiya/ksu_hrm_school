$(document).ready( function () {
	dTable = $('#grid_observations').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: CI.base_url+"observation_scores/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [
		            	{"sName": "first_name"},
						{"sName": "elsd_id"},
		            	{"sName": "date"},
		            	{"sName": "user_id"},	
		            	{"sName": "score"},
		            	{"sName": "comment"}
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_observations_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_observations_wrapper .dataTables_length select').addClass("select2-wrapper span12");		
});
function fnShowHide( iCol )
{
	if($("#showhide_"+iCol).is(':checked'))
		$("#showhide_"+iCol).attr("checked", "checked");
	else
		$("#showhide_"+iCol).removeAttr("checked");	
		
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_observations').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}