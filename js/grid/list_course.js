$(document).ready( function () {
	dTable = $('#grid_course').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "list_course/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [null , 
		            {"sName": "course_title"},
		            {"sName": "max_hours"},
		            {"sName": "total_hours_all_weeks"},
		            {"sName": "ID",
	            		"bSearchable": false,
	            		"bSortable": false,
	            		"fnRender": function (oObj) {
	            			return '<a href="list_course/add/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal" class="modal-link"><i class="fa fa-edit"></i></a>';
	            		}
		            }
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_course_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_course_wrapper .dataTables_length select').addClass("select2-wrapper span12");		
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
			sUpdateURL: function(value, settings)
			{
     			return(value); //Simulation of server-side response using a callback function
			},
			sUpdateURL: "list_course/update_course",
            sAddURL: 	"list_course/add_course",
            			"aoColumns": [null,
            			{
							indicator: 'Saving ...',
							tooltip: 'Click to edit title',
							type: 'text',
							submit:'Save changes'
						},
						{
							indicator: 'Saving ...',
							tooltip: 'Click to edit Max Hrs.',
							type: 'text',
							submit:'Save changes'
						},
						{
							indicator: 'Saving ...',
							tooltip: 'Click to edit Total Hrs.',
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
}
});
function fnShowHide( iCol )
{
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_course').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}