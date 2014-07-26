<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
var edit_flag = 1;
<?php 
if($this->session->userdata('role_id') != '1' && !in_array("edit",$this->arrAction))
{
?>
	edit_flag = 0;
<?php 
}
?>
</script>
<script type="text/javascript" src="<?php print base_url(); ?>js/grid/workshops.js"></script>

<?php 
if($this->session->userdata('role_id') == '1' || in_array("add",$this->arrAction))
{
?>
	<div id="add_model_link" class="hide">
		<a href="workshops/add" class="btn btn-success" data-target="#myModal" data-toggle="modal"><?php echo $this->lang->line('add_new'); ?> <i class="fa fa-plus"></i></a>
		<a href="workshops/add_attendee" class="btn btn-success" data-target="#myModal" data-toggle="modal">Add Attendee <i class="fa fa-plus"></i></a>
	</div>
<?php 
}
?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">Loading....</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->		

<div class="row-fluid">
	<div class="span12">
		<div class="grid simple ">
			<div class="grid-title">
			  <h4>Active workshops</h4>
			</div>
			<div class="grid-body ">
			<div id="processing_message" class="hide" title="Processing">Please wait while your request is being processed...</div>
			<table class="table" id="grid_workshops">
				<thead>
					<tr>
						<th style="width:77px">ID</th>
						<th>Workshop</th>
						<th style="width:150px">Date</th>
						<th style="width:77px">Time</th>
						<th style="width:77px">Presenter</th>
						<th style="width:77px">Type</th>
						<th style="width:77px">Venue</th>
						<th style="width:77px">Max attendees</th>
						<th>Registered</th>
    					<th>Spaces</th>
						<th style="width:80px"><?php print $this->lang->line('student_p_action'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th style="width:77px">ID</th>
						<th>Workshop</th>
						<th style="width:150px">Date</th>
						<th style="width:77px">Time</th>
						<th style="width:77px">Presenter</th>
						<th style="width:77px">Type</th>
						<th style="width:77px">Venue</th>
						<th style="width:77px">Max attendees</th>
						<th>Registered</th>
    					<th>Spaces</th>
						<th><?php print $this->lang->line('student_p_action'); ?></th>
					</tr>
				</tfoot>
				<tbody></tbody>
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
		//fnShowHide(6);
	<?php 
	}
	?>
});
</script>  	  
