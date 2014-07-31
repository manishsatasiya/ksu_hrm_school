<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_user/add_emergency_contact/'.$user_id.'/'.$id, array('id' => 'add_emergency_contact','name'=>'formmain')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo 'Add contact'; }else{ echo 'Edit contact'; } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Name', 'name'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'name', 'id' => 'name', 'value' => ($rowdata)?$rowdata->name:$this->session->flashdata('name'), 'class' => 'form-control')); ?></div>
		</div>
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Relationship', 'relation'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'relation', 'id' => 'relation', 'value' => ($rowdata)?$rowdata->relation:$this->session->flashdata('relation'), 'class' => 'form-control')); ?></div>
		</div>	
		<div class="clear"></div>
	</div>
	
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Contact Number', 'contact_number'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'contact_number', 'id' => 'contact_number', 'value' => ($rowdata)?$rowdata->contact_number:$this->session->flashdata('contact_number'), 'class' => 'form-control')); ?></div>
		</div>
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Alternate Contact Method', 'alternate_contact'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'alternate_contact', 'id' => 'alternate_contact', 'value' => ($rowdata)?$rowdata->alternate_contact:$this->session->flashdata('alternate_contact'), 'class' => 'form-control')); ?></div>
		</div>
		<div class="clear"></div>
	</div>
    
    <div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Country', 'country'); ?></div>
			<div class="input_box_thin"><?php  
				print form_dropdown('country',$countries_list,($rowdata)?$rowdata->country:'0','id="country" class="formselect"'); 
			?></div>
		</div>
		<div class="clear"></div>
	</div>
		
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo 'Add'; }else{ echo 'Edit'; } ?>" class="btn btn-primary"/>
</div>	
<?php	
print form_close() ."\r\n";
?>