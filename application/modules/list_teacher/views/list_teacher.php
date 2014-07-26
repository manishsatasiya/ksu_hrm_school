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
<script type="text/javascript" src="<?php print base_url(); ?>js/grid/list_teacher.js"></script>

<?php 
if($this->session->userdata('role_id') == '1' || in_array("add",$this->arrAction))
{
?>
	<div id="add_model_link" class="hide"><a href="list_teacher/add" class="btn btn-success" data-target="#myModal" data-toggle="modal"><?php echo $this->lang->line('add_new'); ?> <i class="fa fa-plus"></i></a></div>
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
			  <h4><?php echo $this->lang->line('teacher_p_heading'); ?></h4>
			    <div class="row export_to_excel">
					<form id="export_file" action="list_teacher/export_to_excel" target="download_iframe" method="POST">
						<div class="col-md-8">	
							<input type="hidden" name="search_section" id="search_section" value="">
							<select name="campus">
									<option value="0"><?php echo $this->lang->line('get_pdf_p_field_all'); ?> <?php echo $this->lang->line('campus'); ?></option>
									<?php 		 
									if(count($school_campus) > 0) {
										foreach($school_campus as $campus_drop){
											echo '<option value="'.$campus_drop['campus_id'].'">'.$campus_drop['campus_name'].'</option>';
										} 	
									}?>
							</select>
						</div>
						<div class="col-md-4">
							<input type="submit" name="submit" value="Export To XLS" class="btn btn-info">
						</div>
					</form>
				</div>
			</div>
			<div class="grid-body ">
				<div id="processing_message" style="display:none" title="Processing">Please wait while your request is being processed...</div>
				<table class="table" id="grid_student">
					<thead>
						<tr>
							<th width="20"><?php echo $this->lang->line('elsid'); ?></th>
							<th><?php echo $this->lang->line('teacher_p_full_name'); ?></th>
							<th><?php echo $this->lang->line('campus'); ?></th>
							<th><?php echo $this->lang->line('teacher_p_username'); ?></th>
							<th><?php echo $this->lang->line('teacher_p_password'); ?></th>
							<th><?php echo $this->lang->line('teacher_p_email_add'); ?></th>
							<th><?php echo $this->lang->line('teacher_p_action'); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php echo $this->lang->line('elsid'); ?></th>
							<th><?php echo $this->lang->line('teacher_p_full_name'); ?></th>
							<th><?php echo $this->lang->line('campus'); ?></th>
							<th><?php echo $this->lang->line('teacher_p_username'); ?></th>
							<th><?php echo $this->lang->line('teacher_p_password'); ?></th>
							<th><?php echo $this->lang->line('teacher_p_email_add'); ?></th>
							<th><?php echo $this->lang->line('teacher_p_action'); ?></th>
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
	fnShowHide(4);
	fnShowHide(5);
	$("#showhide_4").attr("checked", "checked");	
	$("#showhide_5").attr("checked", "checked");	
	<?php 
	if($this->session->userdata('role_id') != '1' && !in_array("edit",$this->arrAction))
	{
	?>
		fnShowHide(6);
		$('#grid_student').dataTable().makeEditable();
	<?php 
	}
	?>
});
</script>  