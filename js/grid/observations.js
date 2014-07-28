$(document).ready( function () {
	$('#grid_observations').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable = $('#grid_observations').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: CI.base_url+"list_user/get_observations_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [
		            	null , 
		            	{"sName": "first_name"},
		            	//{"sName": "section_title"},
						{"sName": "campus"},
		            	{"sName": "username"},
		            	{"sName": "password"},	
		            	{"sName": "email"},
		            	{"sName": "ID",
       						"bSearchable": false,
       						"bSortable": false,
       						"fnRender": function (oObj) {
								return '<a href="'+CI.base_url+'list_user/add_obs_comment/'+oObj.aData[6]+'" data-target="#myModal" data-toggle="modal" class="modal-link" title="Add Comment"><i class="fa fa-comment"></i></a>   <a href="'+CI.base_url+'list_user/add_obs_score/'+oObj.aData[6]+'" data-target="#myModal" data-toggle="modal" class="modal-link" title="Add Score"><i class="fa fa-star"></i></a>';
							}
						}
		            	
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