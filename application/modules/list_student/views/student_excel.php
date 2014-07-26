<?php
header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=export.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
if(isset($arrStudent) && count($arrStudent) > 0)
{
?>
<table border="1">
  <tr>
    <td align="center"><?php echo $this->lang->line('student_p_stud_id'); ?></td>
    <td align="center"><?php echo $this->lang->line('student_p_full_name'); ?></td>
	<td align="center"><?php echo $this->lang->line('att_rep_p_student_arabic_name'); ?></td>
    <td align="center"><?php echo $this->lang->line('sch_date'); ?></td>
    <td align="center"><?php echo $this->lang->line('student_p_status'); ?></td>
    <td align="center"><?php echo $this->lang->line('student_p_section'); ?></td>
    <td align="center"><?php echo $this->lang->line('class_room_sub'); ?></td>
    <td align="center"><?php echo $this->lang->line('course_class_sub'); ?></td>
    <td align="center"><?php echo $this->lang->line('course_class_p_shift'); ?></td>
    <td align="center">Timing</td>
    <td align="center"><?php echo $this->lang->line('att_rep_p_track'); ?></td>
    <td align="center">Building</td>
	<td align="center"><?php echo $this->lang->line('campus'); ?></td>
	<td align="center"><?php echo $this->lang->line('course_class_p_primary_teacher'); ?></td>
	<td align="center"><?php echo $this->lang->line('course_class_p_secondary_teacher'); ?></td>
  </tr>
  <?php 
	  	if($arrStudent){
			foreach ($arrStudent as $key => $row){
		?>
  <tr>
    <td><?php echo $row["student_uni_id"]; ?></td>
    <td><?php echo $row["first_name"]; ?></td>
	<td><?php echo $row["first_name_arabic"]; ?></td>
    <td><?php echo $row["stu_schedule_date"]; ?></td>
    <td align="right"><?php echo $row["academic_status"]; ?></td>
    <td align="right"><?php echo $row["section_title"]; ?></td>
    <td align="right"><?php echo $row["class_room_title"]; ?></td>
    <td align="right"><?php echo $row["course_title"]; ?></td>
    <td align="right"><?php echo $row["shift"]; ?></td>
    <td align="right"><?php echo $row["start_time"]." - ".$row["end_time"]; ?></td>
    <td align="right"><?php echo $row["section_track"]; ?></td>
    <td align="right"><?php echo $row["section_buildings"]; ?></td>
    <td align="right"><?php echo $row["campus"]; ?></td>
    <td align="right"><?php echo $row["pname"]; ?></td>
    <td align="right"><?php echo $row["sname"]; ?></td>
    
  </tr>
  <?php
				}
			} ?>
</table>
<?php		
}
else 
{
	echo "Data not found";
}
?>
</body>
</html>