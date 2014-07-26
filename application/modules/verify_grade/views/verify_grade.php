<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$hide_total = 0;
$hide_final_total = 0;
if(defined("HIDE_ALL_TOTAL_IN_GRADE_FOR_TEACHER_ONLY") && constant("HIDE_ALL_TOTAL_IN_GRADE_FOR_TEACHER_ONLY") == 1 && $this->session->userdata('role_id') == '3')
	$hide_total = 1;
if(defined("HIDE_FINAL_TOTAL_IN_GRADE_FOR_TEACHER_ONLY") && constant("HIDE_FINAL_TOTAL_IN_GRADE_FOR_TEACHER_ONLY") == "yes" && $this->session->userdata('role_id') == '3')
	$hide_final_total = 1;	
$hide_final_total = 1;	
?>
<style>
.grade-rpt .panel-body ul { margin:0px; padding:0px; list-style-type:none; background:#d1dadf; overflow:hidden; border-radius:5px 5px 0 0; border:0px;}
.grade-rpt .panel-body ul li { text-align:center;}
.grade-rpt .panel-body ul li.active,
.grade-rpt .panel-body ul li:hover { background:#ECF0F2; border-radius:5px 5px 0 0;}
.grade-rpt .panel-body ul li.active a,
.grade-rpt .panel-body ul li:hover a { background:#ECF0F2; border-radius:5px 5px 0 0;}
.grade-rpt .panel-body a { text-decoration:none; color:#000000;}
.grade-rpt .panel-body .tab-content { margin:0px;}
.grade-rpt .panel-body .tab-content > .active,
.grade-rpt .panel-body .pill-content > .active {
    padding:0px; overflow:auto; background:#ECF0F2; 
}
.grade-rpt .panel-body .submit-btn { text-align:center; padding:10px 15px 15px;}
</style>

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
<script>
	$(document).ready(function() {	
		<?php 
		$j = 1;
	    if (isset($course_class)) {
	    	foreach ($course_class->result() as $course_classes){
		?>
			
			$("#add_grade_form<?php echo $j;?>").validate( {
				submitHandler: function(form) {
					var flag = false;
					$('#add_grade_form<?php echo $j;?> input[type="text"].txtmust').each(function(){
						if(this.value == '' || this.value < 0){	
							flag = true;	
						}
					});
					
					if(flag == true){
						alert("Please enter numeric or decimal value");
					}else{
						form.submit();
					}
				}
			});
		<?php  
				$j++;
	    	}
		}
	    ?>
	});		
	</script>	
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
        	<div class="modal-header">
              <h2>Loading....</h2>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
            </div>
            <div class="modal-body"><div style="text-align:center;"><i class="fa fa-spinner fa fa-6x fa-spin" id="animate-icon"></i></div></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
		 </div>	
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->		    
<div class="grade-rpt">
    
    <div id="admin">
        <?php $this->load->view('generic/flash_error'); ?>
        <label class="error" id="chkerror" style="display:none;"></label>
		<?php
		if($this->session->userdata('role_id') != '3')
		{
		?>
        
        <!-- search box start -->
        <div id="searchbox" class="graderpt_search_box dataTables_wrapper">
    	<div class="row">
        <div class="col-md-12">
				<?php
				print form_open('verify_grade/index/section_id/asc/post/0') ."\r\n";
				?>
        <div class="row form-row">
              <div class="col-md-4">
              <label class="form-label">
			  <?php echo $this->lang->line('sub_att_p_sea_section'); ?>
              </label>
              <?php print form_input(array('name' => 'section_title', 'id' => 'section_title', 'class' => 'admin_input form-control','placeholder'=>$this->lang->line('sub_att_p_sea_section'),'value'=>$this->session->userdata('s_section_title'))); ?>
              </div>
              <div class="col-md-4">
              <label class="form-label">
			  <?php echo $this->lang->line('sub_att_p_sea_class_room'); ?>
              </label>
              <?php print form_input(array('name' => 'class_room_title', 'id' => 'class_room_title', 'class' => 'admin_input form-control','placeholder'=>$this->lang->line('sub_att_p_sea_class_room'),'value'=>$this->session->userdata('s_class_room_title'))); ?>
              </div>
              <div class="col-md-4">
              <label class="form-label">
			  <?php echo $this->lang->line('sub_att_p_sea_student_id'); ?>
              </label>
              	<?php print form_input(array('name' => 'student_id', 'id' => 'student_id', 'class' => 'admin_input form-control','placeholder'=>$this->lang->line('sub_att_p_field_stuid'),'value'=>$this->session->userdata('s_student_id'))); ?>
              </div>
              <div class="col-md-4">
              <label class="form-label">
			  <?php echo $this->lang->line('sub_att_p_sea_without_marks'); ?>
              </label>
                <?php print form_dropdown('without_third_marks',$without_third_marks,$this->session->userdata('s_without_third_marks'),'id="reg_without_third_marks" class="input_text1 qtip_without_third_marks"'); ?>
              </div>
              <div class="col-md-4">
              <label class="form-label">&nbsp;</label>
              	<?php print form_submit(array('name' => 'add_attendance_search_submit', 'id' => 'add_attendance_search_submit', 'value' => 'Search', 'class' => 'input_submit btn btn-success')) ."\r\n";?>
			  </div>
			<?php print form_close() ."\r\n";
            //print "Total rows: ". $total_rows;
            ?> 
        </div>
              <?php
				}
				?>
        </div>
        </div>     
 		</div>
        <!-- search box end -->
        
        <div class="grid simple">
        <div class="grid-title">
        <h4><?php echo $this->lang->line('sub_grade_p_heading'); ?>Submit Grade</h4>
        </div>
        <div class="grid-body">	
<div class="dataTables_wrapper">
   <!--Pagination start -->
    <div class="row">
    	<div class="col-md-12 top-pgination">
    	<div class="dataTables_paginate paging_bootstrap pagination">
		  <?php
        print $this->pagination->create_links();
        ?>
        </div>
        <div class="dataTables_info">
        	<?php print "Total rows: ". $total_rows; ?>
        </div>
        </div>
    </div>    
   <!--Pagination end --> 
   
   <!--accordion-typ1 start-->
      <div class="accordion-typ1">
      <div class="row">
        <div class="col-xs-12">
        <div class="acc-mainttl">
            <div class=" row">
               <span class="col-md-2"><?php echo $this->lang->line('sub_att_p_list_hd_cl_name'); ?></span>
               <span class="col-md-2"><?php echo $this->lang->line('sub_att_p_list_hd_sec'); ?></span>
               <span class="col-md-2"><?php echo $this->lang->line('sub_att_p_list_hd_class_room'); ?></span>
               	<?php
				if($this->session->userdata('role_id') == '1')
				{
				?>
               <span class="col-md-4"><?php echo $this->lang->line('sub_att_p_list_hd_pri_teacher'); ?></span>
               <?php
			}
			?>
               <span class="col-md-2"><?php echo $this->lang->line('sub_att_p_list_hd_tot_stud'); ?></span>
             </div>  
        </div>
        <div class="panel-group" id="accordion" data-toggle="collapse">
        <?php
		$j = 1;
		$tabcnt = 0;
		if (isset($course_class)) {
			
		foreach ($course_class->result() as $course_classes):
		$course_class_week = $course_classes->school_week;
		$max_hours = $course_classes->max_hours;
		?>
        	<div class="panel panel-default">
            <div class="panel-heading">
			  <h4 class="panel-title">
				<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $j; ?>">
                <span class="row">
				   <span class="col-md-2"><?php if($course_classes->course_title){echo $course_classes->course_title;}else{echo '&nbsp;';} ?></span>
                   <span class="col-md-2"><?php if($course_classes->section_title){echo $course_classes->section_title;}else{echo '&nbsp;';} ?></span>
                   <span class="col-md-2"><?php if($course_classes->class_room_title){echo $course_classes->class_room_title;}else{echo '&nbsp;&ndash;&nbsp;';} ?></span>
                   <?php
					if($this->session->userdata('role_id') != '3')
					{
					?>
                   <span class="col-md-4"><?php if($course_classes->first_name){echo $course_classes->first_name;}else{echo '&nbsp;&ndash;&nbsp;';} ?></span>
                   <?php
					}
					?>
                   <span class="col-md-2"><?php if(isset($course_classes->student)){echo count($course_classes->student);}else{echo '0';} ?></span>
                 </span>  
				</a>
			  </h4>
			</div>
            <div id="collapse-<?php echo $j; ?>" class="panel-collapse collapse">
			  <div class="panel-body">
				 <!--tab start-->   
                 <ul class="nav nav-tabs" id="tab-01">
				<?php
				$acet=0;
                foreach($grade_type AS $grade_type_id=>$grade_type_data)
				{
				$active="";
				if ($acet==0)
				{
				$active="active";
				 }
                ?>
                <li class="<?php echo $active; ?>"><a href="#tabs_<?php echo $tabcnt;?>_<?php echo $grade_type_id;?>"><?php echo $grade_type_data["grade_type"];?></a></li>
                <?php
				$acet++;
                }
                if($show_total_tab == "Yes")
                {
                ?>
                <li><a href="#tabs_<?php echo $tabcnt;?>_1111">Totals</a></li>
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
				if ($acet==0)
				{
				$active="active"; 
				}
				?>
          		
            		<div class="tab-pane <?php echo $active; ?>" id="tabs_<?php echo $tabcnt;?>_<?php echo $grade_type_id;?>">
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
				
				print form_open('verify_grade/Verification/', array('id' => 'add_grade_form'.$j, 'class' => 'add_grade_form')) ."\r\n";
			
				foreach ($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data){
				if($grade_type_exam_data["is_show_percentage"] == "Yes" || $grade_type_exam_data["is_two_marker"] == "Yes")
				{
					//$iscolspan = 1;
					//$colspan = 'colspan="2"';
					
					if($grade_type_exam_data["is_two_marker"] == "Yes")
						$colspan = 'colspan="5"';
						
					$rowspan = 'rowspan="2"';
				}
				}
			?>
			<table border="1" cellpadding="5" cellspacing="0" width="1270px" id="chnstyle" class="table table-bordered no-more-tables">
				<tr>
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('sub_att_p_field_stuid'); ?></th>
					<th align="left" <?php echo $rowspan;?> valign="top" width="200px"><?php echo $this->lang->line('sub_att_p_field_fullname'); ?></th>
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
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('sub_att_p_field_attendance'); ?></th>
					<?php
					}
					?>
					<th <?php echo $colspan;?> valign="top"><?php echo $grade_type_exam_data["exam_type_name"];if($iscolspan == 0){echo '<br>('.$grade_type_exam_data["exam_marks"].')';} ?></th>
					<?php
						if($grade_type_data["verification_type"] == "examwise" && $grade_type_data["is_show_verified"] == "Yes")
						{
							if($this->session->userdata('role_id') == '1' || in_array("update_grade",$this->arrAction))
							{
						?>
								<th align="left" <?php echo $rowspan;?> valign="top">Verified</th>
						<?php
							}
							if($this->session->userdata('role_id') == '1' || in_array("lead_update_grade",$this->arrAction))
							{
								if($this->session->userdata('role_id') != '1')
								{
						?>		
								<th align="left" <?php echo $rowspan;?> valign="top">Verified</th>
						<?php
								}
						?>		
								<th align="left" <?php echo $rowspan;?> valign="top">LT.Verify</th>
						<?php
							}
						}
					}
					
					if($grade_type_data["verification_type"] == "common" && $grade_type_data["is_show_verified"] == "Yes")
					{
						if($this->session->userdata('role_id') == '1' || in_array("update_grade",$this->arrAction))
						{
					?>
							<th align="left" <?php echo $rowspan;?> valign="top">Verified</th>
					<?php
						}
						if($this->session->userdata('role_id') == '1' || in_array("lead_update_grade",$this->arrAction))
						{
							if($this->session->userdata('role_id') != '1')
							{
					?>		
							<th align="left" <?php echo $rowspan;?> valign="top">Verified</th>
					<?php
							}
					?>		
							<th align="left" <?php echo $rowspan;?> valign="top">LT.Verify</th>
					<?php
						}
					}
					if($grade_type_data["attendance_type"] == "common")
					{
					?>
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('sub_att_p_field_attendance'); ?></th>
					<?php
					}
					if($grade_type_data["show_total_marks"] == "Yes")
					{
					?>
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('sub_att_p_field_total_marks'); ?>(<?php echo $grade_type_data["total_markes"];?>)</th>
					<?php
					}
					if($grade_type_data["show_grade_range"] == "Y")
					{
					?>
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('sub_att_p_field_range'); ?></th>
					<?php
					}
					if($grade_type_data["show_total_per"] == "Yes")
					{
					?>
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('total'); ?> %(<?php echo $grade_type_data["total_percentage"];?>)</th>
					<?php
					}
					if($hide_final_total == 0)
					{
					?>
					<th align="left" <?php echo $rowspan;?> valign="top"><?php echo $this->lang->line('total'); ?> %(100)</th>
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
					<th><?php echo $this->lang->line('marks'); ?> 1 </th>
					<th><?php echo $this->lang->line('marks'); ?> 2 </th>
					<th><?php echo $this->lang->line('marks'); ?> 3(<?php echo $this->lang->line('optional'); ?>) </th>
					<th><?php echo $this->lang->line('marks'); ?>(<?php echo $grade_type_exam_data["exam_marks"]; ?>) </th>
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
					?>					
					<?php 
					}
					?>
				
				</tr>
			<?php
				}
				$l = 0;
				$trcnt = 1;
				foreach ($course_classes->student as $student_datas)
				{
					$trbg = "";
					if($trcnt%2 == 0)
					{
						 $trbg = 'style="background-color:#fff"';
					}
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
											$temp_exam_mark = ($temp_exam_mark_1+$temp_exam_mark_2+$temp_exam_mark_3)/3;
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
							$total_percentage += $temp_percentage;	
						}	
					}
					if($student_datas->academic_status == 'Withdrawn' || $student_datas->academic_status == 'Denied ') {
						$trbg = 'style="background-color:rgb(241, 116, 116)"';
					}
					
			?>		
				<tr <?php echo $trbg;?>>
					<td>
					<?php
					$logcnt = 0;
					if(isset($course_classes->student_grade_data_log[$grade_type_id][$course_classes->section_id][$student_datas->student_uni_id]["cnt"]))
						$logcnt = $course_classes->student_grade_data_log[$grade_type_id][$course_classes->section_id][$student_datas->student_uni_id]["cnt"];	
					if($logcnt > 0)
					{
					?>
						<?php echo $student_datas->student_uni_id; ?>
					<?php
					}
					else 
					{
						echo $student_datas->student_uni_id;
					}
					?>
					</td>
					<td><?php echo $student_datas->first_name; ?></td>
					<?php 
					$total_exam_mark = 0;
					
					$k = 1;
					$exam_status = "";
					$verified = "No";
					$grade_status_combination = "";
					foreach ($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data)
					{
						$exam_status = "";
						$verified = "No";
						$lead_verified = "No";
						$grade_entry_combination_3 = 'grade3['.$course_classes->section_id."_".$student_datas->student_uni_id."_".$grade_type_exam_id.']';
						$grade_entry_combination_2 = 'grade2['.$course_classes->section_id."_".$student_datas->student_uni_id."_".$grade_type_exam_id.']';
						$grade_entry_combination = 'grade['.$course_classes->section_id."_".$student_datas->student_uni_id."_".$grade_type_exam_id.']';
						$grade_examwisestatus_combination = 'grade_status['.$course_classes->section_id."_".$student_datas->student_uni_id."_".$grade_type_exam_id.']';
						$grade_examwiseverify_combination = 'grade_verify['.$course_classes->section_id."_".$student_datas->student_uni_id."_".$grade_type_exam_id.']';
						$grade_examwiseleadverify_combination = 'grade_lead_verify['.$course_classes->section_id."_".$student_datas->student_uni_id."_".$grade_type_exam_id.']';
						$grade_status_combination = 'grade_status['.$course_classes->section_id."_".$student_datas->student_uni_id."_".$grade_type_id.']';
						$grade_verify_combination = 'grade_verify['.$course_classes->section_id."_".$student_datas->student_uni_id."_".$grade_type_exam_id.']';
						$grade_lead_verify_combination = 'grade_lead_verify['.$course_classes->section_id."_".$student_datas->student_uni_id."_".$grade_type_exam_id.']';
							
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
								
						if($grade_type_data["attendance_type"] == "examwise")
						{
					?>	
						<td align="center" <?php echo $bgcolortd_status_cheat;?>>
							<?php 
							$arr_exam_status = array(""=>"Select","Present"=>"Present","Absent"=>"Absent","Cheating"=>"Cheating","Makeup"=>"Makeup","IELTS"=>"IELTS");
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
							<?php	
							//}
							?>
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
							echo "&nbsp;";
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
									echo $exam_mark."&nbsp;";
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
								$arr_exam_status = array(""=>"Select","Present"=>"Present","Absent"=>"Absent","Cheating"=>"Cheating","Makeup"=>"Makeup","IELTS"=>"IELTS");
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
							
							<td align="center">
								<?php
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
						}
						
						if($grade_type_data["verification_type"] == "examwise" && $grade_type_data["is_show_verified"] == "Yes")
						{
							$arr_varified = array("No"=>"No","Correct"=>"Correct","Incorrect"=>"Incorrect","Cant Verify"=>"Can't Verify");
							if($this->session->userdata('role_id') == '1' || in_array("update_grade",$this->arrAction))
							{
								if($editable == 0)
								{
					?>	
								<td align="center">
						<?php
								print form_dropdown($grade_examwiseverify_combination,$arr_varified,$verified,'id="reg_name_suffix" class="input_text1 qtip_name_suffix"');	
						?>	
								</td>
						<?php
								}
								else
								{
						?>		
								<td align="center">
						<?php	
									echo str_replace("Cant","Can't",$verified)."&nbsp;";
						?>
								</td>
						<?php	
								}
							}
							if($this->session->userdata('role_id') == '1' || in_array("lead_update_grade",$this->arrAction))
							{
								if($this->session->userdata('role_id') != '1')
								{
						?>
								<td align="center">
						<?php	
									echo str_replace("Cant","Can't",$verified)."&nbsp;";
						?>
								</td>
						<?php
								}
						?>	
								<td align="center">
						<?php
								print form_dropdown($grade_examwiseleadverify_combination,$arr_varified,$lead_verified,'id="reg_name_suffix" class="input_text1 qtip_name_suffix"');
						?>	
								</td>
						<?php
							}
						}
						$k++;
					}
					
					if($grade_type_data["verification_type"] == "common" && $grade_type_data["is_show_verified"] == "Yes")
					{
						$arr_varified = array("No"=>"No","Correct"=>"Correct","Incorrect"=>"Incorrect","Cant Verify"=>"Can't Verify");
						if($this->session->userdata('role_id') == '1' || in_array("update_grade",$this->arrAction))
						{
							if($editable == 0)
							{
				?>	
							<td align="center">
					<?php
							print form_dropdown($grade_verify_combination,$arr_varified,$verified,'id="reg_name_suffix" class="input_text1 qtip_name_suffix"');	
					?>	
							</td>
					<?php
							}
							else
							{
							?>
								<td align="center">
						<?php	
									echo str_replace("Cant","Can't",$verified)."&nbsp;";
						?>
								</td>
						<?php	
							}
						}
						if($this->session->userdata('role_id') == '1' || in_array("lead_update_grade",$this->arrAction))
						{
							if($this->session->userdata('role_id') != '1')
							{
					?>
							<td align="center">
						<?php	
								echo str_replace("Cant","Can't",$verified)."&nbsp;";
					?>
							</td>
					<?php
							}
					?>	
							<td align="center">
					<?php
							print form_dropdown($grade_lead_verify_combination,$arr_varified,$lead_verified,'id="reg_name_suffix" class="input_text1 qtip_name_suffix"');
					?>	
							</td>
					<?php
						}
					}
					
					if($grade_type_data["attendance_type"] == "common")
					{
					?>
					<td align="center">
						<?php 
						$arr_exam_status = array(""=>"Select","Present"=>"Present","Absent"=>"Absent","Cheating"=>"Cheating","Makeup"=>"Makeup","IELTS"=>"IELTS");
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
					<td><?php echo $range_name; ?></td>
					<?php
					}
					if($grade_type_data["show_total_per"] == "Yes")
					{
					?>
					<td><?php echo round(($total_exam_mark*$grade_type_data["total_percentage"])/$grade_type_data["total_markes"],2); ?></td>
					<?php
					}
					if($hide_final_total == 0)
					{
					?>
					<td><?php echo round($total_percentage,2); ?></td>
					<?php
					}
					?>
				</tr>
				<?php
				$l++;
				$trcnt++;
				
				$arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["student_uni_id"] = $student_datas->student_uni_id;
				$arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["first_name"] = $student_datas->first_name;
				$arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["total_marks"] = round($total_exam_mark,1);
				$arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["total_perc"] = round(($total_exam_mark*$grade_type_data["total_percentage"])/$grade_type_data["total_markes"],2);
				$arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["total_100_perc"] = round($total_percentage,2);
				}
				?>
			</table>  
            <?php 
			if($this->session->userdata('role_id') == '1' || in_array("Verification",$this->arrAction))
			{
			?>  

			<div class="submit-btn">
			<?php print form_submit(array('name' => 'update'.$j, 'class' => 'submit_button btn btn-primary', 'title' => 'update', 'value' => 'Submit')); ?>
			</div>
			<?php 
			}
			print form_hidden('section_id', $course_classes->section_id);
			print form_close() ."\r\n"; 
			
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
                <div id="tabs_<?php echo $tabcnt;?>_1111">
		<div style="" id="col3">
		<table border="1" cellpadding="5" cellspacing="0" width="1055px" class="table table-bordered no-more-tables">
			<tr>
				<th align="left" valign="top"><?php echo $this->lang->line('sub_att_p_field_stuid'); ?></th>
				<th align="left" valign="top"><?php echo $this->lang->line('sub_att_p_field_fullname'); ?></th>
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
					<th align="left" valign="top"><?php echo $this->lang->line('range'); ?></th>
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
				<th align="left" valign="top"><?php echo $this->lang->line('total'); ?> 100%</th>
				<?php
				if($show_grade_range == "Yes")
				{
				?>
				<th align="left" valign="top"><?php echo $this->lang->line('range'); ?></th>
				<?php
				}
				?>
			</tr>
			<?php
			$trcnttotal = 1;
			foreach ($course_classes->student as $student_datas)
			{
				$trbg = "";
				if($trcnttotal%2 == 0)
				{
					 $trbg = 'style="background-color:#fff"';
				}
				$trcnttotal++;
			?>
				<tr <?php echo $trbg;?>>	
					<td><?php echo $student_datas->student_uni_id; ?></td>
					<td><?php echo $student_datas->first_name; ?></td>
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
			?>
		</table>	
		</div>
	</div>
    			<?php
				}
				?>	
                </div>
                 <!--tab end-->
			  </div>
			</div>
            </div>
         <?php	
		$tabcnt++;
		endforeach;
		}
		?>   
        </div>
        <!--accordion div end-->

        </div>
      </div>
    </div>
    <!--accordion-typ1 end-->     
    <!--Pagination start -->
    <div class="row">
    	<div class="col-md-12 top-pgination">
    	<div class="dataTables_paginate paging_bootstrap pagination">
		  <?php
        print $this->pagination->create_links();
        ?>
        </div>
        <div class="dataTables_info">
        	<?php print "Total rows: ". $total_rows; ?>
        </div>
        </div>
    </div>    
   <!--Pagination end --> 
    </div>  <!--dataTables_wrapper end--> 
	</div>   <!--grid-body end--> 
    </div>  <!--grid simple end-->  
</div>
</div>
<div class="clearboth"></div>	  
