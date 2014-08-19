<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_user/add_qualifications/'.$user_id.'/'.$id, array('id' => 'add_user_qualifications','name'=>'formmain')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo 'Add Qualification'; }else{ echo 'Edit Qualification'; } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Qualification', 'qualification_id'); ?></div>
			<div class="input_box_thin"><?php  
				print form_dropdown('qualification_id',$qualifications_list,($rowdata)?$rowdata->qualification_id:'0','id="qualification_id" class="formselect"'); 
			?></div>
		</div>
        <div class="col-md-6">
			<div class="form_label2"><?php print form_label('Date', 'date'); ?></div>
			<div class="input-append success date col-md-10 no-padding">	
				<?php print form_input(array('name' => 'date', 'id' => 'show_dp', 'value' => ($rowdata)?make_dp_date($rowdata->date):$this->session->flashdata('date'), 'class' => 'form-control')); ?>
				<span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
			</div>
		</div>	
		<div class="clear"></div>
	</div>
	
	<div class="row form-row">
		<div class="col-md-12">
			<div class="form_label2"><?php print form_label('Subject', 'subject'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'subject', 'id' => 'subject', 'value' => ($rowdata)?$rowdata->subject:$this->session->flashdata('subject'), 'class' => 'form-control')); ?></div>
		</div>
		
		<div class="clear"></div>
	</div>
    <div class="row form-row">
        <div class="col-md-6">
            <div class="form_label2"><?php print form_label('Institute', 'institute'); ?></div>
            <div class="input_box_thin"><?php print form_input(array('name' => 'institute', 'id' => 'institute', 'value' => ($rowdata)?$rowdata->institute:$this->session->flashdata('institute'), 'class' => 'form-control')); ?></div>
        </div>
        <div class="col-md-6">
            <div class="form_label2"><?php print form_label('Graduation year', 'graduation_year'); ?></div>
            <div class="input-append success col-md-10 no-padding">	
                <?php print form_input(array('name' => 'graduation_year', 'id' => 'date_year', 'value' => ($rowdata)?$rowdata->graduation_year:$this->session->flashdata('graduation_year'), 'class' => 'form-control')); ?>
                <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="row form-row">
    	<div class="col-md-4">
			<div class="form_label2"><?php print form_label('Accredited', 'accredited'); ?></div>
            <?php $checked = (isset($rowdata->accredited) && $rowdata->accredited == '1') ? true:false; ?>
			<div class="input_box_thin"><?php print form_checkbox(array('name' => 'accredited', 'id' => 'accredited', 'value' => '1', 'checked' => $checked)); ?></div>
		</div>
        <div class="col-md-4">
			<div class="form_label2"><?php print form_label('In Class', 'in_class'); ?></div>
            <?php $checked = (isset($rowdata->in_class) && $rowdata->in_class == '1') ? true:false; ?>
			<div class="input_box_thin"><?php print form_checkbox(array('name' => 'in_class', 'id' => 'in_class', 'value' => '1', 'checked' => $checked)); ?></div>
		</div>
        <div class="col-md-4">
			<div class="form_label2"><?php print form_label('Subject Related', 'subject_related'); ?></div>
            <?php $checked = (isset($rowdata->subject_related) && $rowdata->subject_related == '1') ? true:false; ?>
			<div class="input_box_thin"><?php print form_checkbox(array('name' => 'subject_related', 'id' => 'subject_related', 'value' => '1', 'checked' => $checked)); ?></div>
		</div>
    </div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Verified', 'verified'); ?></div>
			<div class="input_box_thin"><?php  
				print form_dropdown('verified',array('N/A','Yes','No'),($rowdata)?$rowdata->verified:'','id="verified" class="formselect"'); 
			?></div>
		</div>
        <div class="col-md-6">
			<div class="form_label2"><?php print form_label('Comments', 'comments'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'comments', 'id' => 'comments', 'value' => ($rowdata)?$rowdata->comments:$this->session->flashdata('comments'), 'class' => 'form-control')); ?></div>
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