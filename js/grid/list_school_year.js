$(document).ready( function () {
	$('#grid_school_year').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable = $('#grid_school_year').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "list_school_year/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [
		            	null , 
		            	{"sName": "school_id"},
		            	{"sName": "school_year"},
		            	{"sName": "school_year_title"},
		            	{"sName": "school_type"},	
		            	{"sName": "school_week"},
		            	{"sName": "ID",
       						"bSearchable": false,
       						"bSortable": false,
       						"fnRender": function (oObj) {
       							return '<a href="list_school_year/add/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal" class="modal-link"><i class="fa fa-edit"></i></a>';
							}
						}
		            	
		           ],
		sPaginationType: "bootstrap"});
		$('#grid_school_year_wrapper .dataTables_filter input').addClass("input-medium ");
		$('#grid_school_year_wrapper .dataTables_length select').addClass("select2-wrapper span12");		
		dTable.columnFilter({
	        aoColumns: [ 
	                 null,   
	        		 { type: "text" },
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
			sUpdateURL: function(value, settings)
			{
     			return(value); //Simulation of server-side response using a callback function
			},
			sUpdateURL: "list_school_year/update_school_year",
            sAddURL: 	"list_school_year/add_school_year",
            aoColumns: [
                        	null,
                        	{
                        		tooltip: 'Click to select school year',
                       			loadtext: 'loading...',
			                    type: 'select',
					           	onblur: 'cancel',
								submit: 'Save',
                        		loadurl: 'list_school_year/get_listbox/school',
								loadtype: 'GET'
                        	},
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit school year',
    							type: 'text',
    							submit:'Save changes'
                        	},
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit school year title',
    							type: 'text',
    							submit:'Save changes',
    							cssclass:"required"	
                        	},
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit school type',
    							type: 'text',
    							submit:'Save changes'
                        	},
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit school week',
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
	if($("#Col-"+iCol).is(':checked'))
		$("#Col-"+iCol).attr("checked", "checked");
	else
		$("#Col-"+iCol).removeAttr("checked");	
		
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_school_year').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}