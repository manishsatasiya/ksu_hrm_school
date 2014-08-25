<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('schedule/add_appointment/', array('id' => 'add_appointment','name'=>'formmain')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo 'Add appointment'; }else{ echo 'Edit appointment'; } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">
		<div class="col-md-4">
			<div class="form_label2"><?php print form_label('Date', 'date'); ?></div>
			<div class="input-append success date col-md-10 col-lg-10 no-padding">
            	<?php print form_input(array('name' => 'date', 'id' => 'date', 'value' => ($rowdata)?date('m/d/Y',strtotime($rowdata->date)):$this->session->flashdata('date'), 'class' => 'form-control ')); ?>
                <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
            </div>            
		</div>
		<div class="col-md-4">
			<div class="form_label2"><?php print form_label('Time', 'time'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'time', 'id' => 'time', 'value' => ($rowdata)?$rowdata->time:$this->session->flashdata('time'), 'class' => 'form-control')); ?></div>
		</div>	
		<div class="col-md-4">
			<div class="form_label2"><?php print form_label('Type', 'type'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('type',array('private'=>'Private','work'=>'Work'),($rowdata)?$rowdata->type:'0','id="type" class="formselect"');  ?></div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="row form-row">
		<div class="col-md-12">
			<div class="form_label2"><?php print form_label('Subject', 'subject'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'subject', 'id' => 'subject', 'value' => ($rowdata)?$rowdata->details:$this->session->flashdata('subject'), 'class' => 'form-control','maxlength'=>'20')); ?></div>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="row form-row">
		<div class="col-md-12">
			<div class="form_label2"><?php print form_label('Details', 'details'); ?></div>
			<div class="input_box_thin"><?php print form_textarea(array('name' => 'details', 'id' => 'details', 'value' => ($rowdata)?$rowdata->details:$this->session->flashdata('details'), 'class' => 'form-control')); ?></div>
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