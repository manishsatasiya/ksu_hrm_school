<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_role/add/'.$id, array('id' => 'add_role_form_datatable','name'=>'formmain')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo $this->lang->line('role_p_add_role'); }else{ echo $this->lang->line('role_p_edit_role'); } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>

	<div class="containerfdfdf"></div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label($this->lang->line('role_p_role'), 'reg_user_roll_name'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'user_roll_name', 'id' => 'reg_user_roll_name', 'value' => ($rowdata)?$rowdata->user_roll_name:$this->session->flashdata('user_roll_name'), 'class' => 'form-control qtip_user_roll_name')); ?></div>
		</div>
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Is CA Lead?', 'reg_is_ca_lead'); ?></div>
			<?php $checked = (isset($rowdata->is_ca_lead) && $rowdata->is_ca_lead == 'Y') ? true:false; ?>
			<div class="input_box_thin"><?php print form_checkbox(array('name' => 'is_ca_lead', 'id' => 'is_ca_lead', 'value' => 'Y', 'checked' => $checked)); ?></div>
		</div>
		<div class="clear"></div>
	</div>
    <div class="row form-row">
    	<div class="col-md-6">
			<div class="form-group">
			<div class="form_label"><?php print form_label('Dashboard page', 'dashboard_page'); ?></div>
			<div class="controls"><?php  
				print form_dropdown('dashboard_page',list_dashboard_page(),($rowdata)?$rowdata->dashboard_page:'','id="dashboard_page" class=""'); ?>
			</div>
			</div>
		</div>
      </div>   
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo 'Add Role'; }else{ echo 'Edit Role'; } ?>" class="btn btn-primary"/>
	<?php if($id){?>
		<input type="button" name="data_delete" id="data_delete" value="Delete" class="btn btn-danger" onclick="javascript:deleterecord('list_role',<?php echo $id;?>);"/>
	<?php
	} ?>
</div>
<?php	
print form_close() ."\r\n";
?>