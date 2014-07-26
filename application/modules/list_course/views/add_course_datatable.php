<link rel="stylesheet" href="<?php print base_url(); ?>js/timepicker/jquery.ui.timepicker.css?v=0.3.1" type="text/css" />
<script type="text/javascript" src="<?php print base_url(); ?>js/timepicker/jquery.ui.timepicker.js?v=0.3.1"></script>
<script>
$(function() {
	$('#start_time').timepicker({
		showLeadingZero: true
		//onHourShow: OnHourShowCallbackAM
	});
	$('#end_time').timepicker({
		showLeadingZero: true
		///onHourShow: OnHourShowCallbackPM
	});
	$('#pm_start_time').timepicker({
		showLeadingZero: true
		//onHourShow: OnHourShowCallbackAM
	});
	$('#pm_end_time').timepicker({
		showLeadingZero: true
		///onHourShow: OnHourShowCallbackPM
	});
});
</script>
<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_course/add/'.$id, array('id' => 'add_course_form_datatable','name'=>'formmain')) ."\r\n";
print form_hidden('gender', $value = 'M');
$this->load->view('generic/flash_error');
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo $this->lang->line('course_p_add_heading'); }else{ echo $this->lang->line('course_p_edit_heading'); } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('campus'), 'reg_camps_id '); ?></div>
			<div class="input_box_thin">
				<?php print form_dropdown('camps_id',$school_campus,($rowdata)?$rowdata->camps_id."j":$this->session->flashdata('camps_id'),'id="reg_camps_id" class="qtip_camps_id"'); ?>
			</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('course_p_title'), 'reg_course_title '); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'course_title', 'id' => 'reg_course_title', 'value' => ($rowdata)?$rowdata->course_title:$this->session->flashdata('course_title'), 'class' => 'form-control qtip_course_title')); ?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('course_p_max_hours'), 'reg_max_hours '); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'max_hours', 'id' => 'reg_max_hours', 'value' => ($rowdata)?$rowdata->max_hours:$this->session->flashdata('max_hours'), 'class' => 'form-control qtip_max_hours')); ?></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label('Total Hrs.', 'reg_max_hours '); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'total_hours_all_weeks', 'id' => 'reg_total_hours_all_weeks', 'value' => ($rowdata)?$rowdata->total_hours_all_weeks:$this->session->flashdata('total_hours_all_weeks'), 'class' => 'form-control qtip_total_hours_all_weeks')); ?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-12">
			<div class="form_label2"><h4>AM Shift</h4></div>
		</div>	
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label"><?php print form_label('Start Time', 'start_time '); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'start_time', 'id' => 'start_time', 'class' => 'form-control', 'value'=>($rowdata)?$rowdata->start_time:$this->session->flashdata('start_time'))); ?></div>
			</div>
		</div>	
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label"><?php print form_label('End Time', 'end_time '); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'end_time', 'id' => 'end_time', 'class' => 'form-control', 'value'=>($rowdata)?$rowdata->end_time:$this->session->flashdata('end_time'))); ?></div>
			</div>
		</div>	
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-12">
			<div class="form_label2"><h4>PM Shift</h4></div>
		</div>	
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label"><?php print form_label('Start Time', 'pm_start_time '); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'pm_start_time', 'id' => 'pm_start_time', 'class' => 'form-control', 'value'=>($rowdata)?$rowdata->pm_start_time:$this->session->flashdata('pm_start_time'))); ?></div>
			</div>
		</div>	
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label"><?php print form_label('End Time', 'pm_end_time '); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'pm_end_time', 'id' => 'pm_end_time', 'class' => 'form-control', 'value'=>($rowdata)?$rowdata->pm_end_time:$this->session->flashdata('pm_end_time'))); ?></div>
			</div>
		</div>	
		<div class="clear"></div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo $this->lang->line('course_p_add_heading'); }else{ echo $this->lang->line('course_p_edit_heading'); } ?>" class="btn btn-primary"/>
	<?php if($id){?>
		<input type="button" name="data_delete" id="data_delete" value="Delete" class="btn btn-danger" onclick="javascript:deleterecord('list_course',<?php echo $id;?>);"/>
	<?php
	} ?>
</div>
<?php	
print form_close() ."\r\n";
?>