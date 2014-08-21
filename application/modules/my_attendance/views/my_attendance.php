<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
var edit_flag = 1;
var user_unique_id = 0;
<?php 
if($this->session->userdata('role_id') != '1' && !in_array("edit",$this->arrAction))
{
?>
	edit_flag = 0;
<?php 
}
?>
</script>
<script type="text/javascript" src="<?php print base_url(); ?>js/grid/my_attendance.js"></script>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
        	<div class="modal-header">
              <h2>Loading....</h2>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
            </div>
            <div class="modal-body"><div style="text-align:center;"><i class="fa fa-spinner fa fa-6x fa-spin" id="animate-icon"></i></div></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
		 </div>	
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->		


<div class="row-fluid">
	<div class="span12">
		<div class="grid simple ">
			<div class="grid-title">
			  <h4>My Attendance</h4>
			</div>
			<div class="grid-body ">
			<div id="processing_message" class="hide" title="Processing">Please wait while your request is being processed...</div>
			<table class="table" id="grid_my_attendance">
				<thead>
					<tr>
						<th style="width:77px">ID</th>
						<th>Scan ID</th>
						<th>Log Date</th>
						<th>In Time</th>
						<th>Out Time</th>
						<th>Total Hours</th>
						<th>Late</th>
						<th>Approved</th>
						<th style="width:150px">StartTime</th>
						<th style="width:150px">EndTime</th>
					</tr>
				</thead>
				<tbody></tbody>
				<tfoot>
					<tr>
						<th style="width:77px">ID</th>
						<th>Scan ID</th>
						<th>Log Date</th>
						<th>In Time</th>
						<th>Out Time</th>
						<th>Total Hours</th>
						<th>Late</th>
						<th>Approved</th>
						<th style="width:150px">StartTime</th>
						<th style="width:150px">EndTime</th>
					</tr>
				</tfoot>
			</table>
			</div>
		</div>
	</div>
</div>  
<script language="javascript">
$(document).ready(function() {
	<?php 
	if($this->session->userdata('role_id') != '1' && !in_array("edit",$this->arrAction))
	{
	?>
		fnShowHide(table_total_col-1);
	<?php 
	}
	?>
});
</script>