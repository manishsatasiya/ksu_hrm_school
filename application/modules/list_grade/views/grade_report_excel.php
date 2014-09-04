<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=GradeRreport.xls");
header("Pragma: no-cache");
header("Expires: 0");

	$hide_total = 0;
	$j = 1;
	$tabcnt = 0;
    if (isset($course_class)) 
    {
	$show_firstime = 0;
    foreach ($course_class->result() as $course_classes):
	$tabcnt = 0;
	
	foreach($grade_type AS $grade_type_id=>$grade_type_data)
	{
		if($tabcnt != $search_gradetype)
		{
			$tabcnt++;
			continue;
		}
	if(isset($grade_type_exam[$grade_type_id]))
	{
			$arr_grade_exam = $grade_type_exam[$grade_type_id];
			if (isset($course_classes->student)) 
			{
				$iscolspan = 0;
				$colspan = "";
				$rowspan = "";
				
				foreach ($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data){
				if($grade_type_exam_data["exam_percentage"] > 0 || $grade_type_exam_data["is_two_marker"] == "Yes")
				{
					$iscolspan = 1;
					$colspan = 'colspan="2"';
					
					if($grade_type_exam_data["is_two_marker"] == "Yes")
						$colspan = 'colspan="5"';
					
					$rowspan = 'rowspan="2"';
				}
				}
				
			if($show_firstime == 0)
			{	
			?>
			<table border="1" cellpadding="5" cellspacing="0" width="1055px">
				<tr>
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('sub_att_p_field_stuid'); ?></th>
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('sub_att_p_sea_section'); ?></th>
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('sub_att_p_field_fullname'); ?></th>
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('sub_att_p_track'); ?></th>
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('campus'); ?></th>
					<?php
					foreach ($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data){
					if($grade_type_exam_data["exam_percentage"] > 0 || $grade_type_exam_data["is_two_marker"] == "Yes")
					{
						$iscolspan = 1;
						$colspan = 'colspan="2"';
						
						if($grade_type_exam_data["is_two_marker"] == "Yes")
							$colspan = 'colspan="5"';
					}
					
					if($grade_type_data["verification_type"] == "examwise" && $grade_type_data["is_show_verified"] == "Yes")
					{
					?>
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('sub_att_p_field_verified'); ?></th>
					<th align="left" <?php echo $rowspan;?> valign="top">Lead Verified</th>
					<th align="left" <?php echo $rowspan;?> valign="top">Verified By</th>
					<th align="left" <?php echo $rowspan;?> valign="top">Verified Date</th>
					<th align="left" <?php echo $rowspan;?> valign="top">Submitted By</th>
					<th align="left" <?php echo $rowspan;?> valign="top">Submitted Date</th>
					<?php
					}
					if($grade_type_data["attendance_type"] == "examwise")
					{
					?>
					<th align="left" <?php echo $rowspan;?> valign="top">Attendance</th>
					<?php
					}
					?>
					<th <?php echo $colspan;?> valign="top"><?php echo $grade_type_exam_data["exam_type_name"];if($iscolspan == 0){echo '<br>('.$grade_type_exam_data["exam_marks"].')';} ?></th>
						
					<?php 
					}
					if($grade_type_data["verification_type"] == "common" && $grade_type_data["is_show_verified"] == "Yes")
					{
					?>
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('sub_att_p_field_verified'); ?></th>
					<th align="left" <?php echo $rowspan;?> valign="top">Lead Verified</th>
					<th align="left" <?php echo $rowspan;?> valign="top">Verified By</th>
					<th align="left" <?php echo $rowspan;?> valign="top">Verified Date</th>
					<th align="left" <?php echo $rowspan;?> valign="top">Submitted By</th>
					<th align="left" <?php echo $rowspan;?> valign="top">Submitted Date</th>
					<?php
					} ?>
					<?php
					if($grade_type_data["attendance_type"] == "common")
					{
					?>
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('sub_att_p_field_attendance'); ?></th>
					<?php
					}
					if($hide_total == 0)
					{
					?>
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('sub_att_p_field_total_marks'); ?>(<?php echo $grade_type_data["total_markes"];?>)</th>
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('sub_att_p_field_total'); ?> %(<?php echo $grade_type_data["total_percentage"];?>)</th>
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('sub_att_p_field_total'); ?> %(100)</th>
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
					foreach ($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data)
					{
					if($grade_type_exam_data["is_two_marker"] == "Yes")
					{
					?>
					<th><?php echo $this->lang->line('sub_att_p_field_marks'); ?> 1 </th>
					<th><?php echo $this->lang->line('sub_att_p_field_marks'); ?> 2 </th>
					<th><?php echo $this->lang->line('sub_att_p_field_marks'); ?> 3(<?php echo $this->lang->line('sub_att_p_field_optional'); ?>) </th>
					<th><?php echo $this->lang->line('sub_att_p_field_marks'); ?>(<?php echo $grade_type_exam_data["exam_marks"]; ?>) </th>
					<th><?php echo $grade_type_exam_data["exam_percentage"]; ?>% </th>
					<?php
					}
					else
					{
					?>
					<th><?php echo $this->lang->line('sub_att_p_field_marks'); ?>(<?php echo $grade_type_exam_data["exam_marks"]; ?>) </th>
					<th><?php echo $grade_type_exam_data["exam_percentage"]; ?>% </th>
					<?php
					}
					?>		
					<?php 
					}
					?>
				
				</tr>
			<?php
				}
			}
				$l = 0;
				foreach ($course_classes->student as $student_datas)
				{
					$editable = 0;
					$total_percentage = 0;
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
							//echo $temp_exam_mark."<br>";
								if($temp_exam_mark > 0)
								{
									$temp_exam_mark = round($temp_exam_mark,1);
									//echo $temp_exam_mark."<br>";
									$temp_total_exam_mark += $temp_exam_mark;
								}
							}	
							//echo $temp_total_exam_mark."<br>";
							$temp_percentage = round(($temp_total_exam_mark*$temp_grade_type_data["total_percentage"])/$temp_grade_type_data["total_markes"],2);
							//echo $temp_percentage."<br>";
							$total_percentage += $temp_percentage;	
						}	
					}
					//echo $total_percentage."<br>";exit;
			?>		
				<tr>
					<td><?php echo $student_datas->student_uni_id; ?></td>
					<td><?php echo $student_datas->section_title; ?></td>
					<td><?php echo $student_datas->first_name; ?></td>
					<td><?php echo $student_datas->track; ?></td>
					<td><?php echo $student_datas->campus; ?></td>
					<?php 
					$total_exam_mark = 0;
					
					$k = 1;
					$grade_status_combination = "";
					foreach ($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data)
					{
						$exam_status = "";
						$verified = "";
						$lead_verified = "";
						$verified_by = "";
						$verified_date = "";
						$created_by = "";
						$submitted_date = "";
						
						$grade_entry_combination_3 = 'grade3['.$course_classes->section_id."_".$student_datas->student_uni_id."_".$grade_type_exam_id.']';
						$grade_entry_combination_2 = 'grade2['.$course_classes->section_id."_".$student_datas->student_uni_id."_".$grade_type_exam_id.']';
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
							$verified = $course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["verified"];
							$lead_verified = $course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["lead_verified"];
							$verified_by = $course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["verified_by_name"];
							$verified_date = $course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["verified_date"];
							$created_by = $course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["created_by_name"];
							$submitted_date = $course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["created_date"];
					if(isset($course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["editable"]))
							$editable = $course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["editable"];
							$exam_mark = round($exam_mark,1);
							if($exam_mark_2 !== "" && $exam_mark_2 !== NULL)
							{
								$exam_mark_2 = round($exam_mark_2,1);
							}	
							$bgcolortd = "";
							$bgcolortd_status_cheat = "";
							
							if($exam_status == "Cheating")
								$bgcolortd_status_cheat = ' bgcolor="#8AC5FF" ';
								
							if($exam_mark_3 !== "" && $exam_mark_3 !== NULL)
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
						
						if($grade_type_data["verification_type"] == "examwise" && $grade_type_data["is_show_verified"] == "Yes")
						{
					?>	
						<td align="center">
						<?php 
							echo $verified;
						?>	
						</td>
						<td align="center"><?php echo $lead_verified; ?></td>
						<td align="center"><?php echo $verified_by; ?></td>
						<td align="center"><?php echo $verified_date; ?></td>
						<td align="center"><?php echo $created_by; ?></td>
						<td align="center"><?php echo $submitted_date; ?></td>
						<?php
						}
						
						if($grade_type_data["attendance_type"] == "examwise")
						{
					?>	
						<td align="center" <?php echo $bgcolortd_status_cheat;?>>
						<?php 
							$arr_exam_status = array("Present"=>"Present","Absent"=>"Absent");
							echo $exam_status;
						?>	
						</td>
						<?php
						}
						if($grade_type_exam_data["is_two_marker"] == "Yes")
						{
						?>
							<td align="center">
							<?php
								echo $exam_mark;
							?>	
							</td>
							<td align="center">
							<?php
								echo $exam_mark_2;
							?>	
							</td>	
							<td align="center">
							<?php
								if($exam_mark_2 !== "" && $exam_mark_2 !== NULL)
									echo $exam_mark_3;
								else 
									echo "";
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
								echo "";
							?>
							</td>
							<?php
							if($iscolspan == 1)
							{
							?>
							<td align="center" <?php echo $bgcolortd;?>>
							<?php 
							if($exam_mark_2 !== "" && $exam_mark_2 !== NULL)
							{
								if($percentage != "" && $percentage != "3rd")
								{
									echo round($percentage,2);
								}
								else
								{
									echo $percentage;	
								}	
							}
							echo "";
							?>
							</td>
						<?php	
							}
						}
						else
						{
						?>
								<td align="center">
								<?php
									echo $exam_mark;
								?>
								</td>
								<?php
								if($iscolspan == 1)
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
							if($grade_type_data["verification_type"] == "examwise" && $grade_type_data["is_show_verified"] == "Yes")
							{
						?>	
							<td align="center">
								<?php 
								echo $verified;
							?>	
							</td>
							<td align="center"><?php echo $lead_verified; ?></td>
							<td align="center"><?php echo $verified_by; ?></td>
							<td align="center"><?php echo $verified_date; ?></td>
							<td align="center"><?php echo $created_by; ?></td>
							<td align="center"><?php echo $submitted_date; ?></td>
							<?php
							}
							if($grade_type_data["attendance_type"] == "examwise")
							{
						?>	
							<td align="center">
								<?php 
								$arr_exam_status = array("Present"=>"Present","Absent"=>"Absent");
								echo $exam_status;
							?>	
							</td>
							<?php
							}
							if($grade_type_exam_data["is_two_marker"] == "Yes")
							{
							?>
								<td align="center">
								<?php
								print "N/A";
								?>
								</td>
								<td align="center">
								<?php
								print "N/A";
								?>
								</td>
							
								<td align="center">
							<?php	
									echo "";
							?>
								</td>
							
							<td align="center">
								<?php
								echo "";
								?>
								</td>
								<?php
								if($iscolspan == 1)
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
								<td align="center">N/A</td>
								<?php
								if($iscolspan == 1)
								{
								?>
								<td align="center">
								<?php echo round($percentage,2);?>
								</td>
								<?php
								}
							}
						}
						$k++;
					}
					if($grade_type_data["verification_type"] == "common" && $grade_type_data["is_show_verified"] == "Yes")
					{
					?>
					<td align="center">
						<?php 
						echo $verified;
					?>	
					</td>
					<td align="center"><?php echo $lead_verified; ?></td>
					<td align="center"><?php echo $verified_by; ?></td>
					<td align="center"><?php echo $verified_date; ?></td>
					<td align="center"><?php echo $created_by; ?></td>
					<td align="center"><?php echo $submitted_date; ?></td>
					<?php
					} ?>
					
					
					<?php
					if($grade_type_data["attendance_type"] == "common")
					{
					?>
					<td align="center">
						<?php 
						$arr_exam_status = array("Present"=>"Present","Absent"=>"Absent");
						echo $exam_status;
					?>	
					</td>
					<?php
					}
					if($hide_total == 0)
					{
					?>
					<td><?php echo round($total_exam_mark,1); ?></td>
					<td><?php echo round(($total_exam_mark*$grade_type_data["total_percentage"])/$grade_type_data["total_markes"],2); ?></td>
					<td><?php echo round($total_percentage,2); ?></td>
					<?php
					}
					?>
				</tr>
				<?php
				$l++;
				}
			$j++;
			}
			/*else
			{
			?>
			No Student found in this class.Please ask to administrator.
			<?php
			}*/
	}
	else
	{
		echo "No Exam Found";
	}
			?>
	<?php
	$tabcnt++;
	$show_firstime = 1;
	}
	?>	
	
	<?php	
	
    endforeach;
    }
    ?>
    </table> 