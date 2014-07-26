<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<div id="containers">
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_section/add/'.$id, array('id' => 'add_section_form_datatable','name'=>'formmain')) ."\r\n";
print form_hidden('gender', $value = 'M');
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo $this->lang->line('section_p_add_heading'); }else{ echo $this->lang->line('section_p_edit_heading'); } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('campus'), 'reg_camps_id'); ?></div>
			<div class="input_box_thin">
				<?php print form_dropdown('camps_id',$school_campus,($rowdata)?$rowdata->camps_id."j":$this->session->flashdata('camps_id'),'id="reg_camps_id" class="qtip_camps_id"'); ?>
			</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('section_p_title'), 'reg_section_title'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'section_title', 'id' => 'reg_section_title', 'value' => ($rowdata)?$rowdata->section_title:$this->session->flashdata('section_title'), 'class' => 'form-control qtip_section_title')); ?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>		
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('ca_lead_teacher'), 'reg_ca_lead_teacher'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('ca_lead_teacher',$get_ca_lead_teacher_list,($rowdata)?$rowdata->ca_lead_teacher."j":$this->session->flashdata('ca_lead_teacher'),'id="reg_ca_lead_teacher" class="qtip_ca_lead_teacher"'); ?></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label('building', 'reg_buildings'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('buildings',$buildings,($rowdata)?$rowdata->buildings:$this->session->flashdata('buildings'),'id="reg_buildings" class="qtip_buildings"'); ?></div>
			</div>	
		</div>
		<div class="clear"></div>
	</div>	
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('att_rep_p_track'), 'reg_track'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('track',$track,($rowdata)?$rowdata->track:$this->session->flashdata('track'),'id="reg_track" class="qtip_track"'); ?></div>
			</div>
		</div>	
		<div class="clear"></div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo $this->lang->line('section_p_add_btn'); }else{ echo $this->lang->line('section_p_edit_btn'); } ?>" class="btn btn-primary"/>
	<?php if($id){?>
		<input type="button" name="data_delete" id="data_delete" value="Delete" class="btn btn-danger" onclick="javascript:deleterecord('list_section',<?php echo($id);?>);"/>
	<?php
	} ?>
</div>	
<?php	
print form_close() ."\r\n";
?>
