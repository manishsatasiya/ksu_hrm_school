var asInitVals = new Array();
$(document).ready( function () {
	$('#grid_job_title').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable=	$('#grid_job_title').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: CI.base_url+"job_title/index_json",
		"sDom": "<'div class=col-md-12'fClT><'clear'>rtip",
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		"oTableTools": {
            "aButtons": [
            ]
        },
		aoColumns: [
		            	{"sName": "job_title_id"},
						{"sName": "job_title"},
						{"sName": "created_at"},
						{"sName": "updated_at"},
		            	{	"sName": "ID",
       						"bSearchable": false,
       						"bSortable": false,
       						"fnRender": function (oObj) {
       								var actionstr = "";
									actionstr += '<a href="'+CI.base_url+'job_title/add/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal" class="modal-link"><i class="fa fa-edit"></i></a>';
									actionstr += ' <a href="'+CI.base_url+'job_title/delete/'+oObj.aData[0]+'"><i class="fa fa-trash-o"></i></a>';
			       					return actionstr;
							}
						}
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_job_title_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_job_title_wrapper .dataTables_length select').addClass("select2-wrapper span12"); 
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
	var dTable = $('#grid_job_title').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}