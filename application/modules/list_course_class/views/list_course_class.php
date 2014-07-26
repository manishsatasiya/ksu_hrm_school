<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$arrAttendanceAction = get_priviledge_action("attendance");
$arrGradeReportAction = get_priviledge_action("grade_report");
?>
<script type="text/javascript">
var showattandancelink = 0;
var showgradereportlink = 0;
<?php
if($this->session->userdata('role_id') == '1' || (is_array($arrAttendanceAction) && count($arrAttendanceAction) > 0 && in_array("index",$arrAttendanceAction)))
{
?>
	showattandancelink = 1;
<?php
}
if($this->session->userdata('role_id') == '1' || (is_array($arrGradeReportAction) && count($arrGradeReportAction) > 0 && in_array("index",$arrGradeReportAction)))
{
?>
	showgradereportlink = 1;
<?php
}
?>
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
<script type="text/javascript" src="<?php print base_url(); ?>js/grid/list_course_class.js?t=newtht"></script>
	
<?php 
if($this->session->userdata('role_id') == '1' || in_array("add",$this->arrAction))
{
?>
	<div id="add_model_link" class="hide"><a href="list_course_class/add" class="btn btn-success" data-target="#myModal" data-toggle="modal"><?php echo $this->lang->line('add_new'); ?> <i class="fa fa-plus"></i></a></div>
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
			  <h4><?php echo $this->lang->line('course_class_p_heading'); ?></h4>
              <div class="export_to_excel">
              	<form id="export_file" action="list_course_class/export_to_excel" target="download_iframe" method="POST">
                    <div class="col-md-10">
                    <div class="col-md-8">
                    <input type="hidden" name="search_section" id="search_section" value="">
                    <input type="hidden" name="search_gradetype" id="search_gradetype" value="">
                    </div>
                    <div class="col-md-4">
                        <input type="submit" name="submit" value="Export To XLS" class="btn btn-info">
                    </div>
                    </div>
                </form>	
              </div>
			</div>
			<div class="grid-body ">    
			<div id="processing_message" style="display:none" title="Processing">Please wait while your request is being processed...</div>
			<table class="table" id="grid_course_class">
				<thead>
					<tr>
                    	<th style="width:30px"></th>
						<th width="5px"><?php echo $this->lang->line('course_class_p_id'); ?></th>
						<th><?php echo $this->lang->line('course_class_p_course_name'); ?></th>
						<th><?php echo $this->lang->line('course_class_p_primary_teacher'); ?></th>
						<th><?php echo $this->lang->line('course_class_p_sec_teacher'); ?></th>
						<th><?php echo $this->lang->line('course_class_p_class_room'); ?></th>
						<th><?php echo $this->lang->line('course_class_p_section'); ?></th>
						<th><?php echo $this->lang->line('course_class_p_shift'); ?></th>
						<th><?php echo $this->lang->line('campus'); ?></th>
						<th>Track</th>
						<th>Building</th>
						<th><?php echo $this->lang->line('course_class_p_total_student'); ?></th>
						<th><?php echo $this->lang->line('course_class_p_action'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
                    	<th style="width:30px"></th>
						<th><?php echo $this->lang->line('course_class_p_id'); ?></th>
						<th><?php echo $this->lang->line('course_class_p_course_name'); ?></th>
						<th><?php echo $this->lang->line('course_class_p_primary_teacher'); ?></th>
						<th><?php echo $this->lang->line('course_class_p_sec_teacher'); ?></th>
						<th><?php echo $this->lang->line('course_class_p_class_room'); ?></th>
						<th><?php echo $this->lang->line('course_class_p_section'); ?></th>
						<th><?php echo $this->lang->line('course_class_p_shift'); ?></th>
						<th><?php echo $this->lang->line('campus'); ?></th>
						<th>Track</th>
						<th>Building</th>
						<th><?php echo $this->lang->line('course_class_p_total_student'); ?></th>
						<th><?php echo $this->lang->line('course_class_p_action'); ?></th>
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
		fnShowHide(9);
	<?php 
	}
	?>
});
</script> 