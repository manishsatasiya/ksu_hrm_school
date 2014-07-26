<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('workshops/add_attendee/'.$id, array('id' => 'add_attendee_form','name'=>'formmain')) ."\r\n";
$attendee_style=$workshop_id_style='';
if($attendee)
	$attendee_style = 'disabled="disabled"';
if($workshop_id)
	$workshop_id_style = 'disabled="disabled"';
?>
<div class="modal-header">
  <h2><?php if(!$id){ echo 'Add attendee to workshop  '; if($workshop_id){ echo '<b>'.$workshop_list[$workshop_id].'</b>'; } }else{ echo 'Edit attendee  '; if($attendee){ echo '<b>'.$attendee_list[$attendee].'</b>'; } } ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<?php $this->load->view('generic/flash_error'); ?>
	<div class="containerfdfdf"></div>
	<div class="row form-row">
		<?php if(!$attendee){ ?>
		<div class="col-md-6">
			<div class="form_label"><?php print form_label('Attendee', 'reg_attendee'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('attendee',$attendee_list,$attendee,'id="reg_attendee" class="" '.$attendee_style); ?></div>
		</div>	
		<?php 
		}else{
			print form_hidden('attendee', $attendee);
		}	 ?>	
		<?php if(!$workshop_id){ ?>
		<div class="col-md-6">
			
			<div class="form_label2"><?php print form_label('Workshop', 'reg_workshop_id'); ?></div>
			<div class="input_box_thin"><?php print form_dropdown('workshop_id',$workshop_list,$sel_workshop_id,'id="reg_workshop_id" class="" '.$workshop_id_style); ?></div>
		</div>
		<?php 
		}else{
			print form_hidden('workshop_id', $workshop_id);
		}	 ?>
		<div class="clear"></div>
	</div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <input type="submit" name="student_submit" id="student_submit" value="<?php if(!$id){ echo 'Add Attendee'; }else{ echo 'Edit Attendee'; } ?>" class="btn btn-primary"/>
</div>
<?php
print form_close() ."\r\n";
?>

