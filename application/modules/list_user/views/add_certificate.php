<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_user/add_certificate/'.$user_id.'/'.$id, array('id' => 'add_user_certificate','name'=>'formmain')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo 'Add Certificate'; }else{ echo 'Edit Certificate'; } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">
		<div class="col-md-6">
			<div class="form_label2"><?php print form_label('Certificate', 'certificate_id'); ?></div>
			<div class="input_box_thin"><?php  
				print form_dropdown('certificate_id',$certificate_list,($rowdata)?$rowdata->qualification_id:'0','id="certificate_id" class="formselect"'); 
			?></div>
		</div>
        <div class="col-md-6">
			<div class="form_label2"><?php print form_label('Date', 'date');?></div>
			<div class="input-append success date col-md-10 no-padding">	
				<?php print form_input(array('name' => 'date', 'id' => 'show_dp', 'value' => ($rowdata)?make_dp_date($rowdata->date):$this->session->flashdata('date'), 'class' => 'form-control')); ?>
				<span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
			</div>
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