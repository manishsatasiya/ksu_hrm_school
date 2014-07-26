<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_student/add/'.$user_id, array('id' => 'add_student_form_datatable','name'=>'formmain')) ."\r\n";
print form_hidden('gender', $value = 'M');
print form_hidden('user_id', $value = ($rowdata)?$rowdata->user_id:0);
?>
<div class="modal-header">
  <h2><?php if(!$user_id){ echo $this->lang->line('student_p_add_heading'); }else{ echo $this->lang->line('student_p_edit_heading'); } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<?php //if($this->session->userdata('role_id') == '1'){ ?>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label"><?php print form_label($this->lang->line('student_p_section'), 'reg_section_id'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('section_id',$section_list,($rowdata)?$rowdata->section_id."j":$this->session->flashdata('section_id'),'id="reg_section_id" class="qtip_section_id"'); ?></div>
			</div>
		</div>		
		<div class="col-md-6">
            <div class="form-group">
            <div class="form_label"><?php print form_label($this->lang->line('campus'), 'reg_campus_id'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('campus_id',$campus_list,($rowdata)?$rowdata->campus_id."j":$this->session->flashdata('campus_id'),'id="reg_campus_id" class="qtip_campus_id"'); ?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<?php //}else if($this->session->userdata('role_id') == '3'){
		print form_hidden('teacher_id',$this->session->userdata('user_id'));
	//} 
	?>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('student_p_uni_id'), 'reg_student_uni_id'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'student_uni_id', 'id' => 'reg_student_uni_id', 'value' => ($rowdata)?$rowdata->student_uni_id:$this->session->flashdata('student_uni_id'), 'class' => 'form-control qtip_student_uni_id')); ?></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('student_p_full_name'), 'reg_first_name'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'first_name', 'id' => 'reg_first_name', 'value' => ($rowdata)?ucwords(strtolower($rowdata->first_name)):$this->session->flashdata('first_name'), 'class' => 'form-control qtip_first_name')); ?></div>
			</div>
		</div>	
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('student_p_full_name').' (Arabic)', 'reg_first_name'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'first_name_arabic','dir'=>'rtl', 'id' => 'reg_first_name_arabic', 'value' => ($rowdata)?$rowdata->first_name_arabic:$this->session->flashdata('first_name_arabic'), 'class' => 'form-control qtip_first_name_arabic')); ?></div>
			</div>
		</div>	
		<div class="col-md-6">
			<div class="form-group">
            <div class="form_label2"><?php print form_label($this->lang->line('sch_date'), 'reg_student_schedule_date'); ?></div>
			<div class="input-append success date col-md-10 no-padding">
				<?php print form_input(array('name' => 'student_schedule_date', 'id' => 'reg_student_schedule_date', 'value' => ($rowdata)?date('m/d/Y',strtotime($rowdata->student_schedule_date)):$this->session->flashdata('student_schedule_date'), 'class' => 'form-control qtip_student_schedule_date')); ?>
				<span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
			</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('student_p_status'), 'reg_active'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('discontinue',$discontinue,($rowdata)?$rowdata->academic_status:$this->session->flashdata('discontinue'),'id="reg_active" class="qtip_active"'); ?></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('att_rep_p_track'), 'reg_track'); ?></div>
			<div class="input_box_thin"><?php print ($rowdata) ? $rowdata->section_track : ''; ?></div>
			</div>
		</div>	
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print $this->lang->line('student_p_building'); ?></div>
			<div class="input_box_thin"><?php print ($rowdata) ? $rowdata->section_buildings : ''; ?></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('course_class_p_shift'), 'reg_shift'); ?></div>
			<div class="input_box_thin"><?php print ($rowdata)?$rowdata->courses_shift:''; ?></div>
			</div>
		</div>	
		<div class="clear"></div>
	</div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <input type="submit" name="student_submit" id="student_submit" value="<?php if(!$user_id){ echo $this->lang->line('student_p_add_btn'); }else{ echo $this->lang->line('student_p_edit_btn'); } ?>" class="btn btn-primary"/>
  <?php 
  if($user_id > 0){?>
	<input type="button" name="data_delete" id="data_delete" value="Delete" class="btn btn-danger" onclick="javascript:deleterecord('list_student',<?php echo $user_id;?>);"/>
	<?php
	} ?>
</div>
<?php
print form_close() ."\r\n";
?>

