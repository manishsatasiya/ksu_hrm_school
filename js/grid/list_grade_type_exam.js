$(document).ready( function () {
	dTable =	$('#grid_grade_type_exam').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "list_grade_type_exam/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [null , 
		            {"sName": "grade_type_id"},
		            {"sName": "exam_type_name"},
		            {"sName": "exam_marks"},
		            {"sName": "exam_percentage"},
		            {"sName": "ID",
		            		"bSearchable": false,
		            		"bSortable": false,
		            		"fnRender": function (oObj) {
								return '<a href="list_grade_type_exam/add/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal" class="modal-link"><i class="fa fa-edit"></i></a>';
		            		}
		            }
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_grade_type_exam_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_grade_type_exam_wrapper .dataTables_length select').addClass("select2-wrapper span12");			
	dTable.columnFilter({
	        aoColumns: [ 
	                 null,   
	                 { type: "text" },
	        		 { type: "text" },
	        		 { type: "text" },
	        		 { type: "text" },
	                 null
	            ]
        });	
if(edit_flag == 1)
{
	dTable.makeEditable({
			sUpdateURL: "list_grade_type_exam/update_grade_type_exam",
            sAddURL: 	"list_grade_type_exam/add_grade_type_exam",
            			"aoColumns": [null,
            			{
                    		tooltip: 'Click to select grade type exam',
                   			loadtext: 'loading...',
		                    type: 'select',
				           	onblur: 'cancel',
							submit: 'Save',
                    		loadurl: 'list_grade_type_exam/get_listbox/grade_type',
							loadtype: 'GET'
                    	},
						{
							indicator: 'Saving ...',
							tooltip: 'Click to edit exam Type',
							type: 'text',
							submit:'Save changes'
						},
						{
							indicator: 'Saving ...',
							tooltip: 'Click to edit exam marks',
							type: 'text',
							submit:'Save changes'
						},
						{
							indicator: 'Saving ...',
							tooltip: 'Click to edit exam percentage',
							type: 'text',
							submit:'Save changes'
						}
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
	var dTable = $('#grid_grade_type_exam').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}
