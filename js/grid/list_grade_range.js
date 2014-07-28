$(document).ready( function () {
	$('#grid_grade_range').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable =	$('#grid_grade_range').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "list_grade_range/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [null , 
		            {"sName": "grade_min_range"},
		            {"sName": "grade_max_range"},
		            {"sName": "grade_name"},
		            {"sName": "ID",
		            		"bSearchable": false,
		            		"bSortable": false,
		            		"fnRender": function (oObj) {
								return '<a href="list_grade_range/add/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal" class="modal-link"><i class="fa fa-edit"></i></a>';
		            		}
		            }
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_grade_range_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_grade_range_wrapper .dataTables_length select').addClass("select2-wrapper span12");		
	dTable.columnFilter({
	        aoColumns: [ 
	                 null,   
	        		 { type: "text" },
	        		 { type: "text" },
	        		 { type: "text" },
	                 null
	            ]
        });	
if(edit_flag == 1)
{
	dTable.makeEditable({
			sUpdateURL: "list_grade_range/update_grade_range",
            sAddURL: 	"list_grade_range/add_grade_range",
            			"aoColumns": [null,
						{
							indicator: 'Saving ...',
							tooltip: 'Click to edit minimum rage',
							type: 'text',
							submit:'Save changes'
						},
            			{
							indicator: 'Saving ...',
							tooltip: 'Click to edit maximum range',
							type: 'text',
							submit:'Save changes'
						},
						{
							indicator: 'Saving ...',
							tooltip: 'Click to edit grade name',
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
	var dTable = $('#grid_grade_range').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}
