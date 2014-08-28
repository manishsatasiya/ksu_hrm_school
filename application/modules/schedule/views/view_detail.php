<script type="text/javascript" src="<?php print base_url(); ?>js/custom.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>

<div class="modal-header">
  <h2>Appointment:<?php echo ($rowdata)?make_dp_date($rowdata->date):''; ?></h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
	<div class="row form-row">
		<div class="col-md-12">
			<b>Type:</b><?php echo ($rowdata)?ucwords($rowdata->type):''; ?>
		</div>
		<div class="col-md-12">
			<b>Title:</b><?php echo ($rowdata)?$rowdata->subject:''; ?>
		</div>
        <div class="col-md-12">
			<b>Details:</b><br /><?php echo ($rowdata)?$rowdata->details:''; ?>
		</div>
	</div>
    	
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <a href="#<?= $id ?>" class="btn btn-primary" id="close_view_model">Edit</a>
    <a href="schedule/delete/<?= $id ?>" class="btn btn-danger" id="">Delete</a>
</div>