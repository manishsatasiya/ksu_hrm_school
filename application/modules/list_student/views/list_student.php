<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$arrAttendanceAction = get_priviledge_action("attendance");
$arrGradeReportAction = get_priviledge_action("grade_report");
?>
<script>
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
</script>
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
<script type="text/javascript" src="<?php print base_url(); ?>js/grid/list_student.js?t=newjsct"></script>

<?php 
if($this->session->userdata('role_id') == '1' || in_array("add",$this->arrAction))
{
?>

<div id="add_model_link">
	<a href="list_student/add" class="btn btn-success" data-target="#myModal" data-toggle="modal"><?php echo $this->lang->line('add_new'); ?> <i class="fa fa-plus"></i></a>
    </div>

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
			  <h4><?php echo $this->lang->line('student_p_heading'); ?></h4>
                <div class="export_to_excel">
                    <form id="export_file" action="list_student/export_to_excel" target="download_iframe" method="POST">
                        <div class="col-md-8">
                        <input type="hidden" name="search_section" id="search_section" value="">
                        <input type="hidden" name="search_gradetype" id="search_gradetype" value="">
                            <select name="campus" class="">
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
			<div id="processing_message" class="hide" title="Processing">Please wait while your request is being processed...</div>
			<table class="table" id="grid_student">
				<thead>
					<tr>
						<th style="width:30px"></th>
						<th style="width:80px"><?php print $this->lang->line('student_p_stud_id'); ?></th>
						<th><?php print $this->lang->line('student_p_full_name'); ?></th>
						<th><?php echo $this->lang->line('sch_date'); ?></th>	
						<th style="width:77px"><?php print $this->lang->line('student_p_section'); ?></th>
						<th style="width:77px"><?php echo $this->lang->line('campus'); ?></th>
						<th style="width:77px"><?php print $this->lang->line('sub_att_p_track'); ?></th>
						<th style="width:77px"><?php print $this->lang->line('student_p_building'); ?></th>
						<th style="width:77px"><?php print $this->lang->line('student_p_status'); ?></th>
						<th><?php print $this->lang->line('student_p_action'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th style="width:30px"></th>
						<th><?php print $this->lang->line('student_p_stud_id'); ?></th>
						<th><?php print $this->lang->line('student_p_full_name'); ?></th>
						<th><?php echo $this->lang->line('sch_date'); ?></th>	
						<th><?php print $this->lang->line('student_p_section'); ?></th>
						<th><?php echo $this->lang->line('campus'); ?></th>
						<th><?php print $this->lang->line('sub_att_p_track'); ?></th>
						<th><?php print $this->lang->line('student_p_building'); ?></th>
						<th><?php print $this->lang->line('student_p_status'); ?></th>
						<th><?php print $this->lang->line('student_p_action'); ?></th>
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
	fnShowHide(2);
	fnShowHide(4);
	fnShowHide(5);
	fnShowHide(6);
	<?php 
	if($this->session->userdata('role_id') != '1' && !in_array("edit",$this->arrAction))
	{
	?>
		fnShowHide(7);
	<?php 
	}
	?>
});
</script>  	  
