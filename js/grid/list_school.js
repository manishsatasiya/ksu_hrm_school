$(document).ready( function () {
	dTable = $('#grid_school').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "list_school/index_json",
        "sDom": 'fCl<"clear">rtip',
        "oColVis": {
            "aiExclude": [table_total_col-1]
        },
		"bPaginate": false,
		bFilter: false,
		aoColumns: [
		            	null , 
		            	{"sName": "school_name"},
		            	{"sName": "principal"},
		            	{"sName": "email"},
		            	{"sName": "www_address"},	
		            	{"sName": "address"},
		            	{"sName": "city"},
		            	{"sName": "state"},
		            	{"sName": "zip"},
		            	{"sName": "area_code"},
		            	{"sName": "phone"},
		            	{"sName": "ID",
       						"bSearchable": false,
       						"bSortable": false,
       						"fnRender": function (oObj) {
								return '<a href="list_school/add/'+oObj.aData[0]+'" data-target="#myModal" data-toggle="modal"><i class="fa fa-edit"></i></a>';
							}
						}
		            	
		           ]});	
	if(edit_flag == 1)
	{
		dTable.makeEditable({
			sUpdateURL: function(value, settings)
			{
     			return(value); //Simulation of server-side response using a callback function
			},
			sUpdateURL: "list_school/update_school",
            sAddURL: 	"list_school/add_school",
            aoColumns: [
                        	null,
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit school name',
    							type: 'text',
    							submit:'Save changes'
                        	},
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit principal name',
    							type: 'text',
    							submit:'Save changes'
                        	},
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit email',
    							type: 'text',
    							submit:'Save changes'
                        	},
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit website',
    							type: 'text',
    							submit:'Save changes',
    							cssclass:"required"	
                        	},
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit address',
    							type: 'text',
    							submit:'Save changes'
                        	},
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit city',
    							type: 'text',
    							submit:'Save changes'
                        	},
                        	
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit state',
    							type: 'text',
    							submit:'Save changes'
                        	},
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit zip',
    							type: 'text',
    							submit:'Save changes'
                        	},
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit area code',
    							type: 'text',
    							submit:'Save changes'
                        	},
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit cell phone',
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
	if($("#showhide_"+iCol).is(':checked'))
		$("#showhide_"+iCol).attr("checked", "checked");
	else
		$("#showhide_"+iCol).removeAttr("checked");	
		
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_school').dataTable();
	
	var bVis = dTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}