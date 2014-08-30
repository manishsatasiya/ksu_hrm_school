<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_teacher/add/'.$id, array('id' => 'add_teacher_form_datatable','name'=>'formmain')) ."\r\n";
print form_hidden('gender', $value = 'M');
print form_hidden('user_id', $value = ($rowdata)?$rowdata->user_id:0);
$this->load->view('generic/flash_error');
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo $this->lang->line('teacher_p_add_heading'); }else{ echo $this->lang->line('teacher_p_edit_heading'); } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
    <div class="row form-row">		
        <div class="col-md-4"> <?php print form_label('First Names', 'names',array('class'=>'form-label')); ?> 
        <?php print form_input(array('name' => 'first_name', 'id' => 'first_name', 'value' => ($rowdata)?$rowdata->first_name:$this->session->flashdata('first_name'), 'class' => 'form-control ','placeholder' => 'First Name')); ?> </div>
        <div class="col-md-4"> <?php print form_label('Middle Name', 'names',array('class'=>'form-label')); ?>
        <?php print form_input(array('name' => 'middle_name', 'id' => 'middle_name', 'value' => ($rowdata)?$rowdata->middle_name:$this->session->flashdata('middle_name'), 'class' => 'form-control ','placeholder' => 'Middle Name')); ?> </div>
        <div class="col-md-4"> <?php print form_label('Last Name', 'names',array('class'=>'form-label')); ?>
        <?php print form_input(array('name' => 'last_name', 'id' => 'last_name', 'value' => ($rowdata)?$rowdata->last_name:$this->session->flashdata('last_name'), 'class' => 'form-control ','placeholder' => 'Last Name')); ?> </div>
    </div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label('Campus Location', 'reg_campus_id'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('campus_id',$campus_list,($rowdata)?$rowdata->campus_id."j":$this->session->flashdata('campus_id'),'id="reg_campus_id" class="qtip_campus_id"'); ?></div>
			</div>
		</div>
        <div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label('Campus Privilages', 'campus_privilages_id',array('class'=>'form-label')); ?> </div>
			<div class="input_box_thin"><?php print form_multiselect('campus_privilages[]',$campus_list,$campus_privilages,'id="multi" class="select2 form-control" placeholder="Select Campus"');  ?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
    <div class="row form-row">
    	<div class="col-md-6"> <?php print form_label('Line Manager', 'coordinator',array('class'=>'form-label')); ?> <?php print form_dropdown('coordinator',$line_manager_list,($rowdata)?$rowdata->coordinator:$this->session->flashdata('coordinator'),'id="coordinator" class="select2 form-control"'); ?> </div>
        <div class="col-md-6"> <?php print form_label('Office / Room #', 'office_no',array('class'=>'form-label')); ?> <?php print form_input(array('name' => 'office_no', 'id' => 'office_no', 'value' => ($rowdata)?$rowdata->office_no:$this->session->flashdata('office_no'), 'class' => 'form-control','placeholder' => 'Office no')); ?> </div>
    </div>    
	<div class="row form-row">
		<div class="col-md-12">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('teacher_p_email_add'), 'reg_email'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'email', 'id' => 'reg_email', 'value' => ($rowdata)?$rowdata->email:$this->session->flashdata('email'), 'class' => 'form-control qtip_email')); ?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<?php if(!$id){ ?>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('teacher_p_username'), 'reg_username'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'username', 'id' => 'reg_username', 'value' => ($rowdata)?$rowdata->username:$this->session->flashdata('username'), 'class' => 'form-control qtip_username')); ?></div>
			<div id="username_valid"></div>
			<div id="username_taken"></div>
			<div id="username_length"></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('teacher_p_password'), 'reg_password'); ?></div>
			<div class="input_box_thin"><?php print form_password(array('name' => 'password', 'id' => 'reg_password', 'class' => 'form-control qtip_password')); ?></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('teacher_p_confirmpassword'), 'password_confirm'); ?></div>
			<div class="input_box_thin"><?php print form_password(array('name' => 'password_confirm', 'id' => 'password_confirm', 'class' => 'form-control')); ?></div>
			</div>
		</div>	
		<div class="clear"></div>
	</div>
	<?php } ?>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo $this->lang->line('teacher_p_add_btn'); }else{ echo $this->lang->line('teacher_p_edit_btn'); } ?>" class="btn btn-primary"/>
	<?php if(isset($rowdata) && isset($rowdata->user_id)){?>
		<input type="button" name="data_delete" id="data_delete" value="Delete" class="btn btn-danger" onclick="javascript:deleterecord('list_teacher',<?php echo $rowdata->user_id;?>);"/>
	<?php
	}
print form_close() ."\r\n";
?>