<?php
header("Content-type: application/ms-excel");
header("Content-Disposition: attachment; filename=ksu_weekly_report.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1" id="rounded-corner" bordercolor="#fff" width="100%">
	<tr>
		<th colspan="5"><?php echo $this->lang->line('attendance_weekly_report_p_absent'); ?></th>
		<th rowspan="2"><?php echo $this->lang->line('attendance_weekly_report_p_student_without_absent'); ?></th>
		<th rowspan="2"><?php echo $this->lang->line('attendance_weekly_report_p_regularly_attending_students'); ?></th>
		<th rowspan="2"><?php echo $this->lang->line('attendance_weekly_report_p_discontinued_students'); ?></th>
		<th rowspan="2"><?php echo $this->lang->line('attendance_weekly_report_p_registered_students'); ?></th>
		<th rowspan="2">&nbsp;</th>
		<th rowspan="2"><?php echo $this->lang->line('attendance_weekly_report_p_course'); ?></th>
	</tr>
	<tr>
		<th><?php echo $this->lang->line('attendance_weekly_report_p_more_than'); ?> 25%</th>
		<th><?php echo $this->lang->line('attendance_weekly_report_p_between'); ?> 20% and 25%</th>
		<th><?php echo $this->lang->line('attendance_weekly_report_p_between'); ?> 15% and 20%</th>
		<th><?php echo $this->lang->line('attendance_weekly_report_p_between'); ?> 10% and 15%</th>
		<th><?php echo $this->lang->line('attendance_weekly_report_p_less_than'); ?> 10%</th>
	</tr>
	<?php
	if($data) {
		foreach ($data as $arrcourseid){
					foreach ($arrcourseid as $datas){
	?>
	<tr>
		<td><?php echo $datas[$type.'_student_more_than_25_per']; ?></td>
		<td><?php echo $datas[$type.'_student_between_20_25_per']; ?></td>
		<td><?php echo $datas[$type.'_student_between_10_20_per']; ?></td>
		<td><?php echo $datas[$type.'_student_between_10_15_per']; ?></td>
		<td><?php echo $datas[$type.'_student_less_than_10_per']; ?></td>
		<td><?php echo $datas[$type.'_without_absent_student']; ?></td>
		<td><?php echo $datas[$type.'_regular_student']; ?></td>
		<td><?php echo $datas[$type.'_discontinued_student']; ?></td>
		<td><?php echo $datas[$type.'_registered_student']; ?></td>
		<td>Number</td>
		<td rowspan=2><?php echo $datas['course_title']; ?></td>
	</tr>
	<tr>
		<td><?php echo @round((($datas[$type.'_student_more_than_25_per']/$datas[$type.'_regular_student'])*100),2); ?></td>
		<td><?php echo @round((($datas[$type.'_student_between_20_25_per']/$datas[$type.'_regular_student'])*100),2); ?></td>
		<td><?php echo @round((($datas[$type.'_student_between_10_20_per']/$datas[$type.'_regular_student'])*100),2); ?></td>
		<td><?php echo @round((($datas[$type.'_student_between_10_15_per']/$datas[$type.'_regular_student'])*100),2); ?></td>
		<td><?php echo @round((($datas[$type.'_student_less_than_10_per']/$datas[$type.'_regular_student'])*100),2); ?></td>
		<td><?php echo @round((($datas[$type.'_without_absent_student']/$datas[$type.'_regular_student'])*100),2); ?></td>
		<td><?php echo @round((($datas[$type.'_regular_student']/$datas[$type.'_registered_student'])*100),2); ?></td>
		<td><?php echo @round((($datas[$type.'_discontinued_student']/$datas[$type.'_registered_student'])*100),2); ?></td>
		<td>100</td>
		<td>Percentage</td>
	</tr>
<?php
	} 
	}
} 
?>
</table>