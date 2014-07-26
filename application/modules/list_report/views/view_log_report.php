<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="modal-header">
  <h2>View Log</h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
  <br />
</div>
<div class="modal-body">
  <?php
if($data_array<>"")
{
	$arrdata = unserialize($data_array); 
?>
  <table border=1>
    <?php	
	foreach($arrdata[0] AS $key=>$val)
	{
		if(array_key_exists($key,$arrLogData)){
			$key = $arrLogData[$key];
		
?>
    <tr>
      <td><?php echo $key;?></td>
      <td><?php echo $val;?></td>
    </tr>
    <?php	
		}
	}
?>
  </table>
  <?php	
}
else
{
	echo "No database operation performed.";
}
?>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>