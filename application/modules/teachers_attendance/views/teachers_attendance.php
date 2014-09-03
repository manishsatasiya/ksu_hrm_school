<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row-fluid">
	<div class="span12">
		<div class="grid simple ">
			<div class="grid-title">
			  <h4><?php echo $this->lang->line('late_att_teachers_attendance_status_report'); ?></h4>
              <div class="export_to_excel">
                    <form id="export_file" action="teachers_attendance/export_to_excel" target="download_iframe" method="POST">
                    	<div class="col-md-8"></div>
                        <div class="col-md-4">
                        	<input type="submit" name="submit" value="Export To XLS" class="btn btn-info">
                        </div>
                    </form>
                </div>
			</div>
			<div class="grid-body ">
			<div id="processing_message" style="display:none" title="Processing">Please wait while your request is being processed...</div>
			<table class="table" id="grid_teachers_attendance">
			<thead>
				<tr>
					<th><?php echo $this->lang->line('late_att_p_list_elsd_id'); ?></th>
					<th><?php echo $this->lang->line('late_att_p_list_teacher'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_sec'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_course'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_shift'); ?></th>
                    <th><?php echo $this->lang->line('campus'); ?></th>
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
					<th><?php echo $this->lang->line('late_att_p_list_elsd_id'); ?></th>
					<th><?php echo $this->lang->line('late_att_p_list_teacher'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_sec'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_course'); ?></th>
                    <th><?php echo $this->lang->line('late_att_p_list_shift'); ?></th>
                    <th><?php echo $this->lang->line('campus'); ?></th>
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
dTable = $('#grid_teachers_attendance').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "teachers_attendance/getdata",
		"sDom": 'fCl<"clear">rtip',
		"oColVis": {
			//"aiExclude": [table_total_col-1]
        },
		aoColumns: [
						{"sName": "elsd_id"},
		            	{"sName": "first_name"},
		            	{"sName": "section_title"},
		            	{"sName": "course_title"},
		            	{"sName": "shift","bSearchable": false,"bSortable": false},
		            	{"sName": "campus","bSearchable": false,"bSortable": false},
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


$(document).ready(function() {
	fnShowHide(0);
	fnShowHide(2);
	fnShowHide(3);
	fnShowHide(4);
	fnShowHide(5);
});


function fnShowHide( iCol )
{
	/* Get the DataTables object again - this is not a recreation, just a get of the object */
	var dTable = $('#grid_teachers_attendance').dataTable();
	
	var bVis = dTable.fnSettings().aoColumns[iCol].bVisible;
	dTable.fnSetColumnVis( iCol, bVis ? false : true );
}
</script>
