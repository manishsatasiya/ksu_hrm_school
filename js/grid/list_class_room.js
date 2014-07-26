$(document).ready( function () {
	dTable = $('#grid_class_room').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "list_class_room/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [
		            null , 
		            {"sName": "class_room_title"},
					{"sName": "campus_name"},
		            {"sName": "ID",
	            		"bSearchable": false,
	            		"bSortable": false,
	            		"fnRender": function (oObj) {
	            			return '<a href="list_class_room/add/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal" class="modal-link"><i class="fa fa-edit"></i></a>';
	            		}
		            }
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_class_room_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_class_room_wrapper .dataTables_length select').addClass("select2-wrapper span12");	
	dTable.columnFilter({
	        aoColumns: [ 
	                 null,   
	        		 { type: "text" },
	        		 { type: "text" },
	                 null
	            ]
        });		
if(edit_flag == 1)
{		
	dTable.makeEditable({
			sUpdateURL: function(value, settings)
			{
     			return(value); //Simulation of server-side response using a callback function
			},
			sUpdateURL: "list_class_room/update_class_room",
            sAddURL: 	"list_class_room/add_class_room",
            			"aoColumns": [null,
            			{
							indicator: 'Saving ...',
							tooltip: 'Click to edit Class Room',
							type: 'text',
							submit:'Save changes'
						},
						{
							tooltip: 'Click to select campus',
							loadtext: 'loading...',
							type: 'select',
							onblur: 'cancel',
							submit: 'Save',
							loadurl: 'list_course_class/get_listbox/campus',
							loadtype: 'GET'
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
}
});
function fnShowHide( iCol )
{
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_class_room').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}
