var asInitVals = new Array();
$(document).ready( function () {
	dTable=	$('#grid_workshop_attended').dataTable({
				bJQueryUI:false,
				bProcessing:true,
				bServerSide: true,
				sAjaxSource: CI.base_url+"workshops/get_attended_json?workshop_id="+workshop_id,
				"sDom": 'fCl<"clear">rtip',
				"oColVis": {
					"aiExclude": [table_total_col-1]
		        },
				aoColumns: [
								{"sName": "attendance"},
								{"sName": "user_workshop_id"},
								{"sName": "first_name"},
								{"sName": "elsd_id"},
								{"sName": "email"},
								{	"sName": "ID",
									"bSearchable": false,
									"bSortable": false,
								}
						   ],
				sPaginationType: "bootstrap"});
	$('#grid_workshop_attended_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_workshop_attended_wrapper .dataTables_length select').addClass("select2-wrapper span12");				
});
function fnShowHide( iCol )
{
	if($("#showhide_"+iCol).is(':checked'))
		$("#showhide_"+iCol).attr("checked", "checked");
	else
		$("#showhide_"+iCol).removeAttr("checked");	
		
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_workshop_attendees').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}
