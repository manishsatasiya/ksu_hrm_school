$(document).ready( function () {
	$('#grid_other_user').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable = $('#grid_other_user').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "list_interviews/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [
						null ,
						{"sName": "elsd_id"},
		            	{"sName": "first_name"},
		            	{"sName": "email"},
		            	{"sName": "personal_email"},
		            	{"sName": "cell_phone"},
		            	{"sName": "nationality"},
						{"sName": "birth_date"},
						{"sName": "contractor"},
						{"sName": "campus"},
						{"sName": "contractor"},
						{"sName": "ID",
       						"bSearchable": false,
       						"bSortable": false,
						},
						{"sName": "interview_date"},
						{"sName": "interviewee1"},
						{"sName": "interviewee2"},
						{"sName": "interview_type"},
						{"sName": "interview_outcome"},
						{"sName": "created_date"},
						{"sName": "updated_date"},
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
								actionstr += '<li><a href="list_user/add/'+oObj.aData[parseInt(table_total_col-1)]+'" data-target="#myModal" data-toggle="modal" class="modal-link">Edit</a></li>';
								actionstr += '<li><a href="list_user/edit_profile/'+oObj.aData[parseInt(table_total_col-1)]+'">View Profile</a></li>';
								//actionstr += '<li><a href="add_privilege/add_single_user_privilege/index/'+oObj.aData[parseInt(table_total_col-1)]+'">Rights</a></li>';
								actionstr += '<li class="divider"></li>';
								actionstr += '<li><a href="#" onclick=dt_delete("users","user_id",'+oObj.aData[parseInt(table_total_col-1)]+'); class="text-error bold">Delete Profile <i class="fa fa-times-circle"></i></a></li>';
								actionstr += '</ul>';
								actionstr += '</div>';
								
								return actionstr;
							}
						}
		            	
		           ],
		sPaginationType: "bootstrap"});
		$('#grid_other_user_wrapper .dataTables_filter input').addClass("input-medium ");
		$('#grid_other_user_wrapper .dataTables_length select').addClass("select2-wrapper span12"); 
		dTable.columnFilter({
	        aoColumns: [    
	                 null,
					 { type: "text" },
					 { type: "text" },
	        		 { type: "text" },
	                 { type: "text" },
	                 { type: "text" },
	                 { type: "text" },
	                 { type: "text" },
	                 { type: "text" },
					 null,
	                 null,
	                 null,
	                 null,
					 null,
	                 null,
	                 null,
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
			sUpdateURL: "list_user/update_user",
            sAddURL: 	"list_user/add_student",
            aoColumns: [
                        	null,
                        	null,
                        	null,
                        	null,
                        	null,
							null,
                        	null,
                        	null,
                        	null,
                        	null,
							null,
                        	null,
                        	null,
                        	null,
                        	null,
                        	/*{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit name',
    							type: 'text',
    							submit:'Save changes'
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
                        		tooltip: 'Click to select roll',
                       			loadtext: 'loading...',
			                    type: 'select',
					           	onblur: 'cancel',
								submit: 'Save',
                        		loadurl: 'list_user/get_listbox/other_user_roll',
								loadtype: 'GET'
                        	},
                        	{
                        		indicator: 'Saving ...',
    							tooltip: 'Click to edit email',
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
    							tooltip: 'Click to edit cell phone',
    							type: 'text',
    							submit:'Save changes'
                        	},*/
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
	var dTable = $('#grid_other_user').dataTable();
	
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}