<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row-fluid">
	<div class="span12">
		<div class="grid simple ">
			<div class="grid-title">
			  <h4><?php echo $this->lang->line('late_att_p_list_heading'); ?></h4>
              <div class="export_to_excel">
                    <form id="export_file" action="late_attendance/export_to_excel" target="download_iframe" method="POST">
                    	<div class="col-md-8"></div>
                        <div class="col-md-4">
                        	<input type="submit" name="submit" value="Export To XLS" class="btn btn-info">
                        </div>
                    </form>
                </div>
			</div>
			<div class="grid-body ">
			<div id="processing_message" style="display:none" title="Processing">Please wait while your request is being processed...</div>
			<table class="table" id="grid_late_attendance">
			<thead>
				<tr>
					<th><?php echo $this->lang->line('late_att_p_list_elsd_id'); ?></th>
					<th><?php echo $this->lang->line('late_att_p_list_teacher'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_sec'); ?></th>
                    <th><?php echo $this->lang->line('course_class_p_class_room'); ?></th>
                    <th><?php echo $this->lang->line('campus'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_course'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_week'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_shift'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_deadline'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_submitted_dt'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th><?php echo $this->lang->line('late_att_p_list_elsd_id'); ?></th>
					<th><?php echo $this->lang->line('late_att_p_list_teacher'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_sec'); ?></th>
                    <th><?php echo $this->lang->line('course_class_p_class_room'); ?></th>
                    <th><?php echo $this->lang->line('campus'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_course'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_week'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_shift'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_deadline'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_submitted_dt'); ?></th>
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
dTable = $('#grid_late_attendance').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "late_attendance/getdata",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			//"aiExclude": [table_total_col-1]
        },
		aoColumns: [
						{"sName": "elsd_id"},
		            	{"sName": "first_name"},
		            	{"sName": "section_title"},
		            	{"sName": "class_room_title","bSearchable": false,"bSortable": false},
		            	{"sName": "campus","bSearchable": false,"bSortable": false},
		            	{"sName": "course_title"},
						{"sName": "attendence_week"},
						{"sName": "shift","bSearchable": false,"bSortable": false},
						{"sName": "attendance_last_date","bSearchable": false,"bSortable": false},
						{"sName": "attendence_submitted_date","bSearchable": false,"bSortable": false}
		           ],
		sPaginationType: "bootstrap"});
});
</script>

