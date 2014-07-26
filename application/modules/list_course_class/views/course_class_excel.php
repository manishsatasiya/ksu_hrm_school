<?php
header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=export.xls");
header("Pragma: no-cache");
header("Expires: 0");
if(isset($arrCourseClass) && count($arrCourseClass) > 0)
{
?>
	<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body>
	<table border="1">
	  	<tr>
			<td align="center"><?php echo $this->lang->line('course_class_p_course_name'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('course_class_p_primary_teacher'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('course_class_p_sec_teacher'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('course_class_p_class_room'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('course_class_p_section'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('course_class_p_shift'); ?></td>
	  	</tr>
	  	<?php 
	  	if($arrCourseClass){
			foreach ($arrCourseClass as $key => $row){
		?>
					<tr>
						<td><?php echo $row["course_title"]; ?></td>
						<td><?php echo $row["first_name"]; ?></td>
						<td><?php echo $row["second_name"]; ?></td>
						<td align="right"><?php echo $row["class_room_title"]; ?></td>
						<td align="right"><?php echo $row["section_title"]; ?></td>
						<td align="right"><?php echo $row["shift"]; ?></td>
					</tr>
		<?php
				}
				
			} 
}
else 
{
	echo "Data not found";
}
?>  
	</table>	
</body></html>	