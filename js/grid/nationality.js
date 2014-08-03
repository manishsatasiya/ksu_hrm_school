var asInitVals = new Array();
$(document).ready( function () {
	$('#grid_nationality').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable=	$('#grid_nationality').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: CI.base_url+"nationality/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [
		            	{"sName": "id"},
						{"sName": "nationality"},
						{"sName": "native"},
						{"sName": "accepted"},
						{	"sName": "ID",
       						"bSearchable": false,
       						"bSortable": false,
       						"fnRender": function (oObj) {
       								var actionstr = "";
									actionstr += '<a href="'+CI.base_url+'nationality/add/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal" class="modal-link"><i class="fa fa-edit"></i></a>';
									actionstr += ' <a href="'+CI.base_url+'nationality/delete/'+oObj.aData[0]+'"><i class="fa fa-trash-o"></i></a>';
			       					return actionstr;
							}
						}
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_nationality_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_nationality_wrapper .dataTables_length select').addClass("select2-wrapper span12"); 
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
function fnShowHide( iCol )
{
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_nationality').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}