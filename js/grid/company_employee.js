$(document).ready( function () {
	$('#grid_company_employee').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable = $('#grid_company_employee').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "company_employee/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [
						null ,
						{"sName": "elsd_id"},
		            	{"sName": "first_name"},
		            	{"sName": "scanner_id"},
		            	//{"sName": "gender"},
		            	{"sName": "email"},
		            	//{"sName": "cell_phone"},
						//{"sName": "user_roll_id"},
						//{"sName": "co_ordinator"},
						//{"sName": "campus"},
						//{"sName": "contractor"},
						//{"sName": "returning"},
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
								actionstr += '<li><a href="add_employee/index/'+oObj.aData[parseInt(table_total_col-1)]+'">Edit</a></li>';
								actionstr += '<li><a href="list_user/edit_profile/'+oObj.aData[parseInt(table_total_col-1)]+'">View Profile</a></li>';
								/*actionstr += '<li class="divider"></li>';
								actionstr += '<li><a href="#" onclick=dt_delete("users","user_id",'+oObj.aData[parseInt(table_total_col-1)]+'); class="text-error bold">Delete Profile <i class="fa fa-times-circle"></i></a></li>';*/
								actionstr += '</ul>';
								actionstr += '</div>';
								
								return actionstr;
							}
						}
		            	
		           ],
		sPaginationType: "bootstrap"});
		$('#grid_company_employee_wrapper .dataTables_filter input').addClass("input-medium ");
		$('#grid_company_employee_wrapper .dataTables_length select').addClass("select2-wrapper span12"); 
		dTable.columnFilter({
	        aoColumns: [    
	                 null,
					 { type: "text" },
					 { type: "text" },
	        		 { type: "text" },
	                 //{ type: "text" },
	                 { type: "text" },
	                 //{ type: "text" },
	                 //{ type: "text" },
	                // { type: "text" },
					// { type: "text" },
	                // { type: "text" },
	                // { type: "text" },
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
	var dTable = $('#grid_company_employee').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}