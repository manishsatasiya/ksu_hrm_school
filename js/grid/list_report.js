$(document).ready( function () {
	$('#grid_report').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	var oTable=	$('#grid_report').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "list_report/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		"aaSorting": [[ 6, "desc" ]],
		aoColumns: [
		            	{"bVisible": false}, 
		            	{"sName": "user_id"},
		            	{"sName": "parent_menu"},
		            	{"sName": "sub_menu"},
		            	{"sName": "action"},
		            	{"sName": "user_ip"},
		            	{"sName": "created_date"},
						{"sName": "ID",
		            		"bSearchable": false,
		            		"bSortable": false,
		            		"fnRender": function (oObj) {
		            			return '<a href="list_report/viewlog/'+oObj.aData[7]+'" data-target="#myModal" data-toggle="modal"><i class="fa fa-edit"></i></a>';
		            		}
						}
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_report_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_report_wrapper .dataTables_length select').addClass("select2-wrapper span12");		
});
