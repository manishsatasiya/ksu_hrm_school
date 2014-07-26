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
<script type="text/javascript" src="<?php print base_url(); ?>js/grid/list_grade_type_exam.js"></script><div>
<?php 
if($this->session->userdata('role_id') == '1' || in_array("add",$this->arrAction))
{
?>
	<div id="add_model_link" class="hide"><a href="list_grade_type_exam/add" class="btn btn-success" data-target="#myModal" data-toggle="modal"><?php echo $this->lang->line('add_new'); ?> <i class="fa fa-plus"></i></a></div>
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
			  <h4><?php echo $this->lang->line('grade_exam_p_heading'); ?></h4>
			</div>
			<div class="grid-body ">		
        	<div id="processing_message" style="display:none" title="Processing">Please wait while your request is being processed...</div>		
			<table class="table" id="grid_grade_type_exam">
			<thead>
				<tr>
					<th><?php echo $this->lang->line('grade_exam_p_id'); ?></th>
					<th><?php echo $this->lang->line('grade_exam_p_type'); ?></th>
					<th><?php echo $this->lang->line('grade_exam_p_name'); ?></th>
					<th><?php echo $this->lang->line('grade_exam_p_marks'); ?></th>
					<th><?php echo $this->lang->line('grade_exam_p_perc'); ?></th>
					<th><?php echo $this->lang->line('grade_exam_p_action'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th><?php echo $this->lang->line('grade_exam_p_id'); ?></th>
					<th><?php echo $this->lang->line('grade_exam_p_type'); ?></th>
					<th><?php echo $this->lang->line('grade_exam_p_name'); ?></th>
					<th><?php echo $this->lang->line('grade_exam_p_marks'); ?></th>
					<th><?php echo $this->lang->line('grade_exam_p_perc'); ?></th>
					<th><?php echo $this->lang->line('grade_exam_p_action'); ?></th>
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
		fnShowHide(5);
	<?php 
	}
	?>
});
</script>  