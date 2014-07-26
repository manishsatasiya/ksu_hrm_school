<?php
header("Content-type: application/ms-excel");
header("Content-Disposition: attachment; filename=teacher_attendance_report.xls");
header("Pragma: no-cache");
header("Expires: 0");
if(isset($export_data) && isset($export_data['rows']) && count($export_data['rows']) > 0)
{
?>
	<table border="1">
	  	<tr>
	  		<td align="center"><?php echo $this->lang->line('late_att_p_list_elsd_id'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('late_att_p_list_teacher'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('late_att_p_list_sec'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('late_att_p_list_course'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('late_att_p_list_shift'); ?></td>
	  		<td align="center"><?php echo $this->lang->line('campus'); ?></td>
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
<?php  
}
else 
{
	echo "Data not found";
}
?>  