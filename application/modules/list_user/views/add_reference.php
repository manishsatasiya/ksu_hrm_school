<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_user/add_reference/'.$user_id.'/'.$id, array('id' => 'add_user_reference','name'=>'formmain')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo 'Add reference'; }else{ echo 'Edit reference'; } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Company', 'company_name'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'company_name', 'id' => 'company_name', 'value' => ($rowdata)?$rowdata->company_name:$this->session->flashdata('company_name'), 'class' => 'form-control')); ?></div>
		</div>
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Name', 'name'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'name', 'id' => 'name', 'value' => ($rowdata)?$rowdata->name:$this->session->flashdata('name'), 'class' => 'form-control')); ?></div>
		</div>	
		<div class="clear"></div>
	</div>
	
    <div class="row form-row">
		<div class="col-md-12">
			<div class="form_label2"><?php print form_label('Email', 'email'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'email', 'id' => 'email', 'value' => ($rowdata)?$rowdata->email:$this->session->flashdata('email'), 'class' => 'form-control')); ?></div>
		</div>
    </div>    	
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo 'Add'; }else{ echo 'Edit'; } ?>" class="btn btn-primary"/>
</div>	
<?php	
print form_close() ."\r\n";
?>