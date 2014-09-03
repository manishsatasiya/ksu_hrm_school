var asInitVals = new Array();
$(document).ready( function () {
	$('#grid_profile_comment').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable=	$('#grid_profile_comment').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: CI.base_url+"profile_comment/index_json/",
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
						{"sName": "created_at"},
		            	{"sName": "ID",
       						"bSearchable": false,
       						"bSortable": false,
       						"fnRender": function (oObj) {
								var actionstr = "";
								actionstr += '<div class="btn-group">';
								//actionstr += '<button class="btn btn-mini btn-primary btn-demo-space">OPTIONS</button>';
								actionstr += '<button class="btn btn-mini dropdown-toggle btn-demo-space" data-toggle="dropdown">';
								//actionstr += '<span class="caret"></span>';
								actionstr += '<i class="fa fa-gear"></i>';
								actionstr += '</button>';
								actionstr += '<ul class="dropdown-menu">';
								//if(edit_flag == 1)
								actionstr += '<li><a href="profile_comment/add/'+oObj.aData[parseInt(table_total_col-1)]+'" data-target="#myModal" data-toggle="modal">Edit</a></li>';
								//if(edit_profile_flag == 1)
								actionstr += '<li><a href="profile_comment/view_detail/'+oObj.aData[parseInt(table_total_col-1)]+'" data-target="#myModal" data-toggle="modal">View Detail</a></li>';
								actionstr += '<li class="divider"></li>';
								actionstr += '<li><a href="#" onclick=dt_delete("profile_notes","id",'+oObj.aData[parseInt(table_total_col-1)]+'); class="text-error bold">Delete Profile <i class="fa fa-times-circle"></i></a></li>';
								actionstr += '</ul>';
								actionstr += '</div>';
								
								return actionstr;
							}
						}
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