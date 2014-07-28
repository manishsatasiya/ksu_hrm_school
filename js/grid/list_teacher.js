$(document).ready( function () {
	$('#grid_student').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable = $('#grid_student').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "list_teacher/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [
		            	null , 
		            	{"sName": "first_name"},
		            	//{"sName": "section_title"},
						{"sName": "campus"},
		            	{"sName": "username"},
		            	{"sName": "password"},	
		            	{"sName": "email"},
		            	{"sName": "ID",
       						"bSearchable": false,
       						"bSortable": false,
       						"fnRender": function (oObj) {
								var actionstr = "";
								actionstr += '<div class="btn-group">';
								//actionstr += '<button class="btn btn-mini btn-primary btn-demo-space">OPTIONS</button>';
								actionstr += '<button class="btn-axn" data-toggle="dropdown">';
								//actionstr += '<span class="caret"></span>';
								actionstr += '<a class="fa fa-gear"></a>';
								actionstr += '</button>';
								actionstr += '<ul class="dropdown-menu">';
								actionstr += '<li><a href="list_teacher/add/'+oObj.aData[parseInt(table_total_col-1)]+'" data-target="#myModal" data-toggle="modal" class="modal-link">Edit</a></li>';
								actionstr += '<li><a href="list_user/edit_profile/'+oObj.aData[parseInt(table_total_col-1)]+'">View Profile</a></li>';
								actionstr += '</ul>';
								actionstr += '</div>';
								
								return actionstr;
								
								//return '<a href="list_teacher/add/'+oObj.aData[6]+'" data-target="#myModal" data-toggle="modal" class="modal-link"><i class="fa fa-edit"></i></a>';
							}
						}
		            	
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_student_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_student_wrapper .dataTables_length select').addClass("select2-wrapper span12");		
		dTable.columnFilter({
	        aoColumns: [ 
	                 { type: "text" },
	        		 { type: "text" },
	                 { type: "text" },
	                 { type: "text" },
	                 null,
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
			sUpdateURL: "list_teacher/update_student",
            sAddURL: 	"list_teacher/add_student",
            aoColumns: [
                        	null,
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit name',
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
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit username',
    							type: 'text',
    							submit:'Save changes'
                        	},
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit password',
    							type: 'text',
    							submit:'Save changes',
    							cssclass:"required"	
                        	},
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit email',
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
	var dTable = $('#grid_student').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}