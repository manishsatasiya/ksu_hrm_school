<script type="text/javascript" src="<?php print base_url(); ?>js/custom.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>

<div class="modal-header">
  <h2>Type : <?php echo ($rowdata)?$rowdata->note_type:''; ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<div class="row form-row">
		<div class="col-md-12">
			<b>User :</b><?php echo ($rowdata)?ucwords($rowdata->staff_name):''; ?>
		</div>
		<div class="col-md-12">
			<b>Department:</b><?php echo ($rowdata)?$rowdata->department_name:''; ?>
		</div>
        <div class="col-md-12">
			<b>Recommended:</b><?php echo ($rowdata)?profile_comment_recommendation($rowdata->recommendation):''; ?>
		</div>
        <div class="col-md-12">
			<b>Created by:</b><?php echo ($rowdata)?$rowdata->created_name:''; ?>
		</div>
        <div class="col-md-12">
			<b>Date:</b><?php echo ($rowdata)?$rowdata->created_at:''; ?>
		</div>
        <div class="col-md-12">
			<b>Show to employee:</b><?php echo ($rowdata && $rowdata->show_to_employee == '1')?'Yes':'No'; ?>
		</div>
        <div class="col-md-12">
			<b>Details:</b><br /><?php echo ($rowdata)?$rowdata->detail:''; ?>
		</div>
	</div>
    	
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>