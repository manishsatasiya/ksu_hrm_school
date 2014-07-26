$(document).ready( function () {
	dTable =	$('#grid_school_campus').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "list_school_campus/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [null , 
		            {"sName": "campus_name"},
		            {"sName": "campus_location"},
		            {"sName": "ID",
		            		"bSearchable": false,
		            		"bSortable": false,
		            		"fnRender": function (oObj) {
		            			return '<a href="list_school_campus/add/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal" class="modal-link"><i class="fa fa-edit"></i></a>';
		            		}
		            }
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_school_campus_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_school_campus_wrapper .dataTables_length select').addClass("select2-wrapper span12");	
	dTable.columnFilter({
		aoColumns: [ 
				 null,   
				 { type: "text" },
				 { type: "text" },
				 null
			]
	});	
	$('#grid_school_campus_wrapper .dataTables_filter input').addClass("input-medium ");
    $('#grid_school_campus_wrapper .dataTables_length select').addClass("select2-wrapper span12"); 
	if(edit_flag == 1)
	{
		dTable.makeEditable({
				sUpdateURL: "list_school_campus/update_school_campus",
				sAddURL: 	"list_school_campus/add_school_campus",
							"aoColumns": [null,
							{
								indicator: 'Saving ...',
								tooltip: 'Click to edit campus name',
								type: 'text',
								submit:'Save changes'
							},
							{
								indicator: 'Saving ...',
								tooltip: 'Click to edit campus location',
								type: 'text',
								submit:'Save changes'
							},null],
				fnShowError:function(message, action){
					switch (action) {
						case "update":
							if(message != "success")
								jAlert(message, "Update failed");
							break;
						case "add":
							$("#lblAddError").html(message);
							$("#lblAddError").show();
							break;
					}
				}, 
				fnStartProcessingMode: function () {
					$("#processing_message").dialog();
				},
				fnEndProcessingMode: function () {
					$("#processing_message").dialog("close");
				},
				oAddNewRowButtonOptions: {	
					label: "Add...",
					icons: {primary:'ui-icon-plus'} 
				}							
								
	
			});
	}
});
function fnShowHide( iCol )
{
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_school_campus').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}
