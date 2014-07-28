var asInitVals = new Array();
$(document).ready( function () {
	$('#grid_contracted_numbers').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable=	$('#grid_contracted_numbers').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: CI.base_url+"contracted_numbers/index_json",
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
						{"sName": "created_at"},
						{"sName": "updated_at"},
		            	{	"sName": "ID",
       						"bSearchable": false,
       						"bSortable": false,
       						"fnRender": function (oObj) {
       								var actionstr = "";
									if(edit_flag){
										actionstr += '<a href="'+CI.base_url+'contracted_numbers/add/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal" class="modal-link"><i class="fa fa-edit"></i></a>';
									}
									if(delete_flag){
										actionstr += ' <a href="#" onclick=dt_delete("contracted_numbers","id",'+oObj.aData[0]+');><i class="fa fa-trash-o"></i></a>';
									}
			       					return actionstr;
							}
						}
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_contracted_numbers_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_contracted_numbers_wrapper .dataTables_length select').addClass("select2-wrapper span12"); 
	
});
function fnShowHide( iCol )
{
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_contracted_numbers').dataTable();
	
	var bVis = dTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}