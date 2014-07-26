<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_class_room/add/'.$id, array('id' => 'add_class_room_form_datatable','name'=>'formmain')) ."\r\n";
print form_hidden('gender', $value = 'M');
?>

<div class="modal-header">
  <h2><?php if(!$id){ echo $this->lang->line('class_room_p_add_heading'); }else{ echo $this->lang->line('class_room_p_edit_heading'); } ?></h2>
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
				<?php print form_dropdown('camps_id',$school_campus,($rowdata)?$rowdata->camps_id."j":$this->session->flashdata('camps_id'),'id="reg_camps_id" class="input_text1 qtip_camps_id"'); ?>
			</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<div class="form_label2"><?php print form_label($this->lang->line('class_room_p_title'), 'reg_class_room_title'); ?></div>
			<div class="input_box_thin"><?php print form_input(array('name' => 'class_room_title', 'id' => 'reg_class_room_title', 'value' => ($rowdata)?$rowdata->class_room_title:$this->session->flashdata('class_room_title'), 'class' => 'form-control input_text qtip_class_room_title')); ?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>		
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo $this->lang->line('class_room_p_add_btn'); }else{ echo $this->lang->line('class_room_p_edit_btn'); } ?>" class="btn btn-primary"/>
	<?php if($id){?>
		<input type="button" name="data_delete" id="data_delete" value="Delete" class="btn btn-danger" onclick="javascript:deleterecord('list_class_room',<?php echo $id;?>);"/>
	<?php
	}?>
</div>
<?php
print form_close() ."\r\n";
?>