var asInitVals = new Array();
$(document).ready( function () {
	$('#grid_workshop_attendees').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable=	$('#grid_workshop_attendees').dataTable({
				bJQueryUI:false,
				bProcessing:true,
				bServerSide: true,
				sAjaxSource: CI.base_url+"workshops/get_attendees_json?workshop_id="+workshop_id,
				"sDom": 'fCl<"clear">rtip',
				"oColVis": {
					"aiExclude": [table_total_col-1]
		        },
				aoColumns: [
								{"sName": "user_workshop_id"},
								{"sName": "first_name"},
								{"sName": "line_manager"},
								{"sName": "elsd_id"},
								{"sName": "email"},
								{"sName": "created_at"},
								{	"sName": "ID",
									"bSearchable": false,
									"bSortable": false,
									"fnRender": function (oObj) {
											var actionstr = "";
											actionstr += '<a href="'+CI.base_url+'workshops/add_attendee/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal" class="modal-link"><i class="fa fa-edit"></i></a>';
											actionstr += ' <a href="'+CI.base_url+'workshops/delete_attendee/'+oObj.aData[0]+'"><i class="fa fa-trash-o"></a>';
											return actionstr;
									}
								}
						   ],
				sPaginationType: "bootstrap"});
	dTable.columnFilter({
	        aoColumns: [ 
	                 null,   
	        		 { type: "text" },
	                 null,
					 { type: "text" },
					 { type: "text" },
					 null,
					 null
	            ]
        });	
	$('#grid_workshop_attendees_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_workshop_attendees_wrapper .dataTables_length select').addClass("select2-wrapper span12");			
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
