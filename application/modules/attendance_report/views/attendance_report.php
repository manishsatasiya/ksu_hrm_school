<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="row-fluid">
	<div class="span12">
		<div class="grid simple ">
			<div class="grid-title">
			  <h4><?php echo $this->lang->line('att_rep_p_list_heading'); ?></h4>
              <div class="export_to_excel">
                    <form id="export_file" action="attendance_report/export_to_excel" target="download_iframe" method="POST">
                    	<div class="col-md-8"></div>
                        <div class="col-md-4">
                        	<input type="submit" name="submit" value="Export To XLS" class="btn btn-info">
                        </div>
                    </form>
                </div>
			</div>
			<div class="grid-body ">
			<div id="processing_message" style="display:none" title="Processing">Please wait while your request is being processed...</div>
			<table class="table" id="grid_attendance_report">
			<thead>
				<tr>
					<th><?php echo $this->lang->line('att_rep_p_list_studid'); ?></th>
					<th><?php echo $this->lang->line('att_rep_p_list_stud_name'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_sche_date'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_list_section'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_list_class_room'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_list_course_name'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_list_shift'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_track'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_list_campus'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_list_teacher_name'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_list_per'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_list_tot_abs_hours'); ?></th>
                    <?php
					foreach ($enable_week as $enable_wek){
					?>
						<th><?php echo $this->lang->line('att_rep_p_list_week'); ?> <?=$enable_wek->week_id?></th>
					<?php 
					}
					?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th><?php echo $this->lang->line('att_rep_p_list_studid'); ?></th>
					<th><?php echo $this->lang->line('att_rep_p_list_stud_name'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_sche_date'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_list_section'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_list_class_room'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_list_course_name'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_list_shift'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_track'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_list_campus'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_list_teacher_name'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_list_per'); ?></th>
                    <th><?php echo $this->lang->line('att_rep_p_list_tot_abs_hours'); ?></th>
                    <?php
					foreach ($enable_week as $enable_wek){
					?>
						<th><?php echo $this->lang->line('att_rep_p_list_week'); ?> <?=$enable_wek->week_id?></th>
					<?php 
					}
					?>
				</tr>
			</tfoot>
			<tbody>
			
			</tbody>
		</table>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
dTable = $('#grid_attendance_report').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "attendance_report/getdata",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			//"aiExclude": [table_total_col-1]
        },
		aoColumns: [
						{"sName": "student_uni_id"},
		            	{"sName": "student_name","bSearchable": false,"bSortable": false},
		            	{"sName": "stu_schedule_date","bSearchable": false,"bSortable": false},
		            	{"sName": "section_title"},
		            	{"sName": "class_room_title"},
		            	{"sName": "course_title"},
						{"sName": "shift","bSearchable": false,"bSortable": false},
						{"sName": "track","bSearchable": false,"bSortable": false},
						{"sName": "campus"},
						{"sName": "teacher_name"},
						{"sName": "attendance_perc","bSearchable": false,"bSortable": false},
						{"sName": "absent_hour","bSearchable": false,"bSortable": false},
						<?php
						foreach ($enable_week as $enable_wek){
						?>
							{"sName": "week_<?=$enable_wek->week_id?>","bSearchable": false,"bSortable": false},
						<?php 
						}
						?>		            	
		           ],
		sPaginationType: "bootstrap"});
});
</script>