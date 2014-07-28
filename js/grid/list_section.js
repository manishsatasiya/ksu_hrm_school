$(document).ready( function () {
	$('#grid_section').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable = $('#grid_section').dataTable({
			bJQueryUI:false,
			bProcessing:true,
			bServerSide: true,
			sAjaxSource: "list_section/index_json",
			"sDom": 'fCl<"clear">rtip',
			"oColVis": {
				"aiExclude": [table_total_col-1]
	        },
			aoColumns: [null , 
			            {"sName": "section_title"},
			            {"sName": "ca_lead_teacher"},
			            {"sName": "campus_name"},
						{"sName": "track"},
						{"sName": "buildings"},
			            {"sName": "ID",
			            		"bSearchable": false,
			            		"bSortable": false,
			            		"fnRender": function (oObj) {
			            			return '<a href="list_section/add/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal" class="modal-link"><i class="fa fa-edit"></i></a>';
			            		}
			            }
			           ],
			sPaginationType: "bootstrap"});
		$('#grid_section_wrapper .dataTables_filter input').addClass("input-medium ");
		$('#grid_section_wrapper .dataTables_length select').addClass("select2-wrapper span12");	

		dTable.columnFilter({
	        aoColumns: [ 
	                 null,   
	        		 { type: "text" },
	        		 { type: "text" },
	        		 { type: "text" },
	        		 { type: "text" },
	        		 { type: "text" },
					 null,
	            ]
        });	
 	if(edit_flag == 1)
	{	
		dTable.makeEditable({
			sUpdateURL: function(value, settings)
			{
     			return(value); //Simulation of server-side response using a callback function
			},
			sUpdateURL: "list_section/update_section",
            sAddURL: 	"list_section/add_section",
            			"aoColumns": [null,
            			{
							indicator: 'Saving ...',
							tooltip: 'Click to edit section',
							type: 'text',
							submit:'Save changes'
						},
						{
                    		tooltip: 'Click to select CA Lead teacher',
                   			loadtext: 'loading...',
		                    type: 'select',
				           	onblur: 'cancel',
							submit: 'Save',
                    		loadurl: 'list_course_class/get_listbox/ca_lead_teacher',
							loadtype: 'GET'
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
						{
							tooltip: 'Click to select track',
							loadtext: 'loading...',
							type: 'select',
							onblur: 'cancel',
							submit: 'Save',
							loadurl: 'list_course_class/get_listbox/track',
							loadtype: 'GET'
						},
						{
							tooltip: 'Click to select building',
							loadtext: 'loading...',
							type: 'select',
							onblur: 'cancel',
							submit: 'Save',
							loadurl: 'list_course_class/get_listbox/buildings',
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
	var dTable = $('#grid_section').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}
