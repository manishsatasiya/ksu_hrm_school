var asInitVals = new Array();
$(document).ready( function () {
	dTable=	$('#grid_workshops').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: CI.base_url+"workshops/workshop_inactive_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [
		            	{"sName": "workshop_id"},
						{"sName": "title"},
						{"sName": "start_date"},
						{"sName": "time"},
						{"sName": "presenter"},
						{"sName": "workshop_type"},
						{"sName": "venue"},
						{"sName": "attendee_limit","bSortable": false,},
						{"sName": "registered","bSortable": false,},
						{"sName": "spaces","bSortable": false,},
		            	{	"sName": "ID",
       						"bSearchable": false,
       						"bSortable": false,
       						"fnRender": function (oObj) {
       								var actionstr = "";
									actionstr += '<div class="btn-group">';
									actionstr += '<button class="btn-axn" data-toggle="dropdown">';
									actionstr += '<i class="fa fa-gear"></i>';
									actionstr += '</button>';
									actionstr += '<ul class="dropdown-menu">';
									actionstr += '<li><a href="'+CI.base_url+'workshops/add/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal" class="modal-link">Edit Workshop</a></li>';
									
									actionstr += '<li><a href="'+CI.base_url+'workshops/attended/'+oObj.aData[0]+'">View Attendee</a></li>';
									
									actionstr += '<li><a href="'+CI.base_url+'workshops/add_attendee/0/0/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal" class="modal-link">Add Attendee</a></li>';
									actionstr += '<li><a href="'+CI.base_url+'workshops/sign_up_sheet/'+oObj.aData[0]+'">Sign up sheet</a></li>';
									
									actionstr += '<li class="divider"></li>';
									actionstr += '<li><a href="'+CI.base_url+'workshops/delete/'+oObj.aData[0]+'" class="text-error bold">Delete Workshop <i class="fa fa-times-circle"></i></a></li>';
									actionstr += '</ul>';
									actionstr += '</div>';
			       					return actionstr;
							}
						}
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_workshops_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_workshops_wrapper .dataTables_length select').addClass("select2-wrapper span12");
	dTable.columnFilter({
        aoColumns: [ 
				{ type: "text" },
				{ type: "text" },
				{ type: "text" },
				{ type: "text" },
				{ type: "text" },
				{ type: "text" },
				{ type: "text" },
				{ type: "text" },
				null,
				null,
				null
            ]
        });	
});
function fnShowHide( iCol )
{
	if($("#showhide_"+iCol).is(':checked'))
		$("#showhide_"+iCol).attr("checked", "checked");
	else
		$("#showhide_"+iCol).removeAttr("checked");	
		
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_workshops').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}
