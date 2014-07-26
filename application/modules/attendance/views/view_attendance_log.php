<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="modal-header">
  <h2>Student Attendance Log</h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
<table align="center" border="0" cellpadding="5" cellspacing="0" width="100%" class="simple-tbl">
	<thead>
	<tr>
		<th width="20%">Changed By</th>
		<th width="4%">Changed <br />Date</th>
		<th width="3%" colspan="2">Week</th>			
	</tr>
    </thead>
	<?php
	$l = 0;
	foreach($student_attendance_log as $week=>$rowdataLog)
	{
	?>		
	<tr>
		<td rowspan="2" style="border-right:none;"></td>
		<td rowspan="2" style="border-left:none;"></td>		
		<td colspan="2" align="center"><?php echo "Week ".$week; ?></td>		
	</tr>
	<tr>
		<td><b>OLD</b></td>			
		<td><b>Changed</b></td>			
	</tr>
		<?php
		foreach($rowdataLog as $log_data)
		{
		?>
		<tr>
			<?php
			$a_hour = $log_data['a_hour']; 
			$a_hour_new = $log_data['a_hour_new'];
			?>
			<td><?php echo($log_data['first_name']); ?></td>
			<td><?php echo(date('d-M-Y',strtotime($log_data['chndate']))); ?></td>		
			<td style="width: 7%;"><?php echo $a_hour;?></td>
			<td style="width: 7%;"><?php echo $a_hour_new;?></td>
		</tr>
		<?php 
		}
	} 
	?>
</table>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>