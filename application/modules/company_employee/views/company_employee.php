<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var edit_flag = 1;
var edit_profile_flag = 1;
<?php 
if($this->session->userdata('role_id') != '1' && !in_array("edit",$this->arrAction))
{
?>
	edit_flag = 0;
<?php 
}
if($this->session->userdata('role_id') != '1' && !in_array("edit_profile",$this->arrAction))
{
?>
	edit_profile_flag = 0;
<?php 
}
?>
</script>
<script type="text/javascript" src="<?php print base_url(); ?>js/grid/company_employee.js"></script>
<div id="admin">

<div class="row-fluid">
	<div class="span12">
		<div class="grid simple ">
			<div class="grid-title">
			  <h4>Company employee</h4>
			</div>
			<div class="grid-body ">
			<table class="table" id="grid_company_employee">
			<thead>
				<tr>
					<th>DB ID</th>
					<th>ELSD ID</th>
					<th>Staff Name</th>
					<th>Status</th>
					<th>KSU E-mail</th>
					<th>Personal E-mail</th>
					<th>Mobile No</th>
					<th>Company</th>
					<th>Nationality</th>
					<th>DOB</th>
					<th>Interview Evaluation Form</th>
					<th>Date Added</th>
					<th>Last Updated</th>
					<th><?php echo $this->lang->line('user_p_action'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>DB ID</th>
					<th>ELSD ID</th>
					<th>Staff Name</th>
					<th>Status</th>
					<th>KSU E-mail</th>
					<th>Personal E-mail</th>
					<th>Mobile No</th>
					<th>Company</th>
					<th>Nationality</th>
					<th>DOB</th>
					<th>Company</th>
					<th>Interview Evaluation Form</th>
					<th>Date Added</th>
					<th>Last Updated</th>
					<th><?php echo $this->lang->line('user_p_action'); ?></th>
				</tr>
			</tfoot>
			<tbody>
			
			</tbody>
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