<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_grade_type_exam/add/'.$id, array('id' => 'add_grade_type_exam_form_datatable','name'=>'formmain')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo $this->lang->line('grade_exam_add_heading'); }else{ echo $this->lang->line('grade_exam_edit_heading'); } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_exam_p_type'), 'reg_grade_type_id'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('grade_type_id',$grade_types,($rowdata)?$rowdata->grade_type_id:$this->session->flashdata('grade_type_id'),'id="reg_grade_type_id" class="qtip_grade_type_id"'); ?></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_exam_p_name'), 'reg_grade_type'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'exam_type_name', 'id' => 'reg_exam_type_name', 'value' => ($rowdata)?$rowdata->exam_type_name:$this->session->flashdata('exam_type_name'), 'class' => 'form-control qtip_exam_type_name')); ?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_exam_p_marks'), 'reg_exam_marks'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'exam_marks', 'id' => 'reg_exam_marks', 'value' => ($rowdata)?$rowdata->exam_marks:$this->session->flashdata('exam_marks'), 'class' => 'form-control qtip_exam_marks')); ?></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_exam_p_perc'), 'reg_exam_percentage'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'exam_percentage', 'id' => 'reg_exam_percentage', 'value' => ($rowdata)?$rowdata->exam_percentage:$this->session->flashdata('exam_percentage'), 'class' => 'form-control qtip_exam_percentage')); ?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_exam_p_two_marks'), 'reg_is_two_marker'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('is_two_marker',$is_two_marker,($rowdata)?$rowdata->is_two_marker:$this->session->flashdata('is_two_marker'),'id="reg_is_two_marker" class=" qtip_is_two_marker"'); ?></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_exam_p_two_marks_avg_validation'), 'reg_two_mark_difference'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'two_mark_difference', 'id' => 'reg_two_mark_difference', 'value' => ($rowdata)?$rowdata->two_mark_difference:$this->session->flashdata('two_mark_difference'), 'class' => 'form-control qtip_two_mark_difference')); ?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_exam_p_is_show'), 'reg_is_show'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('is_show',$is_show,($rowdata)?$rowdata->is_show:$this->session->flashdata('is_show'),'id="reg_is_show" class="qtip_is_show"'); ?></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_exam_p_is_show_per'), 'reg_is_show_percentage'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('is_show_percentage',$is_show_percentage,($rowdata)?$rowdata->is_show_percentage:$this->session->flashdata('is_show_percentage'),'id="reg_is_show_percentage" class=" qtip_is_show_percentage"'); ?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>	
	<input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo $this->lang->line('grade_exam_add_btn'); }else{ echo $this->lang->line('grade_exam_edit_btn'); } ?>" class="btn btn-primary"/>
	<?php if($id){?>
		<input type="button" name="exam_delete" id="exam_delete" value="Delete" class="btn btn-danger" onclick="javascript:deleterecord('list_grade_type_exam',<?php echo $id;?>);"/>
	<?php
	}?>
</div>
<?php	
print form_close() ."\r\n";
?>