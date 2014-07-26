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
					<th><?php echo $this->lang->line('user_p_full_name'); ?> </th>
					<th>Scan ID</th>
					<!--<th>Gender</th>-->
					<th>KSU Email</th>
					<!--<th>Mobile</th>
					<th><?php //echo $this->lang->line('user_p_role'); ?></th>
					<th>Line Manager</th>
					<th><?php //echo $this->lang->line('user_p_campus'); ?></th>
					<th>Contractor</th>
					<th>Returning</th>-->
					<th><?php echo $this->lang->line('user_p_action'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>DB ID</th>
					<th>ELSD ID</th>
					<th><?php echo $this->lang->line('user_p_full_name'); ?> </th>
					<th>Scan ID</th>
					<!--<th>Gender</th>-->
					<th>KSU Email</th>
					<!--<th>Mobile</th>
					<th><?php //echo $this->lang->line('user_p_role'); ?></th>
					<th>Line Manager</th>
					<th><?php //echo $this->lang->line('user_p_campus'); ?></th>
					<th>Contractor</th>
					<th>Returning</th>-->
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