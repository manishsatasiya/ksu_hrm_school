<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_grade_type/add/'.$id, array('id' => 'add_grade_type_form_datatable')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo $this->lang->line('grade_type_add_heading'); }else{ echo $this->lang->line('grade_type_edit_heading'); } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_type_p_type'), 'reg_grade_type'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'grade_type', 'id' => 'reg_grade_type', 'value' => ($rowdata)?$rowdata->grade_type:$this->session->flashdata('grade_type'), 'class' => 'form-control qtip_grade_type')); ?></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_type_p_marks'), 'reg_total_markes'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'total_markes', 'id' => 'reg_total_markes', 'value' => ($rowdata)?$rowdata->total_markes:$this->session->flashdata('total_markes'), 'class' => 'form-control qtip_total_markes')); ?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_type_p_perc'), 'reg_total_percentage'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'total_percentage', 'id' => 'reg_total_percentage', 'value' => ($rowdata)?$rowdata->total_percentage:$this->session->flashdata('total_percentage'), 'class' => 'form-control qtip_total_percentage')); ?></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_type_p_att_type'), 'reg_attendance_type'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('attendance_type',$attendance_type,($rowdata)?$rowdata->attendance_type:$this->session->flashdata('attendance_type'),'id="reg_attendance_type" class=" qtip_attendance_type"'); ?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_type_p_verification_type'), 'reg_verification_type'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('verification_type',$verification_type,($rowdata)?$rowdata->verification_type:$this->session->flashdata('verification_type'),'id="reg_verification_type" class=" qtip_verification_type"'); ?></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_type_p_is_show_verification'), 'reg_is_show_verified'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('is_show_verified',$is_show_verified,($rowdata)?$rowdata->is_show_verified:$this->session->flashdata('is_show_verified'),'id="reg_is_show_verified" class=" qtip_is_show_verified"'); ?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_type_p_show_grade_type_total_marks'), 'reg_show_total_marks'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('show_total_marks',$show_total_marks,($rowdata)?$rowdata->show_total_marks:$this->session->flashdata('show_total_marks'),'id="reg_show_total_marks" class=" qtip_show_total_marks"'); ?></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_type_p_show_grade_type_total'), 'reg_show_total_per'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('show_total_per',$show_total_per,($rowdata)?$rowdata->show_total_per:$this->session->flashdata('show_total_per'),'id="reg_show_total_per" class=" qtip_show_total_per"'); ?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_type_p_is_show'), 'reg_is_show_grade'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('is_show_grade',$is_show_grade,($rowdata)?$rowdata->is_show_grade:$this->session->flashdata('is_show_grade'),'id="reg_is_show_grade" class=" qtip_is_show_grade"'); ?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo $this->lang->line('grade_type_add_btn'); }else{ echo $this->lang->line('grade_type_edit_btn'); } ?>" class="btn btn-primary"/>
</div>
<?php
print form_close() ."\r\n";
?>