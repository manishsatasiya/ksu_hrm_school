<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_active_staff/add_note/'.$user_id.'/'.$id, array('id' => 'add_note_form_datatable','name'=>'formmain')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo 'Add comment'; }else{ echo 'Edit comment'; } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Note Type', 'note_type'); ?></div>
			<div class="input_box_thin"><?php  
				print form_dropdown('note_type',$note_type_list,($rowdata)?$rowdata->note_type:'0','id="note_type" class="formselect"'); 
			?></div>
		</div>
        <div class="col-md-6">
			<div class="form_label2"><?php print form_label('Department', 'department'); ?></div>
			<div class="input_box_thin"><?php  
				print form_dropdown('department',$department_list,($rowdata)?$rowdata->department:'0','id="department" class="formselect"'); 
			?></div>
		</div>
		<div class="clear"></div>
	</div>
    <div class="row form-row">
		<div class="col-md-6" id="professional_development_cat" style=" <?php if(isset($rowdata->department) && $rowdata->department == 8){} else { echo 'display:none;'; } ?>">
			<div class="form_label2"><?php print form_label('Category', 'category'); ?></div>
			<div class="input_box_thin"><?php  
				print form_dropdown('category',$professional_development_cat_list,($rowdata)?$rowdata->category:'0','id="category" class="formselect"'); 
			?></div>
		</div>
        <div class="col-md-6" id="academic_admin_cat" style=" <?php if(isset($rowdata->department) && $rowdata->department == 3){} else { echo 'display:none;'; } ?>">
			<div class="form_label2"><?php print form_label('Category', 'category'); ?></div>
			<div class="input_box_thin"><?php  
				print form_dropdown('category',$academic_admin_cat_list,($rowdata)?$rowdata->category:'0','id="category" class="formselect"'); 
			?></div>
		</div>
    </div>    
    <div class="row form-row">
        <div class="col-md-6">
			<div class="form_label2"><?php print form_label('Recommended Action', 'recommendation'); ?></div>
			<div class="input_box_thin"><?php  
				print form_dropdown('recommendation',$recommendation_list,($rowdata)?$rowdata->recommendation:'0','id="recommendation" class="formselect"'); 
			?></div>
		</div>
        <div class="col-md-6">
            <div class="form_label2"><?php print form_label('Show details to employee', 'show_to_employee'); ?></div>
            <?php $checked = (isset($rowdata->show_to_employee) && $rowdata->show_to_employee == '1') ? true:false; ?>
			<div class="input_box_thin"><?php print form_checkbox(array('name' => 'show_to_employee', 'id' => 'show_to_employee', 'value' => '1', 'checked' => $checked)); ?></div>
        </div>
        <div class="clear"></div>
    </div>
	<div class="row form-row">
        <div class="col-md-12">
			<div class="form_label2"><?php print form_label('Details', 'detail'); ?></div>
			<div class="input_box_thin"><?php print form_textarea(array('name' => 'detail', 'id' => 'detail', 'value' => ($rowdata)?$rowdata->detail:$this->session->flashdata('detail'), 'class' => 'form-control')); ?></div>
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