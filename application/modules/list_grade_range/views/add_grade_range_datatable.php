<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<div id="containers">
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_grade_range/add/'.$id, array('id' => 'add_grade_range_form_datatable')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo $this->lang->line('grade_range_add_heading'); }else{ echo $this->lang->line('grade_range_edit_heading'); } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">
		<div class="col-md-4">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_range_p_min_range'), 'reg_grade_min_range'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'grade_min_range', 'id' => 'reg_grade_min_range', 'value' => ($rowdata)?$rowdata->grade_min_range:$this->session->flashdata('grade_min_range'), 'class' => 'input_text1 qtip_grade_min_range')); ?></div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_range_p_max_range'), 'reg_grade_max_range'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'grade_max_range', 'id' => 'reg_grade_max_range', 'value' => ($rowdata)?$rowdata->grade_max_range:$this->session->flashdata('grade_max_range'), 'class' => 'input_text1 qtip_grade_max_range')); ?></div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('grade_range_p_grade'), 'reg_category_title'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'grade_name', 'id' => 'reg_grade_name', 'value' => ($rowdata)?$rowdata->grade_name:$this->session->flashdata('grade_name'), 'class' => 'input_text1 qtip_grade_name')); ?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo $this->lang->line('grade_range_add_btn'); }else{ echo $this->lang->line('grade_range_edit_btn'); } ?>" class="btn btn-primary"/>
</div>
<?php
print form_close() ."\r\n";
?>