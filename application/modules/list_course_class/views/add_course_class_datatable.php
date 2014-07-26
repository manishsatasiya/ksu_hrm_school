<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_course_class/add/'.$id, array('id' => 'add_course_class_form_datatable','name'=>'formmain')) ."\r\n";
print form_hidden('gender', $value = 'M');
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo $this->lang->line('course_class_p_add_heading'); }else{ echo $this->lang->line('course_class_p_edit_heading');  } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">
	  <div class="col-md-6">
	  	<div class="form-group">
		<div class="form_label2"><?php print form_label($this->lang->line('campus'), 'reg_camps_id'); ?></div>
		<div class="input_box_thin"> <?php print form_dropdown('camps_id',$school_campus,($rowdata)?$rowdata->camps_id."j":$this->session->flashdata('camps_id'),'id="reg_camps_id" class=" qtip_camps_id" onchange="getCampusBasedData(this.value);"'); ?> </div>
		</div>
	  </div>
	  <div class="col-md-6">
	  	<div class="form-group">
		<div class="form_label2"><?php print form_label($this->lang->line('course_class_p_section'), 'reg_section'); ?></div>
		<div class="input_box_thin"><?php print form_dropdown('section_id',$section,($rowdata)?$rowdata->section_id."j":$this->session->flashdata('section_id'),'id="reg_section_id" class=" qtip_section_id"'); ?></div>
		</div>
	  </div>
	  <div class="clear"></div>
	</div>
	<div class="row form-row">
	  <div class="col-md-6">
	  	<div class="form-group">
		<div class="form_label2"><?php print form_label($this->lang->line('course_class_p_school_year_title'), 'reg_school_year_id'); ?></div>
		<div class="input_box_thin"><?php print form_dropdown('school_year_id',$school_year_list,($rowdata)?$rowdata->school_year_id."j":$this->session->flashdata('school_year_id'),'id="reg_school_year_id" class=" qtip_school_year_id"'); ?></div>
		</div>
	  </div>
	  <div class="col-md-6">
	  	<div class="form-group">
		<div class="form_label2"><?php print form_label($this->lang->line('course_class_p_course_name'), 'reg_course_id'); ?></div>
		<div class="input_box_thin"><?php print form_dropdown('course_id',$course,($rowdata)?$rowdata->course_id."j":$this->session->flashdata('course_id'),'id="reg_course_id" class=" qtip_course_id"'); ?></div>
		</div>
	  </div>
	  <div class="clear"></div>
	</div>
	<div class="row form-row">
	  <div class="col-md-6">
	  	<div class="form-group">
		<div class="form_label2"><?php print form_label($this->lang->line('course_class_p_primary_teacher'), 'reg_primary_teacher_id'); ?></div>
		<div class="input_box_thin"><?php print form_dropdown('primary_teacher_id',$teacher_list,($rowdata)?$rowdata->primary_teacher_id."j":$this->session->flashdata('primary_teacher_id'),'id="reg_primary_teacher_id" class=" qtip_primary_teacher_id"'); ?></div>
		</div>
	  </div>
	  <div class="col-md-6">
	  	<div class="form-group">
		<div class="form_label2"><?php print form_label($this->lang->line('course_class_p_secondary_teacher'), 'reg_secondary_teacher_id'); ?></div>
		<div class="input_box_thin"><?php print form_dropdown('secondary_teacher_id',$teacher_list,($rowdata)?$rowdata->secondary_teacher_id."j":$this->session->flashdata('secondary_teacher_id'),'id="reg_secondary_teacher_id" class=" qtip_secondary_teacher_id"'); ?></div>
		</div>
	  </div>
	  <div class="clear"></div>
	</div>
	<div class="row form-row">
	  <div class="col-md-6">
	  	<div class="form-group">
		<div class="form_label2"><?php print form_label($this->lang->line('course_class_p_class_room'), 'reg_classroom'); ?></div>
		<div class="input_box_thin"><?php print form_dropdown('class_room_id',$classroom,($rowdata)?$rowdata->class_room_id."j":$this->session->flashdata('class_room_id'),'id="reg_class_room_id" class=" qtip_class_room_id"'); ?></div>
		</div>
	  </div>
	  <div class="col-md-6">
	  	<div class="form-group">
		<div class="form_label2"><?php print form_label($this->lang->line('course_class_p_shift'), 'reg_shift'); ?></div>
		<div class="input_box_thin"><?php print form_dropdown('shift',array('AM'=>'AM','PM'=>'PM'),($rowdata)?$rowdata->shift:$this->session->flashdata('shift'),'id="reg_shift" class=" qtip_shift"'); ?></div>
		</div>
	  </div>
	  <div class="clear"></div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo $this->lang->line('course_class_p_add_btn'); }else{ echo $this->lang->line('course_class_p_edit_btn'); } ?>" class="btn btn-primary"/>
	<?php 
	if($this->session->userdata('role_id') == '1' || in_array("delete",$this->arrAction))
	{
		if($id){
		?>
		<input type="button" name="data_delete" id="data_delete" value="Delete" class="btn btn-danger" onclick="javascript:deleterecord('list_course_class',<?php echo $id;?>);"/>
		<?php
		}
	}?>
</div>
<?php	
print form_close() ."\r\n";
?>
