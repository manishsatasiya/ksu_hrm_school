<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('nationality/add/'.$id, array('id' => 'add_nationality_form_datatable','name'=>'formmain')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo 'Add nationality'; }else{ echo 'Edit nationality'; } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	
	<div class="row form-row">		
    	<div class="col-md-6">
			<div class="form-group">
			<div class="form_label"><?php print form_label('Nationality', 'nationality'); ?></div>
			<div class="controls"><?php  
				print form_input(array('name' => 'nationality', 'id' => 'nationality', 'value' => ($rowdata)?$rowdata->nationality:$this->session->flashdata('nationality'), 'class' => 'form-control')); ?>
			</div>
			</div>
		</div>
        
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label"><?php print form_label('Native', 'native'); ?></div>
			<div class="controls"><?php  
				print form_dropdown('native',array('No'=>'No','Yes'=>'Yes'),($rowdata)?$rowdata->native:'0','id="native" class="qtip_name_suffix"'); ?>
			</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
    <div class="row form-row">
    	<div class="col-md-6">
			<div class="form-group">
			<div class="form_label"><?php print form_label('Accepted nationality', 'native'); ?></div>
			<div class="controls"><?php  
				print form_dropdown('accepted',array('No'=>'No','Yes'=>'Yes'),($rowdata)?$rowdata->accepted:'No','id="accepted" class=""'); ?>
			</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo 'Add Nationality'; }else{ echo 'Edit Nationality'; } ?>" class="btn btn-primary"/>
  <?php 
  if($id > 0){?>
	<input type="button" name="data_delete" id="data_delete" value="Delete" class="btn btn-danger" onclick="javascript:deleterecord('Nationality',<?php echo $id;?>);"/>
	<?php
	} ?>
</div>
<?php
print form_close() ."\r\n";
?>