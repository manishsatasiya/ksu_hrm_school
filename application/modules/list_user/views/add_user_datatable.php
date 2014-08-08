<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_user/add/'.$id, array('id' => 'add_user_form_datatable','name'=>'formmain')) ."\r\n";
print form_hidden('gender', $value = 'M');
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo $this->lang->line('user_p_add_user'); }else{ echo $this->lang->line('user_p_edit_user'); } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label($this->lang->line('user_p_role'), 'reg_user_roll_id'); ?></div>
			<div class="input_box_thin"><?php  
				print form_dropdown('user_roll_id',$other_user_roll,($rowdata)?$rowdata->user_roll_id:'0','id="reg_name_suffix" class="qtip_name_suffix"'); 
			?></div>
		</div>
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label($this->lang->line('campus'), 'reg_campus_id'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('campus_id',$campus_list,($rowdata)?$rowdata->campus_id."j":$this->session->flashdata('campus_id'),'id="reg_campus_id" class="qtip_campus_id"'); ?></div>
		</div>	
		<div class="clear"></div>
	</div>
	
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label($this->lang->line('user_p_full_name'), 'reg_first_name'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'first_name', 'id' => 'reg_first_name', 'value' => ($rowdata)?$rowdata->first_name:$this->session->flashdata('first_name'), 'class' => 'form-control qtip_first_name')); ?></div>
		</div>
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label($this->lang->line('user_p_email_address'), 'reg_email'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'email', 'id' => 'reg_email', 'value' => ($rowdata)?$rowdata->email:$this->session->flashdata('email'), 'class' => 'form-control qtip_email')); ?></div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-4">
			<div class="form_label2"><?php print form_label($this->lang->line('user_p_birth_date'), 'birth_date'); ?></div>
			<div class="input-append success date col-md-10 no-padding">	
				<?php print form_input(array('name' => 'birth_date', 'id' => 'show_dp', 'value' => ($rowdata)?make_dp_date($rowdata->birth_date):$this->session->flashdata('birth_date'), 'class' => 'form-control')); ?>
				<span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
			</div>
		</div>	
		<div class="col-md-4">
			<div class="form_label2"><?php print form_label($this->lang->line('user_p_birth_place'), 'reg_birth_place'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'birth_place', 'id' => 'reg_birth_place', 'value' => ($rowdata)?$rowdata->birth_place:$this->session->flashdata('birth_place'), 'class' => 'form-control qtip_birth_place')); ?></div>
		</div>	
		<div class="col-md-4">
			<div class="form_label2"><?php print form_label($this->lang->line('user_p_language_known'), 'reg_language_known'); ?></div>
			<div class="input_box_thin">
				<?php print form_input(array('name' => 'language_known', 'id' => 'reg_language_known', 'value' => ($rowdata)?$rowdata->language_known:$this->session->flashdata('language_known'), 'class' => 'form-control qtip_language_known')); ?>
			</div>
		</div>	
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-4">
			<div class="form_label2"><?php print form_label($this->lang->line('user_p_work_phone'), 'reg_work_phone'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'work_phone', 'id' => 'reg_work_phone', 'value' => ($rowdata)?$rowdata->work_phone:$this->session->flashdata('work_phone'), 'class' => 'form-control qtip_work_phone')); ?></div>
		</div>	
		<div class="col-md-4">
			<div class="form_label2"><?php print form_label($this->lang->line('user_p_home_phone'), 'reg_home_phone'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'home_phone', 'id' => 'reg_home_phone', 'value' => ($rowdata)?$rowdata->home_phone:$this->session->flashdata('home_phone'), 'class' => 'form-control qtip_home_phone')); ?></div>
		</div>	
		<div class="col-md-4">
			<div class="form_label2"><?php print form_label($this->lang->line('user_p_cell_phone'), 'reg_cell_phone'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'cell_phone', 'id' => 'reg_cell_phone', 'value' => ($rowdata)?$rowdata->cell_phone:$this->session->flashdata('cell_phone'), 'class' => 'form-control qtip_cell_phone')); ?></div>
		</div>	
		<div class="clear"></div>
	</div>
	<?php if(!$id){ ?>
	<div class="row form-row">
		<div class="col-md-4">
			<div class="form_label2"><?php print form_label($this->lang->line('user_p_username'), 'reg_username'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'username', 'id' => 'reg_username', 'value' => ($rowdata)?$rowdata->username:$this->session->flashdata('username'), 'class' => 'form-control qtip_username')); ?></div>
			<div id="username_valid"></div>
			<div id="username_taken"></div>
			<div id="username_length"></div>
		</div>
		<div class="col-md-4">
			<div class="form_label2"><?php print form_label($this->lang->line('user_p_password'), 'reg_password'); ?></div>
			<div class="input_box_thin"><?php print form_password(array('name' => 'password', 'id' => 'reg_password', 'class' => 'form-control qtip_password')); ?></div>
		</div>
		<div class="col-md-4">
			<div class="form_label2"><?php print form_label($this->lang->line('user_p_confirm_password'), 'password_confirm'); ?></div>
			<div class="input_box_thin"><?php print form_password(array('name' => 'password_confirm', 'id' => 'password_confirm', 'class' => 'form-control')); ?></div>
		</div>	
		<div class="clear"></div>
	</div>
	<?php } ?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo 'Add User'; }else{ echo 'Edit User'; } ?>" class="btn btn-primary"/>
	<?php if($id){?>
		<input type="button" name="data_delete" id="data_delete" value="Delete" class="btn btn-danger" onclick="javascript:deleterecord('list_user',<?php echo $id;?>);"/>
	<?php
	} ?>
</div>	
<?php	
print form_close() ."\r\n";
?>