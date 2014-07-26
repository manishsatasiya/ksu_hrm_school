<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
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
<script type="text/javascript" src="<?php print base_url(); ?>js/grid/list_user.js?t=newv"></script>
<div id="admin">
<?php 
if($this->session->userdata('role_id') == '1' || in_array("add",$this->arrAction))
{
?>
	<div id="add_model_link" class="hide"><a href="list_user/add_profile" class="btn btn-success"><?php echo $this->lang->line('add_new'); ?> <i class="fa fa-plus"></i></a></div>
<?php
} 
?>
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
			  <h4><?php echo $this->lang->line('user_p_heading'); ?></h4>
			</div>
			<div class="grid-body ">
			<div id="processing_message" style="display:none" title="Processing">Please wait while your request is being processed...</div>
			<table class="table" id="grid_other_user">
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
/*	fnShowHide(4);
	fnShowHide(8);
	fnShowHide(6);
	fnShowHide(7);
	 fnShowHide(9);
	 fnShowHide(10);
	 fnShowHide(11);
	$("#showhide_4").attr("checked", "checked");	
	$("#showhide_5").attr("checked", "checked");
	$("#showhide_6").attr("checked", "checked");
	$("#showhide_7").attr("checked", "checked");*/
	<?php
	if($this->session->userdata('role_id') != '1' && !in_array("edit",$this->arrAction))
	{
	?>
		//fnShowHide(8);
	<?php 
	}
	?>
});
</script>  