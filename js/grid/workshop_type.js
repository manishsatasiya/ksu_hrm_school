var asInitVals = new Array();
$(document).ready( function () {
	$('#grid_workshop_type').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable=	$('#grid_workshop_type').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: CI.base_url+"workshop_type/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [
		            	{"sName": "id"},
						{"sName": "type"},
						{"sName": "created_at"},
						{"sName": "updated_at"},
		            	{	"sName": "ID",
       						"bSearchable": false,
       						"bSortable": false,
       						"fnRender": function (oObj) {
       								var actionstr = "";
									actionstr += '<a href="'+CI.base_url+'workshop_type/add/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal" class="modal-link"><i class="fa fa-edit"></i></a>';
									actionstr += ' <a href="'+CI.base_url+'workshop_type/delete/'+oObj.aData[0]+'"><i class="fa fa-trash-o"></i></a>';
			       					return actionstr;
							}
						}
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_workshop_type_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_workshop_type_wrapper .dataTables_length select').addClass("select2-wrapper span12");			
	dTable.columnFilter({
        aoColumns: [ 
				null,
				{ type: "text" },
				null,
				null,
				null
            ]
        });	
});
