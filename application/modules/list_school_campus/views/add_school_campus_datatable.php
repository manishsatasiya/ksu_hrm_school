<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_school_campus/add/'.$id, array('id' => 'add_campus_form_datatable')) ."\r\n";
print form_hidden('gender', $value = 'M');
?>
<div class="modal-header">
	<h2><?php if(!$id){ echo $this->lang->line('school_cam_add_heading'); }else{ echo $this->lang->line('school_cam_edit_heading'); } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
<?php $this->load->view('generic/flash_error'); ?>
<div class="containerfdfdf"></div>
<div class="row form-row">
	<div class="col-md-6">
		<div class="form-group">
		<div class="form_label2"><?php print form_label($this->lang->line('school_cam_p_name'), 'reg_campus_name'); ?></div>
		<div class="input_box_thin"><?php print form_input(array('name' => 'campus_name', 'id' => 'reg_campus_name', 'value' => ($rowdata)?$rowdata->campus_name:$this->session->flashdata('campus_name'), 'class' => 'form-control qtip_campus_name')); ?></div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
		<div class="form_label2"><?php print form_label($this->lang->line('school_cam_p_location'), 'reg_campus_location'); ?></div>
		<div class="input_box_thin"><?php print form_input(array('name' => 'campus_location', 'id' => 'reg_campus_location', 'value' => ($rowdata)?$rowdata->campus_location:$this->session->flashdata('campus_location'), 'class' => 'form-control qtip_campus_location')); ?></div>
		</div>
	</div>
</div>
<div class="row form-row">
	<div class="col-md-6">
    	<div class="form_label"><?php print form_label('Presenter', 'line_manager_attendance_day'); ?></div>
		<div class="input_box_thin"><?php print form_dropdown('line_manager_attendance_day',$attendance_days_list,($rowdata)?$rowdata->line_manager_attendance_day:$this->session->flashdata('line_manager_attendance_day'),'id="line_manager_attendance_day" class=""'); ?></div>
    </div>
</div>    
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
   <input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo $this->lang->line('school_cam_add_btn'); }else{ echo $this->lang->line('school_cam_edit_btn'); } ?>" class="btn btn-primary"/>
</div>
<?php
print form_close() ."\r\n";
?>
