$(document).ready( function () {
	$('#grid_course_category').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable =	$('#grid_course_category').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "list_course_category/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [null , 
		            {"sName": "category_title"},
		            {"sName": "ID",
		            		"bSearchable": false,
		            		"bSortable": false,
		            		"fnRender": function (oObj) {
								return '<a href="list_course_category/add/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal"><i class="fa fa-edit"></i></a>';
		            		}
		            }
		           ],
		sPaginationType: "bootstrap"})
	dTable.columnFilter({
	        aoColumns: [ 
	                 null,   
	        		 { type: "text" },
	                 null
	            ]
        });	
if(edit_flag == 1)
{
	dTable.makeEditable({
			sUpdateURL: "list_course_category/update_course_category",
            sAddURL: 	"list_course_category/add_course_category",
            			"aoColumns": [null,
						{
							indicator: 'Saving ...',
							tooltip: 'Click to edit category',
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
	var dTable = $('#grid_course_category').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}
