<?php
header('Content-Transfer-Encoding: none');
header("Content-type: application/octet-stream");
header('Content-Type: application/vnd.ms-excel;');// This should work for IE & Opera
header("Content-type: application/x-msexcel");   
header("Content-Disposition: attachment; filename=attendance_report.xls");
header("Pragma: no-cache");
header("Expires: 0");
if(isset($export_data) && isset($export_data['rows']) && count($export_data['rows']) > 0)
{
?>
	<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body>
	<table border="1">
	  	<tr>
	  		<td align="center"><?php echo $this->lang->line('att_rep_p_list_studid'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('att_rep_p_list_stud_name'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('att_rep_p_student_arabic_name'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('att_rep_p_sche_date'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('att_rep_p_status'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('att_rep_p_list_section'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('att_rep_p_list_class_room'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('att_rep_p_class_name'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('att_rep_p_list_shift'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('att_rep_p_track'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('att_rep_p_list_campus'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('att_rep_p_list_teacher_name'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('att_rep_p_sec_teacher_name'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('att_rep_p_percentage'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('att_rep_p_list_tot_abs_hours'); ?></td>
	  		<?php
	  		if($enable_week){
			foreach ($enable_week as $enable_wek){
			?>
	  		<td><?php echo $this->lang->line('att_rep_p_week'); ?> <?php echo $enable_wek->week_id;?></td>
	  		<?php
	  		}
	  		} 
	  		?>
	  	</tr>
	  	<?php 
	  	if($export_data){
			foreach ($export_data['rows'] as $key => $val){
				if($val){
					echo '<tr>';
					foreach ($export_data['rows'][$key] as $fieldname=>$data){
					
					$per_sign = '';
					$align = 'align="center"';
					if($fieldname == "attendance_perc") $per_sign = '%';
					if($fieldname == "student_name") $align = 'align="left"';
					if($fieldname == "student_arabicname") $align = 'align="right"';
	  	?>
				  	<td <?php echo $align;?>><?php echo $data.$per_sign; ?></td>
		<?php
					}
					echo '</tr>';
				}
				
			} 
	  	}	  	
		?>
	</table>	
</body></html>	
<?php  
}
else 
{
	echo "Data not found";
}
?>  