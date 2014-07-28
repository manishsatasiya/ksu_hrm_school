$(document).ready( function () {
	 $('#grid_role').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	 dTable = $('#grid_role').dataTable({
			bJQueryUI:false,
			bProcessing:true,
			bServerSide: true,
			sAjaxSource: "list_role/index_json",
			"sDom": 'fCl<"clear">rtip',
			"oColVis": {
				"aiExclude": [table_total_col-1]
	        },
			aoColumns: [null , 
			            {"sName": "user_roll_name"},
			            {"sName": "ID",
			            		"bSearchable": false,
			            		"bSortable": false,
			            		"fnRender": function (oObj) {
			            			return '<a href="list_role/add/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal" class="modal-link"><i class="fa fa-edit"></i></a>';
			            		}
			            }
			           ],
			sPaginationType: "bootstrap"});
	$('#grid_role_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_role_wrapper .dataTables_length select').addClass("select2-wrapper span12");		
		dTable.columnFilter({
	        aoColumns: [ 
	                 null,   
	        		 { type: "text" },
	                 null
	            ]
        });	
		
		dTable.makeEditable({
			sUpdateURL: function(value, settings)
			{
     			return(value); //Simulation of server-side response using a callback function
			},
			sUpdateURL: "list_role/update_role",
            sAddURL: 	"list_role/add_role",
            			"aoColumns": [null,
            			{
							indicator: 'Saving ...',
							tooltip: 'Click to edit category',
							type: 'text',
							submit:'Save changes'
						},
						null
						],
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
	
});
function fnShowHide( iCol )
{
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_role').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}
