$(document).ready( function () {
	dTable = $('#grid_viewstatuslog').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: CI.base_url+"list_user/get_viewstatuslog_json/"+user_unique_id,
		"sDom": 'fCl<"clear">rtip',
		aoColumns: [
		            	null, 
		            	null, 
		            	null, 
		            	null, 
		            	null, 
		            	null, 
		            	null, 
		            	null
		           ],
		sPaginationType: "bootstrap"});
	$('#grid_observations_wrapper .dataTables_filter input').addClass("input-medium ");
	$('#grid_observations_wrapper .dataTables_length select').addClass("select2-wrapper span12");		
});