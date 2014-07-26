<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('contracted_numbers/add/'.$id, array('id' => 'add_contracted_numbers_form_datatable','name'=>'formmain')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo 'Add Contracted numbers'; }else{ echo 'Edit Contracted numbers'; } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	
	<div class="row form-row">		
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label"><?php print form_label('Contractor', 'contractor_id'); ?></div>
			<div class="controls"><?php  
				print form_dropdown('contractor_id',$contractors,($rowdata)?$rowdata->user_roll_id:'0','id="contractor_id" class=""'); ?>
			</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label"><?php print form_label($this->lang->line('campus'), 'campus_id'); ?></div>
			<div class="controls"><?php  
				print form_dropdown('campus_id',$campus,($rowdata)?$rowdata->campus_id:'0','id="campus_id" class=""'); ?>
			</div>
			</div>
		</div>
	</div>
	
	<div class="row form-row">		
    	<div class="col-md-6">
			<div class="form-group">
			<div class="form_label"><?php print form_label($this->lang->line('user_p_role'), 'user_roll_id'); ?></div>
			<div class="controls"><?php  
				print form_dropdown('user_roll_id',$other_user_roll,($rowdata)?$rowdata->user_roll_id:'0','id="user_roll_id" class=""'); ?>
			</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label"><?php print form_label('Contracted numbers', 'contracted_numbers'); ?></div>
			<div class="controls"><?php  
				print form_input(array('name' => 'contracted_numbers', 'id' => 'contracted_numbers', 'value' => ($rowdata)?$rowdata->contracted_numbers:$this->session->flashdata('contracted_numbers'),'class' => 'form-control')); ?>
			</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo 'Add Contracted numbers'; }else{ echo 'Edit Contracted numbers'; } ?>" class="btn btn-primary"/>
  <?php /*?><?php 
  if($id > 0){?>
	<input type="button" name="data_delete" id="data_delete" value="Delete" class="btn btn-danger" onclick="javascript:deleterecord('contracted_numbers',<?php echo $id;?>);"/>
	<?php
	} ?><?php */?>
</div>
<?php
print form_close() ."\r\n";
?>