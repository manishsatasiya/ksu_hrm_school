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
var workshop_id = <?=$workshop_id?>;
</script>
<script type="text/javascript" src="<?php print base_url(); ?>js/grid/workshop_sign_up_sheet.js"></script>


<div class="row-fluid">
	<div class="grid simple horizontal red">
		<div class="grid-title">
			<h4>Workshop: <?=$title;?></h4>
			<div class="tools"> <a href="javascript:;" class="collapse"></a></div>
		</div>
		<div class="grid-body">
        	<div class="row">
			<div class="col-md-12">
				<div class="col-md-2">
					Presenter:<?=$presenter_name?>
				</div>
				<div class="col-md-2">
					Date:<?=$start_date?>
				</div>
				<div class="col-md-2">
					Time:<?=$time?>
				</div>
				<div class="col-md-2">
					Venue:<?=$venue?>
				</div>
				<div class="col-md-1">
					Limit:<?=$attendee_limit?>
				</div>
				<div class="col-md-1">
					Registered:<?=$registered?>
				</div>
				<div class="col-md-2">
					Workshop type:<?=$workshop_type?>
				</div>
			</div>
            </div>
		</div>
	</div>
</div>
<?php 
if($this->session->userdata('role_id') == '1' || in_array("add",$this->arrAction))
{
?>
	<?php /* ?><div id="add_model_link" class="hide">
		<a href="<?php print base_url(); ?>workshops/add_attendee/0/0/<?=$workshop_id?>" class="btn btn-success" data-target="#myModal" data-toggle="modal">Add Attendee <i class="fa fa-plus"></i></a>
	</div><?php */ ?>
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
			  <h4>Attendee List</h4>
			</div>
			<div class="grid-body ">
			<div id="processing_message" class="hide" title="Processing">Please wait while your request is being processed...</div>
			<table class="table" id="grid_workshop_sign_up_sheet">
				<thead>
					<tr>
						<th>ID</th>
						<th>Attendee</th>
						<th>Staff ID</th>
						<th>Signature</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Attendee</th>
						<th>Staff ID</th>
						<th>Signature</th>
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
