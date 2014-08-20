<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('workshops/add/'.$workshop_id, array('id' => 'add_workshop_form_datatable','name'=>'formmain')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$workshop_id){ echo 'Add Workshop'; }else{ echo 'Edit Workshop'; } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label"><?php print form_label('Presenter', 'reg_presenter'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('presenter',$presenter_list,($rowdata)?$rowdata->presenter:$this->session->flashdata('presenter'),'id="reg_presenter" class=""'); ?></div>
		</div>		
		<div class="col-md-6">
			<div class="form_label"><?php print form_label('Title', 'reg_title'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'title', 'id' => 'reg_title', 'value' => ($rowdata)?$rowdata->title:$this->session->flashdata('title'), 'class' => 'form-control qtip_title')); ?></div>
		</div>		
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Attendee Limit', 'reg_attendee_limit'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'attendee_limit', 'id' => 'reg_attendee_limit', 'value' => ($rowdata)?$rowdata->attendee_limit:$this->session->flashdata('attendee_limit'), 'class' => 'form-control')); ?></div>
		</div>
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Topic', 'reg_topic'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'topic', 'id' => 'reg_topic', 'value' => ($rowdata)?$rowdata->topic:$this->session->flashdata('topic'), 'class' => 'form-control ')); ?></div>
		</div>	
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Venue', 'reg_venue'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'venue', 'id' => 'reg_venue', 'value' => ($rowdata)?$rowdata->venue:$this->session->flashdata('venue'), 'class' => 'form-control')); ?></div>
		</div>	
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Time', 'reg_time'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'time', 'id' => 'reg_time', 'value' => ($rowdata)?$rowdata->time:$this->session->flashdata('time'), 'class' => 'form-control')); ?></div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Date', 'start_date'); ?></div>
			<div class="input-append success date col-md-10 col-lg-6 no-padding">
                <?php print form_input(array('name' => 'start_date', 'id' => 'show_dp', 'value' => ($rowdata)?make_dp_date($rowdata->start_date):$this->session->flashdata('start_date'), 'class' => 'form-control')); ?>
				<span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
			</div>
		</div>
		
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Semester', 'reg_semester'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('semester',$semester,($rowdata)?$rowdata->semester:$this->session->flashdata('semester'),'id="reg_semester" class=""'); ?></div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Required', 'reg_workshop_type_id'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('workshop_type_id',$workshop_type_list,($rowdata)?$rowdata->workshop_type_id:$this->session->flashdata('workshop_type_id'),'id="reg_workshop_type_id" class=""'); ?></div>
		</div>	
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Status', 'reg_status'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('status',$status,($rowdata)?$rowdata->status:$this->session->flashdata('status'),'id="reg_status" class=""'); ?></div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Presented', 'presented'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('presented',array('2'=>'No','1'=>'Yes'),($rowdata)?$rowdata->presented:$this->session->flashdata('presented'),'id="presented" class=""'); ?></div>
		</div>	
    </div>    
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <input type="submit" name="student_submit" id="student_submit" value="<?php if(!$workshop_id){ echo 'Add Workshop'; }else{ echo 'Update Workshop'; } ?>" class="btn btn-primary"/>
  <?php 
  if($workshop_id > 0){?>
	<input type="button" name="data_delete" id="data_delete" value="Delete" class="btn btn-danger" onclick="javascript:deleterecord('workshops',<?php echo $workshop_id;?>);"/>
	<?php
	} ?>
</div>
<?php
print form_close() ."\r\n";
?>

