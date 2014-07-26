<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script>
$(document).ready(function() {	
	<?php 
	$j = 1;
	if (isset($course_class)) {
		foreach ($course_class->result() as $course_classes){
		$week_max_hours = $course_classes->week_max_hours;
	?>
		
		$("#add_attendance_form<?php echo $j;?>").validate( {
			submitHandler: function(form) {
				var flag = false;
				var totalstu = '<?php echo count($course_classes->student); ?>';
				var submitweekno = $("#submitweekno").val();
				$('#add_attendance_form<?php echo $j;?> input[type="text"]').each(function(){
					
					for (var i = 0;i < totalstu; i++){
						var inputid = "attendance"+i+"_";
						inputid = inputid + submitweekno;
						if(this.id == inputid){
							if(this.value == '' || (this.value < 0 || this.value > <?php echo $week_max_hours;?>)){								
								flag = true;	
							}		
						}
					}						
						
				});
				
				if(flag == true){
					alert("Please enter week absent >= 0 and <= <?php echo $week_max_hours;?>");
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
	<div class="modal-dialog" style="width:800px;">
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
<input type="hidden" name="submitweekno" value="" id="submitweekno" />
<div class="attendnc-pg">

  
  <div id="admin">
    <?php $this->load->view('generic/flash_error'); ?>
    <label class="error" id="chkerror" style="display:none;"></label>
    <?php
		if($this->session->userdata('role_id') != '3')
		{
		?>
    <div id="searchbox" class="attendance_search_box dataTables_wrapper">
    <div class="row"> <div class="col-md-12">
      <?php
        print form_open('attendance/index/course_class_id/asc/post/0') ."\r\n";
        ?>
        <div class="row form-row">
              <div class="col-md-4">
              <label class="form-label">
			  <?php echo $this->lang->line('sub_att_p_sea_section') ?>
              </label>
              	<?php print form_input(array('name' => 'section_title', 'id' => 'section_title', 'class' => 'admin_input form-control','placeholder'=>$this->lang->line('sub_att_p_sea_section'),'value'=>$this->session->userdata('s_section_title'))); ?>
              </div>
              <div class="col-md-4">
              <label class="form-label">
			  <?php echo $this->lang->line('sub_att_p_sea_class_room') ?>
              </label>
              	<?php print form_input(array('name' => 'class_room_title', 'id' => 'class_room_title', 'class' => 'admin_input form-control','placeholder'=>$this->lang->line('sub_att_p_sea_class_room'),'value'=>$this->session->userdata('s_class_room_title'))); ?>
              </div>
              <div class="col-md-4">
              <label class="form-label">
			  <?php echo $this->lang->line('sub_att_p_field_stuid') ?>
              </label>
              	<?php print form_input(array('name' => 'student_id', 'id' => 'student_id', 'class' => 'admin_input form-control','placeholder'=>$this->lang->line('sub_att_p_field_stuid'),'value'=>$this->session->userdata('s_student_id'))); ?>
              </div>
              <div class="col-md-4">
              	<?php print form_submit(array('name' => 'add_attendance_search_submit', 'id' => 'add_attendance_search_submit', 'value' => 'Search', 'class' => 'input_submit btn btn-success')) ."\r\n"; ?></div>
        <?php print form_close() ."\r\n";
		//print "Total rows: ". $total_rows;
        ?> 
              </div>
              <?php
		}
		?>
        </div></div>     
 </div>
    
    <div class="grid simple">
    <div class="grid-title"><h4><?php echo $this->lang->line('sub_att_p_heading'); ?></h4></div>
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
			if($this->session->userdata('role_id') != '3')
			{
			?></span>
               <span class="col-md-2"><?php echo $this->lang->line('sub_att_p_list_hd_pri_teacher'); ?></span>
      <?php
			}
			?></span>
               <span class="col-md-1"><?php echo $this->lang->line('sub_att_p_list_hd_tot_stud'); ?></span>
      <?php
			if($this->session->userdata('role_id') != '3')
			{
			?></span>
               <span class="col-md-2"><?php echo $this->lang->line('campus'); ?></span>
               <span class="col-md-1"><?php echo $this->lang->line('course_class_p_shift'); ?></span>
                <?php
			}
		?>
             </div>  
        </div>
		<div class="panel-group" id="accordion" data-toggle="collapse">
        <?php
		$j = 1;
		if (isset($course_class)) {
			
		foreach ($course_class->result() as $course_classes):
		$course_class_week = $course_classes->school_week;
		$max_hours = $course_classes->max_hours;
		?>
		  <div class="panel panel-default">
			<div class="panel-heading">
			  <h4 class="panel-title">
				<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $j; ?>">
                <span class=" row">
				   <span class="col-md-2"><?php if($course_classes->course_title){echo $course_classes->course_title;}else{echo '&nbsp;';} ?></span>
                   <span class="col-md-2"><?php if($course_classes->section_title){echo $course_classes->section_title;}else{echo '&nbsp;';} ?></span>
                   <span class="col-md-2"><?php if($course_classes->class_room_title){echo $course_classes->class_room_title;}else{echo '&nbsp;&mdash;&nbsp;';} ?></span>
                   <?php
			if($this->session->userdata('role_id') != '3')
			{
			?>
                   <span class="col-md-2"><?php if($course_classes->first_name){echo $course_classes->first_name;}else{echo '&nbsp;';} ?></span>
                   <?php
			}
			?>
                   <span class="col-md-1"><?php if(isset($course_classes->student)){echo count($course_classes->student);}else{echo '0';} ?></span>
                   <?php
			if($this->session->userdata('role_id') != '3')
			{
			?>
                   <span class="col-md-2"><?php if($course_classes->campus){echo $course_classes->campus;}else{echo '&nbsp;';} ?></span>
                   
                   <span class="col-md-1"><?php if($course_classes->campus){echo $course_classes->courses_shift;}else{echo '&nbsp;';} ?></span>
                   <?php
			}
			?>
                 </span>  
				</a>
			  </h4>
			</div>
			<div id="collapse-<?php echo $j; ?>" class="panel-collapse collapse">
			  <div class="panel-body">
				 <?php
	if (isset($course_classes->student)) 
	{
	print form_open('attendance/add_attendance/', array('id' => 'add_attendance_form'.$j, 'class' => 'add_attendance_form')) ."\r\n";
	?>
        <table border="1" cellpadding="5" cellspacing="0" id="chnstyle" class="table table-bordered no-more-tables">
          <tr>
            <th align="left" width="1%">#</th>
            <th align="left" width="3%"><?php echo $this->lang->line('sub_att_p_field_stuid'); ?></th>
            <th align="left" width="27%"><?php echo $this->lang->line('sub_att_p_field_fullname'); ?></th>
            <th width="5%" align="center">Total<br />
              Hrs</th>
            <th align="left" width="5%"><?php echo $this->lang->line('sub_att_p_field_perc'); ?></th>
            <?php
			foreach ($course_classes->enable_week as $enable_wek){
			?>
            <th width="3%"><?php echo $this->lang->line('sub_att_p_field_wk'); ?><br />
              <?=$enable_wek->week_id?></th>
            <?php 
			}
			?>
          </tr>
          <?php
		$stcnt = 1;
		$l = 0;
		$trcnt = 1;
		foreach ($course_classes->student as $student_datas)
		{
			$trbg = "";
			if($trcnt%2 == 0)
			{
				 $trbg = 'style="background-color:#ddd"';
			}
	?>
          <tr <?php echo $trbg;?>>
            <td><?php echo $stcnt++;?></td>
            <td><?php
			if($student_datas->log_cnt > 0 && $this->session->userdata('role_id') != '3')
{
?>
              <a href="<?php print base_url() ?>attendance/view_attendance_log/<?php echo $student_datas->user_id;?>" data-target="#myModal" data-toggle="modal" style="text-decoration:underline; color:red;"><?php echo $student_datas->student_uni_id; ?></a>
              <?php
			}
			else 
			{
				echo $student_datas->student_uni_id;
			}
			?>
            </td>
            <td><?php echo ucfirst(strtolower($student_datas->first_name)); ?></td>
            <?php 
			$total_absent_hours = 0;
			$percentage = 0;
			$k = 1;
			foreach ($course_classes->enable_week as $enable_wek)
			{
				$absent_hours = "";
				if(isset($course_classes->student_attendance_data[$student_datas->user_id]) && isset($course_classes->student_attendance_data[$student_datas->user_id][$enable_wek->week_id]))
				{
					$absent_hours = $course_classes->student_attendance_data[$student_datas->user_id][$enable_wek->week_id]["absent_hour"];
					$total_absent_hours += $absent_hours;
				}
				$k++;
			}
			?>
            <td align="center"><?php echo $total_absent_hours; ?></td>
            <td align="center"><?php if($max_hours > 0)echo round((($total_absent_hours*100)/$max_hours),2); ?></td>
            <?php 
			
			$total_absent_hours = 0;
			$percentage = 0;
			$k = 1;
			$tempWeekArr = array();
			foreach ($course_classes->enable_week as $enable_wek)
			{
				$attendance_entry_combination = 'attendance['.$course_classes->course_class_id."_".$student_datas->user_id."_".$enable_wek->week_id."_".$course_classes->school_year_id."_".$course_classes->school_id."_".$course_classes->primary_teacher_id.']';
			?>
            <?php 
				$editable = 0;
				$absent_hours = "";
				if(isset($course_classes->student_attendance_data[$student_datas->user_id]) && isset($course_classes->student_attendance_data[$student_datas->user_id][$enable_wek->week_id]))
				{
					$absent_hours = $course_classes->student_attendance_data[$student_datas->user_id][$enable_wek->week_id]["absent_hour"];
					
					$total_absent_hours += $absent_hours;
					if($this->session->userdata('role_id') == '3')
						$editable = $course_classes->student_attendance_data[$student_datas->user_id][$enable_wek->week_id]["editable"];
				}	
				$readonly = "";
				if($editable == 1)
				{
				?>
            <td align="center"><div>
                <?php
						print (int)$absent_hours;
				?>
              </div></td>
            <?php	
				}	
				else
				{	
					echo '<td align="center">';
					if($this->session->userdata('role_id') == '1' || in_array("add_attendance",$this->arrAction))
					{
						$tempWeekArr[] = $enable_wek->week_id;
						print form_input(array('name' => $attendance_entry_combination , 'id' => 'attendance'.$l.'_'.$enable_wek->week_id, 'value'=>$absent_hours,'class' => 'admin_input','size'=>'3',$readonly=>$readonly,'onKeyPress'=>'return stop_keypress_numaric(event)'));
					}
					else 
					{
						echo $absent_hours."&nbsp;";
					}
					echo '</td>';
				}
				?>
            <?php
			$k++;
			}
			?>
          </tr>
          <?php
		
		$l++;
		$trcnt++;
		}
		?>
          <?php 
		if($this->session->userdata('role_id') == '1' || in_array("add_attendance",$this->arrAction))
		{
		?>
          <tr>
            <td colspan="5">&nbsp;</td>
            <?php 
			foreach ($course_classes->enable_week as $enable_wek){
				if(in_array($enable_wek->week_id, $tempWeekArr)){
			?>
            <td align="center" class="atnd-save"><?php print form_submit(array('name' => 'update_'.$enable_wek->week_id, 'class' => 'submit_button btn btn-primary', 'title' => 'update','style'=>'padding:4px 6px', 'value' => 'Save', 'onclick' => 'getSubmitWeekNo('.$enable_wek->week_id.');')); ?>
           <!--<button name="<?php echo 'update_'.$enable_wek->week_id ?>" onclick="getSubmitWeekNo(<?php echo $enable_wek->week_id ?>);"  class="btn btn-primary" type="submit" style="padding:6px 8px"><i class="fa fa-save"></i></button>--> </td>
            <?php 
				}else{
			?>
            <td align="right" style="background-color:#C9C9C9;">&nbsp;</td>
            <?php 		
				}
			}		
			?>
          </tr>
          <?php
		} 
		?>
        </table>
        <?php 
	print form_hidden('course_class_id', $course_classes->course_class_id);
	print form_close() ."\r\n"; 
	
    $j++;
	}
	else
	{
	?>
        No Student found in this class.Please ask to administrator.
        <?php
	}
	?>
			  </div>
			</div>
		  </div>
         <?php	
			endforeach;
			}
			?> 
		</div>

        </div>
      </div>
    </div>
    <!--accordion-typ1 end-->
    
    
    <!--Pagination start -->
    <div class="row">
    	<div class="col-md-12">
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
   </div></div><!--dataTables_wrapper end-->  
    
  </div>
  </div>
</div>
<div class="clearboth"></div>
