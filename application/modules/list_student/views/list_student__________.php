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
<script type="text/javascript" language="javascript" src="<?php print base_url(); ?>js/popup.js"></script>

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

<div class="admin-bar" id="quick-access" style="display:">
      <div class="admin-bar-inner">
        <div class="form-horizontal">
        
        <!-- formrmr -->
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<?php //if($this->session->userdata('role_id') == '1'){ ?>
	<div class="row form-row">
		<div class="col-md-3">
			<div class="form_label"><?php print form_label($this->lang->line('student_p_section'), 'reg_section_id'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('section_id',$section_list,$this->session->flashdata('section_id'),'id="reg_section_id" class="qtip_section_id"'); ?></div>
		</div>		
		<div class="col-md-3">
            <div class="form_label"><?php print form_label($this->lang->line('campus'), 'reg_campus_id'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('campus_id',$campus_list,$this->session->flashdata('campus_id'),'id="reg_campus_id" class="qtip_campus_id"'); ?></div>
		</div>
        <div class="col-md-3">
			<div class="form_label2"><?php print form_label($this->lang->line('student_p_uni_id'), 'reg_student_uni_id'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'student_uni_id', 'id' => 'reg_student_uni_id', 'value' => $this->session->flashdata('student_uni_id'), 'class' => 'form-control qtip_student_uni_id')); ?></div>
		</div>
        <div class="col-md-3">
			<div class="form_label2"><?php print form_label($this->lang->line('student_p_full_name'), 'reg_first_name'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'first_name', 'id' => 'reg_first_name', 'value' => $this->session->flashdata('first_name'), 'class' => 'form-control qtip_first_name')); ?></div>
		</div>
		<div class="clear"></div>
	</div>
	<?php //}else if($this->session->userdata('role_id') == '3'){
		print form_hidden('teacher_id',$this->session->userdata('user_id'));
	//} 
	?>
	<div class="row form-row">
        <div class="col-md-4">
			<div class="form_label2"><?php print form_label($this->lang->line('student_p_full_name').' (Arabic)', 'reg_first_name'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'first_name_arabic','dir'=>'rtl', 'id' => 'reg_first_name_arabic', 'value' => $this->session->flashdata('first_name_arabic'), 'class' => 'form-control qtip_first_name_arabic')); ?></div>
		</div>
        <div class="col-md-4">
            <div class="form_label2"><?php print form_label($this->lang->line('sch_date'), 'reg_student_schedule_date'); ?></div>
			<div class="input-append success date col-md-10 no-padding">
				<?php print form_input(array('name' => 'student_schedule_date', 'id' => 'reg_student_schedule_date', 'value' =>$this->session->flashdata('student_schedule_date'), 'class' => 'form-control qtip_student_schedule_date')); ?>
				<span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
			</div>
		</div>
        <div class="col-md-4">
			<div class="form_label2"><?php print form_label($this->lang->line('student_p_status'), 'reg_active'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('discontinue',$discontinue,$this->session->flashdata('discontinue'),'id="reg_active" class="qtip_active"'); ?></div>
		</div>	
		<div class="clear"></div>
	</div>
        <!--hkkhkh-->
        
        </div>
        <button class="btn btn-primary btn-cons btn-add" type="button">Add Student</button>
        <button class="btn btn-white btn-cons btn-cancel" type="button">Cancel</button>
      </div>
    </div>
<script language="javascript">
$(document).ready(function() {
	<?php 
	if($this->session->userdata('role_id') != '1' && !in_array("edit",$this->arrAction))
	{
	?>
		fnShowHide(6);
	<?php 
	}
	?>
});
</script>  	  
