<script type="text/javascript" language="javascript" src="<?php print base_url(); ?>js/jquery_datatables_editable/media/js/jquery-ui-tabs.js"></script>

<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		<?php
		$tabcnt = 0;
	    if (isset($course_class)) 
	    {
	    	foreach ($course_class->result() as $course_classes)
	    	{
	    ?>
	   			$("#tabs_<?php echo $tabcnt;?>").tabs();	 
	    <?php		
	    		$tabcnt++;	
	    	}
	    }
		?>
	} );
</script>
<div id="containers" style="overflow:scroll; height:100%;">
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if(!isset($studentname))
{
	echo "No Data Found";
	exit;
}
?>
<h2><?php echo $this->lang->line('student_report'); ?> : <?php echo $studentname;echo " (".$studentuniid.")";?></h2>
<a target="_blank" class="pdf_but" href="<?php echo site_url("list_student/create_report_pdf"); ?>/<?=$studentuniid?>">Generate PDF</a>
<?php
if($attendance)
{
?>
<table border="1" CELLSPACING=4 CELLSPACING=3 width="100%">
<?php	
$flag = 1;
	foreach($attendance as $key=>$arrData)
	{
		if($flag == 1)
		{
?>
			<tr>
				<td align="center"><strong><?php echo $this->lang->line('student_attendance_report'); ?></strong></td>
			</tr>
<?php
		}
		$flag = 0;
			
		if($key == "school_year_title")
		{	
?>		
		<tr>
			<td align="center"><strong><?php echo $this->lang->line('student_class_info'); ?></strong></td>
		</tr>
<?php
		}
?>		
<?php		
		if($key == "student_info")
		{
?>		
		<tr><td><table border="1" width="100%"><tr>
<?php	
		foreach($arrData AS $key_info=>$val)
		{
?>	
			<td><?php echo $key_info;?></td>
<?php	
		}
?>
		</tr>
		<tr>
<?php	
		foreach($arrData AS $key_info=>$val)
		{
?>	
			<td><?php echo $val;?></td>
<?php	
		}
?>
		</tr>
		</table></td></tr>
<?php
		}
		
		if($key == "school_year_title")
		{
			$temp_school_year_title = $arrData;	
		}
				
		if($key == "weeks")
		{
?>		
		<tr><td align="center"><?php echo $temp_school_year_title;?> <?php echo $this->lang->line('attendance'); ?></td></tr>
		<tr><td><table border="1" width="100%"><tr>
<?php	
		foreach($arrData AS $key_weeks=>$val)
		{
?>
			<td><?php echo $key_weeks;?></td>
<?php	
		}
?>
		</tr>
		<tr>
<?php	
		foreach($arrData AS $key_weeks=>$val)
		{
?>
			
			<td><?php echo $val;?></td>
<?php	

		}
?>
		</tr>
		</table></td></tr>
<?php	
		}		
	}
?>
</table>
<?php	
}
else
{
	echo "No attendance data found.";
}
?>

<br/><br/>

<table border="1" CELLSPACING=4 CELLSPACING=3 width="98%">
<tr><td align="center"><strong><?php echo $this->lang->line('grade_report'); ?></strong></td></tr>
<tr>
<td width="100%">
<?php
$j = 1;
$tabcnt = 0;
if (isset($course_class)) {
	
foreach ($course_class->result() as $course_classes):
$course_class_week = $course_classes->school_week;
$max_hours = $course_classes->max_hours;
?>
<div id="tabs_<?php echo $tabcnt?>" style="height:100%;width:100%;">
<ul style="height: 40px; width: 95%;">
<?php
foreach($grade_type AS $grade_type_id=>$grade_type_data)
{
?>
	<li><a href="#tabs_<?php echo $tabcnt;?>_<?php echo $grade_type_id;?>"><?php echo $grade_type_data["grade_type"];?></a></li>
<?php
}
if($show_total_tab == "Yes")
{
?>
<li><a href="#tabs_<?php echo $tabcnt;?>_1111"><?php echo $this->lang->line('totals'); ?></a></li>
<?php
}
?>
</ul>

<?php
$arrTotals_Tab = array();
foreach($grade_type AS $grade_type_id=>$grade_type_data)
{
?>
	<div id="tabs_<?php echo $tabcnt;?>_<?php echo $grade_type_id;?>">
		<div style="">
		<?php
if(isset($grade_type_exam[$grade_type_id]))
{
		$arr_grade_exam = $grade_type_exam[$grade_type_id];
		
		if (isset($course_classes->student)) 
		{
			$iscolspan = 0;
			$colspan = "";
			$rowspan = "";
			
			foreach ($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data){
				if($grade_type_exam_data["is_show_percentage"] == "Yes" || $grade_type_exam_data["is_two_marker"] == "Yes")
				{
					$rowspan = 'rowspan="2"';
					if($grade_type_exam_data["is_two_marker"] == "Yes")
								$colspan = 'colspan="4"';
				}
			}
		?>
		<table border="1" cellpadding="5" cellspacing="0" width="100%" height="100%">
			<tr>
				<?php
				foreach ($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data){
				$colspan = '';
				if($grade_type_exam_data["is_show_percentage"] == "Yes" || $grade_type_exam_data["is_two_marker"] == "Yes")
				{
					$iscolspan = 1;
					$colspan = 'colspan="2"';
					
					if($grade_type_exam_data["is_two_marker"] == "Yes")
					{
						$colspancnt = 4;
						
						if($grade_type_exam_data["is_show_percentage"] == "Yes")
							$colspancnt++;
							
						$colspan = 'colspan="'.$colspancnt.'"';
					}
				}
				
				if($grade_type_data["attendance_type"] == "examwise")
				{
				?>
				<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('attendance'); ?></th>
				<?php
				}
				?>
				<th <?php echo $colspan;?> valign="top"><?php echo $grade_type_exam_data["exam_type_name"];if($iscolspan == 0){echo '<br>('.$grade_type_exam_data["exam_marks"].')';} ?></th>
					
				<?php 
				}
				if($grade_type_data["attendance_type"] == "common")
				{
				?>
				<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('attendance'); ?></th>
				<?php
				}
				if($grade_type_data["show_total_marks"] == "Yes")
				{
				?>
				<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('total_marks'); ?>(<?php echo $grade_type_data["total_markes"];?>)</th>
				<?php
				}
				if($grade_type_data["show_grade_range"] == "Y")
				{
				?>
				<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('range'); ?></th>
				<?php
				}
				if($grade_type_data["show_total_per"] == "Yes")
				{
				?>	
				<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('total'); ?> %(<?php echo $grade_type_data["total_percentage"];?>)</th>
				<?php
				}
				?>
			</tr>
			<?php
			if($iscolspan == 1)
			{
			?>
			<tr>
				<?php
				foreach ($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data){
				if($grade_type_exam_data["is_two_marker"] == "Yes")
				{
				?>
				<th><?php echo $this->lang->line('Marks'); ?> 1 </th>
				<th><?php echo $this->lang->line('Marks'); ?> 2 </th>
				<th><?php echo $this->lang->line('Marks'); ?> 3(<?php echo $this->lang->line('optional'); ?>) </th>
				<th><?php echo $this->lang->line('Marks'); ?>(<?php echo $grade_type_exam_data["exam_marks"]; ?>) </th>
				<?php
				if($grade_type_exam_data["is_show_percentage"] == "Yes")
				{
				?>
				<th><?php echo $grade_type_exam_data["exam_percentage"]; ?>% </th>
				<?php
				}
				}
				else
				{
				?>
				<th>Marks(<?php echo $grade_type_exam_data["exam_marks"]; ?>) </th>
				<?php
					if($grade_type_exam_data["is_show_percentage"] == "Yes")
					{
				?>
					<th><?php echo $grade_type_exam_data["exam_percentage"]; ?>% </th>
				<?php 
					}
				}
				}
				?>
			
			</tr>
		<?php
			}
			$l = 0;
			foreach ($course_classes->student as $student_datas)
			{
		?>		
			<tr>
				<?php 
				$total_100_percentage = 0;
				$temp_grade_type = $grade_type;							
						
				foreach($temp_grade_type AS $temp_grade_type_id=>$temp_grade_type_data)	
				{
					$temp_total_exam_mark = 0;
					if(isset($grade_type_exam[$temp_grade_type_id]))
					{
						$temp_arr_grade_exam = $grade_type_exam[$temp_grade_type_id];
						foreach($temp_arr_grade_exam as $temp_grade_type_exam_id=>$temp_grade_type_exam_data)
						{
							$temp_exam_mark = 0;
							if(isset($course_classes->student_grade_data[$course_classes->section_id][$temp_grade_type_exam_id][$student_datas->student_uni_id]) && 
						isset($course_classes->student_grade_data[$course_classes->section_id][$temp_grade_type_exam_id][$student_datas->student_uni_id]["exam_marks"]))
						{
							if($temp_grade_type_exam_data["is_two_marker"] == "Yes")
							{
								$temp_exam_mark_1 = $course_classes->student_grade_data[$course_classes->section_id][$temp_grade_type_exam_id][$student_datas->student_uni_id]["exam_marks"];
								$temp_exam_mark_2 = $course_classes->student_grade_data[$course_classes->section_id][$temp_grade_type_exam_id][$student_datas->student_uni_id]["exam_marks_2"];
								$temp_exam_mark_3 = $course_classes->student_grade_data[$course_classes->section_id][$temp_grade_type_exam_id][$student_datas->student_uni_id]["exam_marks_3"];
								if(abs($temp_exam_mark_1-$temp_exam_mark_2) >= $grade_type_exam_data["two_mark_difference"])
								{
									if($temp_exam_mark_3 !== "" && $temp_exam_mark_3 !== "3rd")
									{
										$arrMarkerVal = array();
										$arrMarkerVal = array($temp_exam_mark_1,$temp_exam_mark_2,$temp_exam_mark_3);
										
										rsort($arrMarkerVal);
										$temp_exam_mark = ($arrMarkerVal[0]+$arrMarkerVal[1])/2;
									}
								}
								else
								{
									if($temp_exam_mark_1 > 0 || $temp_exam_mark_2 > 0)
										$temp_exam_mark = ($temp_exam_mark_1+$temp_exam_mark_2)/2;
								}
							}
							else
							{
								$temp_exam_mark = $course_classes->student_grade_data[$course_classes->section_id][$temp_grade_type_exam_id][$student_datas->student_uni_id]["exam_marks"];
							}	
						}
							if($temp_exam_mark > 0)
							{
								$temp_exam_mark = round($temp_exam_mark,1);
								$temp_total_exam_mark += $temp_exam_mark;
							}
						}	
						$temp_percentage = round(($temp_total_exam_mark*$temp_grade_type_data["total_percentage"])/$temp_grade_type_data["total_markes"],2);
						$total_100_percentage += $temp_percentage;	
					}	
				}
				
				$total_exam_mark = 0;
				$total_percentage = 0;
				$k = 1;
				$exam_status = "N/A";
				$grade_status_combination = "";
				foreach ($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data)
				{
					$grade_entry_combination = 'grade['.$course_classes->section_id."_".$student_datas->student_uni_id."_".$grade_type_exam_id.']';
					$grade_examwisestatus_combination = 'grade_status['.$course_classes->section_id."_".$student_datas->student_uni_id."_".$grade_type_exam_id.']';
					$grade_status_combination = 'grade_status['.$course_classes->section_id."_".$student_datas->student_uni_id."_".$grade_type_id.']';
						
					$absent_hours = "";
					if(isset($course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]) && 
						isset($course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["exam_marks"]))
					{
						$exam_mark = $course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["exam_marks"];
						$exam_mark_2 = $course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["exam_marks_2"];
						$exam_mark_3 = $course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["exam_marks_3"];
						$exam_status = $course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["exam_status"];
						$exam_mark = round($exam_mark,1);
						if($exam_mark_2 !== "" && $exam_mark_2 !== NULL)
						{
							$exam_mark_2 = round($exam_mark_2,1);
						}	
						$bgcolortd = "";
						$bgcolortd_status_cheat = "";
						
						if($exam_status == "Cheating")
							$bgcolortd_status_cheat = ' bgcolor="#8AC5FF" ';
							
						if($exam_mark_3 != "")
							$exam_mark_3 = round($exam_mark_3,1);
						else
						{
							$exam_mark_3 = "3rd";	
						}
						if($grade_type_exam_data["is_two_marker"] == "Yes")
						{
							if(abs($exam_mark-$exam_mark_2) >= $grade_type_exam_data["two_mark_difference"] && $exam_mark_2 !== "" && $exam_mark_2 !== NULL)
							{
								if($exam_mark_3 !== "" && $exam_mark_3 !== "3rd")
								{
									$arrMarkerVal = array();
									$arrMarkerVal = array($exam_mark,$exam_mark_2,$exam_mark_3);
									
									rsort($arrMarkerVal);
									
									$total_exam_mark += ($arrMarkerVal[0]+$arrMarkerVal[1])/2;
									
									$percentage = ((($arrMarkerVal[0]+$arrMarkerVal[1])/2)*$grade_type_exam_data["exam_percentage"])/$grade_type_exam_data["exam_marks"];	
								}
								else
								{
									$bgcolortd = ' bgcolor="#FF6666" ';
									$percentage = "3rd";
								}
							}
							else
							{
								$exam_mark_3 = "";
								if($exam_mark > 0 || $exam_mark_2 > 0)
									$total_exam_mark += ($exam_mark+$exam_mark_2)/2;
									
								$percentage = ((($exam_mark+$exam_mark_2)/2)*$grade_type_exam_data["exam_percentage"])/$grade_type_exam_data["exam_marks"];		
							}
						}
						else
						{
							$total_exam_mark += $exam_mark;
							if($grade_type_exam_data["exam_marks"])
								$percentage = ($exam_mark*$grade_type_exam_data["exam_percentage"])/$grade_type_exam_data["exam_marks"];
						}
						
						$total_percentage += $percentage;
						
					if($grade_type_data["attendance_type"] == "examwise")
					{
				?>	
					<td align="center">
						<?php 
						$arr_exam_status = array("Present"=>"Present","Absent"=>"Absent");
						echo $exam_status."&nbsp;";
					?>	
					</td>
					<?php
					}
					if($grade_type_exam_data["is_two_marker"] == "Yes")
					{
					?>
						<td align="center">
					<?php
						echo $exam_mark."&nbsp;";
					?>	
						</td>
						<td align="center">
					<?php
						echo $exam_mark_2."&nbsp;";
					?>	
						</td>	
						<td align="center">
					<?php
						echo $exam_mark_3."&nbsp;";
					?>		
						</td>
						<td align="center" <?php echo $bgcolortd;?>>
						<?php
						if($exam_mark_2 !== "" && $exam_mark_2 !== NULL)
						{						
							if(abs($exam_mark-$exam_mark_2) >= $grade_type_exam_data["two_mark_difference"])
							{
								if($exam_mark_3 !== "" && $exam_mark_3 !== "3rd")
								{
									$arrMarkerVal = array();
									$arrMarkerVal = array($exam_mark,$exam_mark_2,$exam_mark_3);
									
									rsort($arrMarkerVal);
									
									echo round(($arrMarkerVal[0]+$arrMarkerVal[1])/2,1);
								}
								else	
									echo "3rd";	
							}
							else
							{
								if($exam_mark > 0 || $exam_mark_2 > 0)
									echo round(($exam_mark+$exam_mark_2)/2,1);
							}
						}	
							echo "&nbsp;";
						?>
						</td>
						<?php
						if($grade_type_exam_data["is_show_percentage"] == "Yes")
						{
						?>
						<td align="center">
						<?php echo round($percentage,2);?>
						</td>
						<?php
						}
					}
					else 
					{
					?>
					<td align="center">
					<?php
						echo $exam_mark."&nbsp;";
					?>
					</td>
					<?php
					if($grade_type_exam_data["is_show_percentage"] == "Yes" && $iscolspan == 1)
					{
					?>
					<td align="center">
					<?php echo round($percentage,2);?>
					</td>
					<?php
					}
					}
					?>
				<?php		
					}
					else 
					{
						$percentage = 0;
						if($grade_type_data["attendance_type"] == "examwise")
						{
					?>	
						<td align="center">
							<?php 
							$arr_exam_status = array("Present"=>"Present","Absent"=>"Absent");
								echo $exam_status."&nbsp;";
						?>	
						</td>
						<?php
						}
						if($grade_type_exam_data["is_two_marker"] == "Yes")
						{
						?>
							<td align="center">
						<?php
							echo "&nbsp;";
						?>	
							</td>
							<td align="center">
						<?php
							echo "&nbsp;";
						?>	
							</td>	
							<td align="center">
						<?php
							echo "&nbsp;";
						?>		
							</td>
							<td align="center" <?php echo $bgcolortd;?>>
							<?php
							if($exam_mark_2 !== "" && $exam_mark_2 !== NULL)
							{						
								if(abs($exam_mark-$exam_mark_2) >= $grade_type_exam_data["two_mark_difference"])
								{
									if($exam_mark_3 !== "" && $exam_mark_3 !== "3rd")
									{
										$arrMarkerVal = array();
										$arrMarkerVal = array($exam_mark,$exam_mark_2,$exam_mark_3);
										
										rsort($arrMarkerVal);
										
										echo round(($arrMarkerVal[0]+$arrMarkerVal[1])/2,1);
									}
									else	
										echo "3rd";	
								}
								else
								{
									if($exam_mark > 0 || $exam_mark_2 > 0)
										echo "&nbsp;";
								}
							}	
								echo "&nbsp;";
							?>
							</td>
						<?php
						}
						else
						{
						?>
						<td align="center">&nbsp;
						
						</td>
						<?php
						}
						if($grade_type_exam_data["is_show_percentage"] == "Yes")
						{
						?>
						<td align="center">
						<?php echo round($percentage,2);?>
						</td>
						<?php
						}
					}
					$k++;
				}
				
				if($grade_type_data["attendance_type"] == "common")
				{
				?>
				<td align="center">
					<?php 
					$arr_exam_status = array("Present"=>"Present","Absent"=>"Absent");
						echo $exam_status."&nbsp;";
				?>	
				</td>
				<?php
				}
				if($grade_type_data["show_total_marks"] == "Yes")
				{
				?>
				<td><?php echo round($total_exam_mark,1); ?></td>
				<?php
				}
				if($grade_type_data["show_grade_range"] == "Y")
				{
					$range_name = "N/A";
					$range_total_marks = round($total_exam_mark,1);
					
					if(is_array($arrGradeRange) && count($arrGradeRange))
					{
						foreach($arrGradeRange AS $rowrange)
						{
							if($range_total_marks >= $rowrange["grade_min_range"] && $range_total_marks <= $rowrange["grade_max_range"])
								$range_name = $rowrange["grade_name"];		
						}
					}
				?>
					<td><?php echo $range_name;?></td>
				<?php
				}
				if($grade_type_data["show_total_per"] == "Yes")
				{
				?>
				<td><?php echo round(($total_exam_mark*$grade_type_data["total_percentage"])/$grade_type_data["total_markes"],2); ?></td>
				<?php
				}
				?>
			</tr>
			<?php
			$l++;
			
			$arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["student_uni_id"] = $student_datas->student_uni_id;
			$arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["first_name"] = $student_datas->first_name;
			$arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["total_marks"] = round($total_exam_mark,1);
			$arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["total_perc"] = round(($total_exam_mark*$grade_type_data["total_percentage"])/$grade_type_data["total_markes"],2);
			$arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["total_100_perc"] = round($total_100_percentage,2);
			
			}
			?>
		</table>    
		<?php
		$j++;
		}
		else
		{
		?>
		No Student found in this class.Please ask to administrator.
		<?php
		}
}
else
{
	echo "<div align='center'><b>No Exam Found</b></div>";
}
		?>
		</div>
		<?php
		if(isset($student_grade_report_log[$grade_type_id]))
		{
		?>
			<br>
			<h2 align="center">Student Grade Report Log</h2>
			<table align="center" border="1" cellpadding="5" cellspacing="1" width="85%">
				<tr>
					<th width="10%">Changed By</th>
					<th width="4%">Changed <br />Date</th>
					<th width="4%">Exam Type</th>
					<th width="4%">Changed Field(s)</th>
					<th width="4%">OLD</th>
					<th width="4%">Changed</th>
				</tr>
				<?php
				$l = 0;
				foreach($student_grade_report_log[$grade_type_id] as $contkey=>$rowdataLog)
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
		<?php
		}
		?>	
	</div>
<?php
}
if($show_total_tab == "Yes")
{
?>	
	<div id="tabs_<?php echo $tabcnt;?>_1111">
		<div style="" id="col3">
		<table border="1" cellpadding="5" cellspacing="0" width="98%">
			<tr>
				<?php
				foreach($grade_type AS $grade_type_id=>$grade_type_data)
				{
					if($grade_type_data["show_total_marks"] == "Yes")
					{
				?>
					<th align="left" valign="top"><?php echo $grade_type_data["grade_type"];?> Marks(<?php echo $grade_type_data["total_markes"];?>)</th>
				<?php
					}
					if($grade_type_data["show_grade_range"] == "Y")
					{
				?>
					<th align="left" valign="top">Range</th>
				<?php
					}
					if($grade_type_data["show_total_per"] == "Yes")
					{
				?>	
					<th align="left" valign="top"><?php echo $grade_type_data["grade_type"];?> %(<?php echo $grade_type_data["total_percentage"];?>)</th>
				<?php
					}
				}
				?>
				<th align="left" valign="top">Total 100%</th>
				<?php
				if($show_grade_range == "Yes")
				{
				?>
				<th align="left" valign="top">Range</th>
				<?php
				}
				?>
			</tr>
			<?php
			$trcnttotal = 1;
			if(isset($course_classes->student))
			{
			foreach ($course_classes->student as $student_datas)
			{
				$trbg = "";
				if($trcnttotal%2 == 0)
				{
					 $trbg = 'style="background-color:#ddd"';
				}
				$trcnttotal++;
			?>
				<tr <?php echo $trbg;?>>	
					<?php
					$total_100_per = 0;
					foreach($grade_type AS $grade_type_id=>$grade_type_data)
					{
						if(isset($arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]))
						{
							if($grade_type_data["show_total_marks"] == "Yes")
							{
					?>
								<td><?php echo $arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["total_marks"];?></td>
					<?php
							}
							if($grade_type_data["show_grade_range"] == "Y")
							{
								$range_name = "N/A";
								$range_total_marks = round($arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["total_marks"],1);
								
								if(is_array($arrGradeRange) && count($arrGradeRange))
								{
									foreach($arrGradeRange AS $rowrange)
									{
										if($range_total_marks >= $rowrange["grade_min_range"] && $range_total_marks <= $rowrange["grade_max_range"])
											$range_name = $rowrange["grade_name"];		
									}
								}
					?>
								<td><?php echo $range_name;?></td>
					<?php
							}
							if($grade_type_data["show_total_per"] == "Yes")
							{		
					?>	
								<td><?php echo $arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["total_perc"];?></td>
					<?php
							}
					
							$total_100_per = $arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["total_100_perc"];
						}
						else 
						{
							if($grade_type_data["show_total_marks"] == "Yes")
							{
					?>
								<td>0</td>
					<?php
							}
							if($grade_type_data["show_grade_range"] == "Y")
							{
					?>
								<td>N/A</td>
					<?php
							}
							if($grade_type_data["show_total_per"] == "Yes")
							{
					?>	
								<td>0</td>
					<?php
							}
						}		
					}
					?>
					<td><?php echo $total_100_per;?></td>
					<?php
					if($show_grade_range == "Yes")
					{
						$range_name = "N/A";
						$range_total_marks = $total_100_per;
						
						if(is_array($arrGradeRange) && count($arrGradeRange))
						{
							foreach($arrGradeRange AS $rowrange)
							{
								if($range_total_marks >= $rowrange["grade_min_range"] && $range_total_marks <= $rowrange["grade_max_range"])
									$range_name = $rowrange["grade_name"];		
							}
						}
					?>
					<td><?php echo $range_name;?></td>
					<?php
					}
					?>
				</tr>	
			<?php
			}
			}
			?>
		</table>	
		</div>
	</div>
	<?php
	}
	?>
</div>

<?php	
$tabcnt++;
endforeach;
}
?>
</td>
</tr>
</table>
<br/><br/>
<?php
if(!empty($section_log_data)) {
?>
<table border="1" width="100%">
<tr><th colspan="3">Section updated log</th></tr>
<tr><th width="50px">Section</th><th width="250px">Update By</th><th width="65px">Date</th><th>Reason</th></tr>
<?php
	foreach($section_log_data as $data1) { ?>
		<tr><td><?php echo addslashes($data1["section_title"]); ?></td>
		<td><?php echo addslashes($data1["first_name"]); ?></td>						
		<td><?php echo $data1["change_date"]; ?></td>
		<td width=\"200px\"><?php echo addslashes(str_replace("'"," ",str_replace("\n"," ",str_replace("\r\n"," ",$data1["reason"])))) ?></td></tr>
<?php
	}
?>
</table>
<?php
}
?>
</div>