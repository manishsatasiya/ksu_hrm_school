<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('list_user/add_obs_comment/'.$user_id.'/', array('id' => 'add_obs_comment','name'=>'formmain')) ."\r\n";
?>
<div class="modal-header">
  <h2><?php echo 'Add Comment'; ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">
		<div class="col-md-12">
			<div class="form_label2"><?php print form_label('Comment', 'comment'); ?></div>
			<div class="input_box_thin"><?php  
				print form_input(array('name' => 'comment', 'id' => 'comment', 'value' => '', 'class' => 'form-control'));
			?></div>
		</div>
	</div>		
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<input type="submit" name="student_submit" id="student_submit" value="<?php echo 'Add'; ?>" class="btn btn-primary"/>
</div>	
<?php	
print form_close() ."\r\n";
?>