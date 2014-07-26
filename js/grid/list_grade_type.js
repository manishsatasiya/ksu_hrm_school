$(document).ready( function () {
	dTable =	$('#grid_grade_type').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "list_grade_type/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [null , 
		            {"sName": "grade_type"},
		            {"sName": "total_markes"},
		            {"sName": "total_percentage"},
		            /*-{"sName": "show_grade_range"},*/
			    {"sName": "attendance_type"},
		            {"sName": "ID",
		            		"bSearchable": false,
		            		"bSortable": false,
		            		"fnRender": function (oObj) {
								return '<a href="list_grade_type/add/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal" class="modal-link"><i class="fa fa-edit"></i></a>';
		            		}
		            }
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_grade_type_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_grade_type_wrapper .dataTables_length select').addClass("select2-wrapper span12");		
	dTable.columnFilter({
	        aoColumns: [ 
	                 null,   
	        		 { type: "text" },
	        		 { type: "text" },
	        		 { type: "text" },
	        		 /*{ type: "text" },*/
				{ type: "text" },
	                 null
	            ]
        });	
if(edit_flag == 1)
{
	dTable.makeEditable({
			sUpdateURL: "list_grade_type/update_grade_type",
            sAddURL: 	"list_grade_type/add_grade_type",
            			"aoColumns": [null,
						{
							indicator: 'Saving ...',
							tooltip: 'Click to edit Type',
							type: 'text',
							submit:'Save changes'
						},
						{
							indicator: 'Saving ...',
							tooltip: 'Click to edit marks',
							type: 'text',
							submit:'Save changes'
						},
						{
							indicator: 'Saving ...',
							tooltip: 'Click to edit percentage',
							type: 'text',
							submit:'Save changes'
						},
						/*{
                    		indicator: 'Saving Name display',
                			tooltip: 'Click to select display',
                			loadtext: 'loading...',
		                    type: 'select',
				           	onblur: 'cancel',
							submit: 'Save',
				           	data: "{'Y':'Yes','N':'No'}"
                    	},*/
						{
                    		indicator: 'Saving Name attandance type',
                			tooltip: 'Click to select attandance type',
                			loadtext: 'loading...',
		                    type: 'select',
				           	onblur: 'cancel',
							submit: 'Save',
				           	data: "{'common':'Common','examwise':'Examwise'}"
                    	},
						,null],
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
	var dTable = $('#grid_grade_type').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}
