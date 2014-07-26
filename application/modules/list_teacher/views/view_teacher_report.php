<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="modal-header">
  <h2>Teacher Report</h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
  <br />
</div>
<div class="modal-body">
<table border="1" width="100%">
<tr><th colspan="4">Section updated log</th></tr>
<tr><th width="50px">Section</th><th width="250px">Update By</th><th width="65px">Date</th><th>Reason</th></tr>
<?php 
if(!empty($section_log_data)) {
	foreach($section_log_data as $data1) { ?>
		<tr><td><?php echo addslashes($data1["section_title"]); ?></td>
		<td><?php echo addslashes($data1["first_name"]); ?></td>						
		<td><?php echo date('d-M-Y',strtotime($data1["change_date"])); ?></td>
		<td width=\"200px\"><?php echo addslashes(str_replace("'"," ",str_replace("\n"," ",str_replace("\r\n"," ",$data1["reason"])))) ?></td></tr><?php
	}
}else{
	echo '<tr><th colspan="3">No date found</th></tr>';
}	
?>
</table>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>