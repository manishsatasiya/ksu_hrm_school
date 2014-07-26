<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('duties/add/'.$id, array('id' => 'add_duties_form_datatable','name'=>'formmain')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo 'Add Duties'; }else{ echo 'Edit Duties'; } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	
	<div class="row form-row">		
		<div class="col-md-12">
			<div class="form-group">
			<div class="form_label"><?php print form_label($this->lang->line('user_p_role'), 'reg_user_roll_id'); ?></div>
			<div class="controls"><?php  
				print form_dropdown('user_roll_id',$other_user_roll,($rowdata)?$rowdata->user_roll_id:'0','id="reg_name_suffix" class="qtip_name_suffix"'); ?>
			</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="row form-row">		
		<div class="col-md-12">
			<div class="form-group">
			<div class="form_label"><?php print form_label('Duties', 'reg_duties'); ?></div>
			<div class="controls"><?php  
				print form_textarea(array('name' => 'duties', 'id' => 'text-editor', 'value' => ($rowdata)?$rowdata->duties:$this->session->flashdata('duties'), 'rows'=>10,'class' => 'form-control')); ?>
			</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo 'Add Duties'; }else{ echo 'Edit Duties'; } ?>" class="btn btn-primary"/>
  <?php 
  if($id > 0){?>
	<input type="button" name="data_delete" id="data_delete" value="Delete" class="btn btn-danger" onclick="javascript:deleterecord('Duties',<?php echo $id;?>);"/>
	<?php
	} ?>
</div>
<?php
print form_close() ."\r\n";
?>