var asInitVals = new Array();
$(document).ready( function () {
	$('#grid_course_class').bind('processing',function(e, oSettings, bShow){showHideDatatableProcessing(bShow)});
	dTable=	$('#grid_course_class').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "list_course_class/index_json",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			"aiExclude": [table_total_col-1]
        },
		aoColumns: [
		            	{
							"bSortable": false
						},
						null , 
		            	/*{"sName": "school_year_id"},*/
		            	{"sName": "course_id"},
		            	{"sName": "primary_teacher_id"},
		            	{"sName": "secondary_teacher_id"},
		            	{"sName": "class_room_id"},
		            	{"sName": "section_id"},
		            	{"sName": "shift"},
						{"sName": "campus_name"},
						{"sName": "track"},
						{"sName": "buildings"},
		            	{"sName": "student_cnt"},
		            	{	"sName": "ID",
       						"bSearchable": false,
       						"bSortable": false,
       						"fnRender": function (oObj) {
       							//return '<a href="javascript:void(0)" onclick="call_cbox(\'list_student/add/\'" + oObj.aData[0] + "\'')">Edit</a>';
       							var actionstr = "";
								actionstr += '<div class="btn-group">';
								actionstr += '<button class="btn btn-mini dropdown-toggle btn-demo-space" data-toggle="dropdown">';
								actionstr += '<a class="fa fa-gear"></a>';
								actionstr += '</button>';
								actionstr += '<ul class="dropdown-menu">';
								
								actionstr += '<li><a href="list_course_class/add/'+oObj.aData[11]+'" data-target="#myModal" data-toggle="modal" class="modal-link">Edit</a></li>';
		       					if(showattandancelink == 1){
		       						actionstr += '<li><a title="Submit Attendance" href="attendance/index/course_class_id/asc/post/0/'+oObj.aData[5]+'">Submit Attendance</a></li>';
		       					}
		       					
		       					if(showgradereportlink == 1){
		       						actionstr += '<li><a title="Submit Grade" href="grade_report/index/section_id/asc/post/0/'+oObj.aData[5]+'">Submit Grade</a></li>';
		       					}
								actionstr += '</ul>';
								actionstr += '</div>';
		       					return actionstr;
							}
						}
		           ],
		sPaginationType: "bootstrap"
	});
	
	//dTable.$("[id=popover]").popover();
	
	$('#grid_course_class_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_course_class_wrapper .dataTables_length select').addClass("select2-wrapper span12");	
	
		dTable.columnFilter({
        aoColumns: [ 
                 null,   
				 null,   
        		 /*{ type: "text" },*/
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
				sUpdateURL: "list_course_class/update_course_class",
				sAddURL: 	"list_course_class/add_course_class",
				aoColumns: [
						null,   
						null,
						/*{
							tooltip: 'Click to select school year',
							loadtext: 'loading...',
							type: 'select',
							onblur: 'cancel',
							submit: 'Save',
							loadurl: 'list_course_class/get_listbox/school_year',
							loadtype: 'GET'
						},*/
						{
							tooltip: 'Click to select course class',
							loadtext: 'loading...',
							type: 'select',
							onblur: 'cancel',
							submit: 'Save',
							loadurl: 'list_course_class/get_listbox/course_class',
							loadtype: 'GET'
						},
						{
							tooltip: 'Click to select primary teacher',
							loadtext: 'loading...',
							type: 'select',
							onblur: 'cancel',
							submit: 'Save',
							loadurl: 'list_course_class/get_listbox/primary_teacher',
							loadtype: 'GET'
						},
						{
							tooltip: 'Click to select secondary teacher',
							loadtext: 'loading...',
							type: 'select',
							onblur: 'cancel',
							submit: 'Save',
							loadurl: 'list_course_class/get_listbox/primary_teacher',
							loadtype: 'GET'
						},
						{
							tooltip: 'Click to select class room',
							loadtext: 'loading...',
							type: 'select',
							onblur: 'cancel',
							submit: 'Save',
							loadurl: 'list_course_class/get_listbox/class_room',
							loadtype: 'GET'
						},
				   /* 	{
							tooltip: 'Click to select section',
							loadtext: 'loading...',
							type: 'select',
							onblur: 'cancel',
							submit: 'Save',
							loadurl: 'list_course_class/get_listbox/section',
							loadtype: 'GET'
						}*/
						null,
						{
							tooltip: 'Click to select shift',
							loadtext: 'loading...',
							type: 'select',
							onblur: 'cancel',
							submit: 'Save',
							loadurl: 'list_course_class/get_listbox/shift',
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
						null,
						null
				   ],
				fnShowError:function(message, action){
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
	
	$('#grid_course_class tbody td i').live('click', function () {
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

function fnShowHide( iCol )
{
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_course_class').dataTable();
	
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
            url: "list_course_class/view_log_report/"+aData[1],
            data: parametros
        });
}
