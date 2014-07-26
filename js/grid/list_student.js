var asInitVals = new Array();
$(document).ready( function () {
	$('#grid_student').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable=	$('#grid_student').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "list_student/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [0,table_total_col-1]
        },
		aoColumns: [
		            	{
							"bSortable": false
						},
		            	{"sName": "student_uni_id"},
						{"sName": "first_name"},
						{"sName": "student_schedule_date"},
						{"sName": "section_title"},
						{"sName": "campus"},
						{"sName": "track"},
						{"sName": "buildings"},
						{"sName": "academic_status"},
		            	
		            	{	"sName": "ID",
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
									actionstr += '<li><a href="list_student/add/'+oObj.aData[parseInt(table_total_col-1)]+'" data-target="#myModal" data-toggle="modal" class="modal-link">Edit Student</a></li>';
									if(showattandancelink == 1){
									actionstr += '<li><a href="attendance/index/course_class_id/asc/post/0/0/'+oObj.aData[parseInt(table_total_col-1)]+'">Submit Attendance</a></li>';
									}
									if(showgradereportlink == 1){
									actionstr += '<li><a href="grade_report/index/section_id/asc/post/0/0/'+oObj.aData[parseInt(table_total_col-1)]+'">Submit Grade</a></li>';
									}
									actionstr += '<li class="divider"></li>';
									actionstr += '<li><a href="#" onclick=dt_delete("users","user_id",'+oObj.aData[parseInt(table_total_col-1)]+'); class="text-error bold">Delete Student <i class="fa fa-times-circle"></i></a></li>';
									actionstr += '</ul>';
									actionstr += '</div>';

			       					return actionstr;
							}
						}
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_student_wrapper .dataTables_filter input').addClass("input-medium ");
    $('#grid_student_wrapper .dataTables_length select').addClass("select2-wrapper span12"); 
	dTable.columnFilter({
        aoColumns: [ 
				null,
				{ type: "text" },
				{ type: "text" },
				null,
				{ type: "text" },
				
				{ type: "text" },
				null,
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
				sUpdateURL: "list_student/update_student",
	            sAddURL: 	"list_student/add_student",
	            aoColumns: [
	                        	null,
	                        	null,
								{
	                        		indicator: 'Saving ...',
	    							tooltip: 'Click to edit firstname',
	    							type: 'text',
	    							submit:'Save changes',
	    							cssclass:"required"	
	
	                        	},   
	                        	null,
								{
	                        		tooltip: 'Click to select section',
	                       			loadtext: 'loading...',
				                    type: 'select',
						           	onblur: 'cancel',
									submit: 'Save',
	                        		loadurl: 'list_student/get_listbox/section',
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
								null,
								null,
								{
	                        		indicator: 'Saving De(Active)',
	                    			tooltip: 'Click to select Regular or Withdrawn or Denied',
	                    			loadtext: 'loading...',
				                    type: 'select',
						           	onblur: 'cancel',
									submit: 'Save',
						           	data: "{'Regular':'Regular','Withdrawn':'Withdrawn','Denied':'Denied'}"
	                        	},                 	
	                        	null
	                        	
	                       ],
	           fnShowError: function (message, action) {
	               switch (action) {
	                   case "update":
	                   	if(message != "success")
	                   		jAlert2(message, "Please Enter Updated Reason",null,'Cancel');
							//$("#popup_panel").html("");
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
	
	/* Add event listener for opening and closing details
     * Note that the indicator for showing which row is open is not controlled by DataTables,
     * rather it is done here
     */
    $('#grid_student tbody td i').live('click', function () {
        var nTr = $(this).parents('tr')[0];
        if ( dTable.fnIsOpen(nTr) )
        {
			/* This row is already open - close it */
			$(this).removeClass("fa fa-minus-circle");
			$(this).addClass("fa fa-plus-circle");     
			dTable.fnClose(nTr);
        }
        else
        {
			/* Open this row */
            $(this).removeClass("fa fa-plus-circle");
            $(this).addClass("fa fa-minus-circle");  
			var data = fnFormatDetailsData(dTable, nTr);
			$.when(data).then(function(theData) {
				dTable.fnOpen( nTr, theData, 'details');
			});
        }
    });	
});
//$("div.toolbar").html('<div class="table-tools-actions"><button class="btn btn-primary" id="test2">Add</button></div>');
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

/* Formating function for row details */
function fnFormatDetailsData( oTable, nTr )
{
	var sOut="";
    var aData = oTable.fnGetData( nTr );
	var parametros = {
            NumPA: aData[1]
        };
    var parametros = jQuery.param(parametros);
	return $.ajax({
            type: "POST",
            url: "list_student/view_report/"+aData[1],
            data: parametros
        });
}

function showHideDatatableProcessing(showhide)
{
	if(showhide == true){
		$(".dataTable tbody").html('<div style="text-align: center;width: auto;position: absolute;margin-left: 500px;"><i style="display:inline-block;" class="fa fa-spinner fa fa-6x fa-spin" id="animate-icon"></i></div>');
		$(".dataTables_processing").html('');
	}
}