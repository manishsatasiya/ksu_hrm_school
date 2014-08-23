<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('job_title/add/'.$job_title_id, array('id' => 'add_job_title_form_datatable','name'=>'formmain')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$job_title_id){ echo 'Add job title'; }else{ echo 'Edit job title'; } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">		
		<div class="col-md-6">
			<div class="form_label"><?php print form_label('Job title name', 'reg_job_title'); ?></div>
			<div class="controls"><?php print form_input(array('name' => 'job_title', 'id' => 'reg_job_title', 'value' => ($rowdata)?$rowdata->job_title:$this->session->flashdata('job_title'), 'class' => 'form-control qtip_job_title')); ?></div>
		</div>		
		<div class="clear"></div>
	</div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <input type="submit" name="student_submit" id="student_submit" value="<?php if(!$job_title_id){ echo 'Add job title'; }else{ echo 'Edit job title'; } ?>" class="btn btn-primary"/>
  <?php 
  if($job_title_id > 0){?>
	<input type="button" name="data_delete" id="data_delete" value="Delete" class="btn btn-danger" onclick="javascript:deleterecord('job_title',<?php echo $job_title_id;?>);"/>
	<?php
	} ?>
</div>
<?php
print form_close() ."\r\n";
?>