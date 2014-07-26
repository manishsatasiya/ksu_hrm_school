<style>



.student-info { background:#ecf0f3; font-family:Arial, Helvetica, sans-serif;}



.student-info .student_info_header { padding:5px;}



.student-info .student_info_header span { margin-right:15px;}

.student-info .attend-box,

.student-info .grades-box { padding:15px 10px; border-top:1px dotted #000000; overflow:hidden;}

.student-info h3 {

	float:left;

	font-size:12px;

	margin:0px;

	padding-right:1%;

	line-height:1;

	width:11%;

	text-transform:uppercase;

	font-family:Arial, Helvetica, sans-serif;

	font-weight:normal;

	}

.student-info h3 i { font-size:30px; margin-top:10px;}

.student-info .attend-tbl { float:left; width:88%; background:#9f9f9f;}

.student-info table {}

.student-info table tr { text-align:center; font-size:12px; background:none !important;}

.student-info table tr td { padding:8px 1px !important; border:none;}

.student-info .attend-tbl table tr + tr td { border-top:1px solid #323232;}

.student-info .attend-tbl tabel tr:first-child  {background:#9f9f9f !important;} 

.student-info .attend-tbl table tr td:nth-child(odd) {background: #ecf0f3;}

.student-info .attend-tbl table tr td:first-child { border-right:1px solid #323232; background:#9f9f9f;}

.student-info table td:last-child { border-radius:0px;}

.student-info .grades-tbl { float:left; width:88%;}

.student-info .grades-tbl table { border:1px solid #7a7a7a;}

.student-info .grades-tbl table tr td  { border-left:1px solid #7a7a7a;}

.student-info .grades-tbl table tr + tr td { border-top:1px solid #7a7a7a;}

.student-info .grades-tbl ul { margin:0px; padding:0px; list-style-type:none; background:#d1dadf; overflow:hidden; border-radius:5px 5px 0 0; border:0px;}

.student-info .grades-tbl ul li { text-align:center;}

.student-info .grades-tbl ul li.active,

.student-info .grades-tbl ul li:hover { background:#ffffff; border-radius:5px 5px 0 0;}





.student-info .grades-tbl a { text-decoration:none; color:#000000;}



.student-info .tab-content {



	padding:15px 10px;



	background:#ffffff;



	}



</style>



<script src="<?php print base_url(); ?>assets/js/tabs_accordian.js" type="text/javascript"></script>



<div class="student-info">

<?php

if($attendance)

{

?>

	
	<?php	

	$flag = 1;

	foreach($attendance as $key=>$arrData)

	{

		if($key == "student_info")

		{
	?>
    <div class="student_info_header">

		<?php	foreach($arrData AS $key_info=>$val)

			{

			?>	

				<span><?php echo $key_info?>: <strong><?php echo $val?></strong></span>

			<?php	

			}
?>
</div>
<?php
		}	

		?>

    



    

		<?php

		if($key == "weeks")

		{

		?>

		<div class="attend-box">

    	<h3>Attendance <br /><i class="fa fa-th-list"></i></h3>



        <div class="attend-tbl">

		<table width="100%" cellspacing="0" cellpadding="0" border="0">

        	<tbody>

            	<tr>

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

            </tbody>

        </table>

		</div>

		</div>

		<?php	

		}

	}

}

else

{

	echo "No attendance data found.";

}

?>



    

    <div class="grades-box">

    	<h3>Grades <br /><i class="fa fa-list-ol"></i></h3>

        <div class="grades-tbl">

        <?php

		$j = 1;
		$active="";
		$tabcnt = 0;

		if (isset($course_class)) {



		foreach ($course_class->result() as $course_classes):

		$course_class_week = $course_classes->school_week;

		$max_hours = $course_classes->max_hours;

		?>

        <div class="tabbable">

            <ul class="nav nav-tabs" id="tab-01">

				<?php
$acet=0;
				foreach($grade_type AS $grade_type_id=>$grade_type_data)

				{
				$active="";
if ($acet==0){ $active="active"; }
				?>

					<li class="<?php echo $active; ?>"><a href="#tabs_<?php echo $tabcnt;?>_<?php echo $grade_type_id;?>_<?php echo $studentuniid;?>"><?php echo $grade_type_data["grade_type"];?></a></li>

				<?php
$acet++;
				}

				if($show_total_tab == "Yes")

				{

				?>

				<li><a href="#tabs_<?php echo $tabcnt;?>_1111_<?php echo $studentuniid;?>"><?php echo $this->lang->line('totals'); ?></a></li>

				<?php

				}

				?>

            </ul>

            <div class="tab-content">

				<?php

				$arrTotals_Tab = array();
$acet=0;
				foreach($grade_type AS $grade_type_id=>$grade_type_data)

				{
				$active="";
if ($acet==0){ $active="active"; }
				?>	

					<div class="tab-pane <?php echo $active; ?>" id="tabs_<?php echo $tabcnt;?>_<?php echo $grade_type_id;?>_<?php echo $studentuniid;?>">

					<?php
$acet++;
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

									<td align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('attendance'); ?></td>

									<?php

									}

									?>

									<td <?php echo $colspan;?> valign="top"><?php echo $grade_type_exam_data["exam_type_name"];if($iscolspan == 0){echo '<br>('.$grade_type_exam_data["exam_marks"].')';} ?></td>

										

									<?php 

									}

									if($grade_type_data["attendance_type"] == "common")

									{

									?>

									<td align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('attendance'); ?></td>

									<?php

									}

									if($grade_type_data["show_total_marks"] == "Yes")

									{

									?>

									<td align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('total_marks'); ?>(<?php echo $grade_type_data["total_markes"];?>)</td>

									<?php

									}

									if($grade_type_data["show_grade_range"] == "Y")

									{

									?>

									<td align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('range'); ?></td>

									<?php

									}

									if($grade_type_data["show_total_per"] == "Yes")

									{

									?>	

									<td align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('total'); ?> %(<?php echo $grade_type_data["total_percentage"];?>)</td>

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

									<td><?php echo $this->lang->line('Marks'); ?> 1 </td>

									<td><?php echo $this->lang->line('Marks'); ?> 2 </td>

									<td><?php echo $this->lang->line('Marks'); ?> 3(<?php echo $this->lang->line('optional'); ?>) </td>

									<td><?php echo $this->lang->line('Marks'); ?>(<?php echo $grade_type_exam_data["exam_marks"]; ?>) </td>

									<?php

									if($grade_type_exam_data["is_show_percentage"] == "Yes")

									{

									?>

									<td><?php echo $grade_type_exam_data["exam_percentage"]; ?>% </td>

									<?php

									}

									}

									else

									{

									?>

									<td>Marks(<?php echo $grade_type_exam_data["exam_marks"]; ?>) </td>

									<?php

										if($grade_type_exam_data["is_show_percentage"] == "Yes")

										{

									?>

										<td><?php echo $grade_type_exam_data["exam_percentage"]; ?>% </td>

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

				}

				if($show_total_tab == "Yes")

				{

				?>	

					<div id="tabs_<?php echo $tabcnt;?>_1111_<?php echo $studentuniid;?>">

						<table border="1" cellpadding="5" cellspacing="0" width="98%">

							<tr>

								<?php

								foreach($grade_type AS $grade_type_id=>$grade_type_data)

								{

									if($grade_type_data["show_total_marks"] == "Yes")

									{

								?>

									<td align="left" valign="top"><?php echo $grade_type_data["grade_type"];?> Marks(<?php echo $grade_type_data["total_markes"];?>)</td>

								<?php

									}

									if($grade_type_data["show_grade_range"] == "Y")

									{

								?>

									<td align="left" valign="top">Range</td>

								<?php

									}

									if($grade_type_data["show_total_per"] == "Yes")

									{

								?>	

									<td align="left" valign="top"><?php echo $grade_type_data["grade_type"];?> %(<?php echo $grade_type_data["total_percentage"];?>)</td>

								<?php

									}

								}

								?>

								<td align="left" valign="top">Total 100%</td>

								<?php

								if($show_grade_range == "Yes")

								{

								?>

								<td align="left" valign="top">Range</td>

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

					<?php

					}

					?>

			</div>

		 </div>

				<?php	

				$tabcnt++;

				endforeach;

				}

				?>

       

    </div>

</div>