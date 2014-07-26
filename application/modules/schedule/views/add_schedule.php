<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('contractors/add/'.$id, array('id' => 'add_contractors_form_datatable','name'=>'formmain')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo 'Add Contractor'; }else{ echo 'Edit Contractor'; } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">		
		<div class="col-md-6">
			<div class="form_label"><?php print form_label('Contractor', 'reg_contractor'); ?></div>
			<div class="controls"><?php print form_input(array('name' => 'contractor', 'id' => 'reg_contractor', 'value' => ($rowdata)?$rowdata->contractor:$this->session->flashdata('contractor'), 'class' => 'form-control qtip_contractor')); ?></div>
		</div>		
		<div class="clear"></div>
	</div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo 'Add Contractor'; }else{ echo 'Edit Contractor'; } ?>" class="btn btn-primary"/>
  <?php 
  if($id > 0){?>
	<input type="button" name="data_delete" id="data_delete" value="Delete" class="btn btn-danger" onclick="javascript:deleterecord('contractors',<?php echo $id;?>);"/>
	<?php
	} ?>
</div>
<?php
print form_close() ."\r\n";
?>