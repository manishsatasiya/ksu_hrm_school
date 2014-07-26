var asInitVals = new Array();
$(document).ready( function () {
	dTable=	$('#grid_workshop_sign_up_sheet').dataTable({
				bJQueryUI:false,
				bProcessing:true,
				bServerSide: true,
				sAjaxSource: CI.base_url+"workshops/get_sign_up_sheet_json?workshop_id="+workshop_id,
				"sDom": 'fClT<"clear">rtip',
				//"sDom": 'lfrCT<"clear"><"table_content"t>,<"widget-bottom"p>',
				"oTableTools": {
		            "aButtons": [
		                "print"
		            ]
		        },
				"oColVis": {
					//"aiExclude": [table_total_col-1]
		        },
				aoColumns: [
								{"sName": "user_workshop_id"},
								{"sName": "first_name"},
								{"sName": "elsd_id"},
								{"sName": "signature"}
						   ],
				sPaginationType: "bootstrap"
			});
	$('#grid_workshop_sign_up_sheet_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_workshop_sign_up_sheet_wrapper .dataTables_length select').addClass("select2-wrapper span12"); 
});
function fnShowHide( iCol )
{
	if($("#showhide_"+iCol).is(':checked'))
		$("#showhide_"+iCol).attr("checked", "checked");
	else
		$("#showhide_"+iCol).removeAttr("checked");	
		
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_workshop_sign_up_sheet').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}
