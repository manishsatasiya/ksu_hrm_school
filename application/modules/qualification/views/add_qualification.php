<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('qualification/add/'.$id, array('id' => 'add_qualification_form_datatable','name'=>'formmain')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo 'Add qualification'; }else{ echo 'Edit qualification'; } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	
	<div class="row form-row">		
    	<div class="col-md-6">
			<div class="form-group">
			<div class="form_label"><?php print form_label('qualification', 'qualification'); ?></div>
			<div class="controls"><?php  
				print form_input(array('name' => 'qualification', 'id' => 'qualification', 'value' => ($rowdata)?$rowdata->qualification:$this->session->flashdata('qualification'), 'class' => 'form-control')); ?>
			</div>
			</div>
		</div>
        
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label"><?php print form_label('type', 'type'); ?></div>
			<div class="controls"><?php  
				print form_dropdown('type',array('qual'=>'qualification','cert'=>'certification'),($rowdata)?$rowdata->type:'0','id="type" class="qtip_name_suffix"'); ?>
			</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
    <div class="row form-row">
    	<div class="col-md-6">
			<div class="form-group">
			<div class="form_label"><?php print form_label('show_in_datatable', 'show_in_datatable'); ?></div>
			<div class="controls"><?php  
				print form_dropdown('show_in_datatable',array('No'=>'No','Yes'=>'Yes'),($rowdata)?$rowdata->show_in_datatable:'No','id="show_in_datatable" class=""'); ?>
			</div>
			</div>
		</div>
        <div class="col-md-6">
			<div class="form-group">
			<div class="form_label"><?php print form_label('datatable_display_order', 'datatable_display_order'); ?></div>
			<div class="controls"><?php  
				print form_input(array('name' => 'datatable_display_order', 'id' => 'datatable_display_order', 'value' => ($rowdata)?$rowdata->datatable_display_order:$this->session->flashdata('datatable_display_order'), 'class' => 'form-control')); ?>
			</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo 'Add qualification'; }else{ echo 'Edit qualification'; } ?>" class="btn btn-primary"/>
  <?php 
  if($id > 0){?>
	<input type="button" name="data_delete" id="data_delete" value="Delete" class="btn btn-danger" onclick="javascript:deleterecord('qualification',<?php echo $id;?>);"/>
	<?php
	} ?>
</div>
<?php
print form_close() ."\r\n";
?>