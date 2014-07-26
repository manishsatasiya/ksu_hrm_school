<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_school_year/add/'.$id, array('id' => 'add_school_year_form_datatable')) ."\r\n";
print form_hidden('gender', $value = 'M');
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo $this->lang->line('school_y_p_add_heading'); }else{ echo $this->lang->line('school_y_p_edit_heading'); } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
<?php $this->load->view('generic/flash_error'); ?>
<div class="containerfdfdf"></div>
	<div class="row form-row">
		<div class="col-md-4">
			<div class="form_label2"><?php print form_label($this->lang->line('school_y_p_field_school_name'), 'reg_school_id'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('school_id',$school_list,($rowdata)?$rowdata->school_id:$this->session->flashdata('school_id'),'id="reg_school_id" class="qtip_school_id"'); ?></div>
		</div>
		<div class="col-md-4">
			<div class="form_label2"><?php print form_label($this->lang->line('school_y_p_field_year'), 'reg_school_year'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'school_year', 'id' => 'reg_school_year', 'value' => ($rowdata)?$rowdata->school_year:$this->session->flashdata('school_year'), 'class' => 'form-control qtip_school_year')); ?></div>
		</div>
		<div class="col-md-4">
			<div class="form_label2"><?php print form_label($this->lang->line('school_y_p_field_title'), 'reg_school_year_title'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'school_year_title', 'id' => 'reg_school_year_title', 'value' => ($rowdata)?$rowdata->school_year_title:$this->session->flashdata('school_year_title'), 'class' => 'form-control qtip_school_year_title')); ?></div>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label($this->lang->line('school_y_p_field_type'), 'reg_school_type'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('school_type', $school_type, ($rowdata)?$rowdata->school_type:$this->session->flashdata('school_type'),'id="reg_school_type" class="form-control qtip_school_type"'); ?></div>
		</div>
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label($this->lang->line('school_y_p_field_week'), 'reg_school_week'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'school_week', 'id' => 'reg_school_week', 'value' => ($rowdata)?$rowdata->school_week:$this->session->flashdata('school_week'), 'class' => 'form-control qtip_school_week')); ?></div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo $this->lang->line('school_y_p_add_btn'); }else{ echo $this->lang->line('school_y_p_edit_btn'); } ?>" class="btn btn-primary"/>
</div>
<?php
print form_close() ."\r\n";
?>