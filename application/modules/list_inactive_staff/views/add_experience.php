<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_user/add_experience/'.$user_id.'/'.$id, array('id' => 'add_user_experience','name'=>'formmain')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo 'Add experience'; }else{ echo 'Edit experience'; } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Company', 'company'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'company', 'id' => 'company', 'value' => ($rowdata)?$rowdata->company:$this->session->flashdata('company'), 'class' => 'form-control')); ?></div>
		</div>
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Position', 'position'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'position', 'id' => 'position', 'value' => ($rowdata)?$rowdata->position:$this->session->flashdata('position'), 'class' => 'form-control')); ?></div>
		</div>	
		<div class="clear"></div>
	</div>
	
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Start date', 'start_date'); ?></div>
			<div class="input-append success date col-md-10 no-padding">	
				<?php print form_input(array('name' => 'start_date', 'id' => 'start_date', 'value' => ($rowdata)?date('m/d/Y',strtotime($rowdata->start_date)):$this->session->flashdata('start_date'), 'class' => 'form-control')); ?>
				<span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('End Date', 'end_date'); ?></div>
			<div class="input-append success date col-md-10 no-padding">	
				<?php print form_input(array('name' => 'end_date', 'id' => 'end_date', 'value' => ($rowdata)?date('m/d/Y',strtotime($rowdata->end_date)):$this->session->flashdata('end_date'), 'class' => 'form-control')); ?>
				<span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-12">
			<div class="form_label2"><?php print form_label('Reason for Leaving', 'departure_reason'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'departure_reason', 'id' => 'departure_reason', 'value' => ($rowdata)?$rowdata->departure_reason:$this->session->flashdata('departure_reason'), 'class' => 'form-control')); ?></div>
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