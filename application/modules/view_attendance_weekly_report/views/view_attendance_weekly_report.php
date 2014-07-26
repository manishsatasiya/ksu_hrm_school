<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="view_attendance_weekly_report">
    <div id="admin">
        <?php $this->load->view('generic/flash_error'); ?>
        <!--search box start-->
        <div id="searchbox" class="ksu_search_box dataTables_wrapper">
        <?php /*?><div align="center"><strong><?php echo $this->lang->line('attendance_weekly_report_p_search'); ?></strong></div><?php */?>
    		<div class="row">
        	<div class="col-md-12">
			<?php
        	print form_open('view_attendance_weekly_report/index');
        	?> 
        	<div class="row form-row">
              <div class="col-md-4">
              <label class="form-label">
			  <?php echo $this->lang->line('attendance_weekly_report_p_school_year'); ?>
              </label>                
                <?php print form_dropdown('school_year_id',$school_year_list,($this->input->post('school_year_id'))?$this->input->post('school_year_id'):$school_year_id,'id="reg_school_year_id" class="input_text1 qtip_school_year_id" onchange="getWeek(this.value)"'); ?>
              </div>
              <div class="col-md-4">
              <label class="form-label">
			  <?php echo $this->lang->line('campus'); ?>
              </label>                
                <?php print form_dropdown('campus_id',$campus_list,($this->input->post('campus_id'))?$this->input->post('campus_id'):$campus_id,'id="reg_campus_id" class="input_text1 qtip_campus_id"'); ?>
              </div>
              <div class="col-md-4">
              <span id="tdweek">
              <label class="form-label">
			  <?php echo $this->lang->line('attendance_weekly_report_p_week'); ?>
              </label>
			  		<select class="input_text1" name="week" id="week">
			  				<option value="0">--<?php echo $this->lang->line('attendance_weekly_report_p_select'); ?>--</option>
						<?php
						foreach($school_week AS $rowschoolweek)
						{
						?>
							<option value="<?php echo $rowschoolweek["week_id"]; ?>" <?php if($this->input->post('week') == $rowschoolweek["week_id"] || $rowschoolweek["week_id"] == '1'){ echo 'selected="selected"';}?>><?php echo "week ".$rowschoolweek["week_id"];?></option>
						<?php
						}
						?>
					</select>
					</span>
              </div>
              <div class="col-md-12">
              <?php print form_submit(array('name' => 'submit', 'id' => 'submit', 'value' => 'Submit', 'class' => 'input_submit btn btn-success')) ."\r\n"; ?>
			  <?php print form_button('mysubmit', 'Export', 'onclick="export_excel()" class="input_submit btn btn-primary"'); ?>
			  <?php 
				if($this->session->userdata('role_id') == '1' || in_array("generatereport",$this->arrAction))
				print form_button('mysubmit', 'Generate Report', 'onclick="generate_report()" class="input_submit btn btn-info"'); 
				?>  
              	</div>
        	<?php 
			print form_close();
			?>   
              </div>
              
        </div>
        </div>     
 		</div>
 		<!--search box end-->
        <!--grid simple start--> 
                
        <div class="grid simple">
        <div class="grid-title">
        	<h4><?php echo $this->lang->line('attendance_weekly_report_p_heading'); ?></h4>
        </div>
    	<div class="grid-body">
    	<div class="dataTables_wrapper"> <!--dataTables_wrapper start--> 
        
		<div id="accordion">   
			
			<div class="clear"></div>
			<table id="rounded-corner" width="100%">
				<tr>
					<th colspan="5"><?php echo $this->lang->line('attendance_weekly_report_p_absent'); ?></th>
					<th rowspan="2"><?php echo $this->lang->line('attendance_weekly_report_p_student_without_absent'); ?> </th>
					<th rowspan="2"><?php echo $this->lang->line('attendance_weekly_report_p_regularly_attending_students'); ?></th>
					<th rowspan="2"><?php echo $this->lang->line('attendance_weekly_report_p_discontinued_students'); ?></th>
					<th rowspan="2"><?php echo $this->lang->line('attendance_weekly_report_p_registered_students'); ?></th>
					<!--<th rowspan="2">&nbsp;</th>-->
					<th rowspan="2" colspan="2"><?php echo $this->lang->line('attendance_weekly_report_p_course'); ?></th>
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
					<td><?php echo (int)$datas[$type.'_without_absent_student']; ?></td>
					<td><?php echo $datas[$type.'_regular_student']; ?></td>
					<td><?php echo $datas[$type.'_discontinued_student']; ?></td>
					<td><?php echo $datas[$type.'_registered_student']; ?></td>
					<td><?php echo $this->lang->line('attendance_weekly_report_p_number'); ?></td>
					<td rowspan=2><?php echo $datas['course_title']; ?></td>
					
				</tr>
				<tr>
					<td><?php echo @round((($datas[$type.'_student_more_than_25_per']/$datas[$type.'_regular_student'])*100),2); ?>%</td>
					<td><?php echo @round((($datas[$type.'_student_between_20_25_per']/$datas[$type.'_regular_student'])*100),2); ?>%</td>
					<td><?php echo @round((($datas[$type.'_student_between_10_20_per']/$datas[$type.'_regular_student'])*100),2); ?>%</td>
					<td><?php echo @round((($datas[$type.'_student_between_10_15_per']/$datas[$type.'_regular_student'])*100),2); ?>%</td>
					<td><?php echo @round((($datas[$type.'_student_less_than_10_per']/$datas[$type.'_regular_student'])*100),2); ?>%</td>
					<td><?php echo @round((((int)$datas[$type.'_without_absent_student']/$datas[$type.'_regular_student'])*100),2); ?>%</td>
					<td><?php echo @round((($datas[$type.'_regular_student']/$datas[$type.'_registered_student'])*100),2); ?>%</td>
					<td><?php echo @round((($datas[$type.'_discontinued_student']/$datas[$type.'_registered_student'])*100),2); ?>%</td>
					<td>100%</td>
					<td><?php echo $this->lang->line('attendance_weekly_report_p_percentage'); ?></td>
					
					
				</tr>
				<?php
					} 
					}
				?>
				<?php
				} 
				?>
			</table>
		</div>
        
        </div></div><!--dataTables_wrapper end-->  
		  </div><!--grid simple end-->  
        
		<div class="clearboth"></div>
	</div>
</div>
<div class="clearboth"></div>