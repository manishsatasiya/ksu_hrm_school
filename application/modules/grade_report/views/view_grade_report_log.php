<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
</h2>
<div class="modal-header">
  <h2>Student Grade Report Log</h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">
<table class="table">
	<thead>
	<tr>
		<th width="10%">Changed By</th>
		<th width="4%">Changed <br />Date</th>
		<th width="4%">Exam Type</th>
		<th width="4%">Changed Field(s)</th>
		<th width="4%">OLD</th>
		<th width="4%">Changed</th>
	</tr>
    </thead>
	<?php
	$l = 0;
	foreach($student_grade_report_log as $contkey=>$rowdataLog)
	{
		$arrChangedFields = explode("|",$rowdataLog["changed_fields"]);
	?>		
		<tr>
			<td rowspan=<?php echo count($arrChangedFields);?>><?php echo($rowdataLog['first_name']); ?></td>
			<td rowspan=<?php echo count($arrChangedFields);?>><?php echo($rowdataLog['chndate']); ?></td>		
			<td rowspan=<?php echo count($arrChangedFields);?> style="width: 7%;"><?php echo $rowdataLog["exam_type_name"];?></td>
		<?php
		for($i=0;$i<count($arrChangedFields);$i++)
		{
			$fieldname = $arrChangedFields[$i];
			
			$examname = "";
			
			if($fieldname == "exam_marks")
				$examname = "Marks";
			if($fieldname == "exam_marks_2")
				$examname = "2nd Marks";
			if($fieldname == "exam_marks_3")
				$examname = "3rd Marks";		
			if($fieldname == "exam_status")
				$examname = "Attendance";
				
			if($i > 0)
			{			
		?>	
			<tr>
		<?php
			}
		?>	
				<td style="width: 7%;"><?php echo $examname;?></td>
				<td style="width: 7%;"><?php echo $rowdataLog[$fieldname];?></td>
				<td style="width: 7%;"><?php echo $rowdataLog[$fieldname."_new"];?></td>
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