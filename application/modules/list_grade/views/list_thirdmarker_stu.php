<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<script>
$(document).ready( function () {
	dTable = $('#grid_student').dataTable({
		bJQueryUI:false,
		bProcessing:true,
		bServerSide: true,
		sAjaxSource: "<?php echo base_url();?>list_grade/list_thirdmarker_stu",
		aoColumns: [
		            	null , 
		            	null , 
		            	null , 
		            	null , 
		            	null 
		           ],
		sPaginationType: "bootstrap"})
});
</script>
<div class="modal-header">
  <h2>List Student Without 3rd mark</h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button><br />
</div>
<div class="modal-body">

    <table cellpadding="0" cellspacing="0" border="0" class="table" id="grid_student">
      <thead>
        <tr>
          <th><?php print $this->lang->line('student_p_stud_id'); ?></th>
          <th><?php print $this->lang->line('student_p_section'); ?></th>
          <th>Campus</th>
          <th><?php print $this->lang->line('student_p_full_name'); ?></th>
          <th>Grade Exam Type</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th><?php print $this->lang->line('student_p_stud_id'); ?></th>
          <th><?php print $this->lang->line('student_p_section'); ?></th>
          <th>Campus</th>
          <th><?php print $this->lang->line('student_p_full_name'); ?></th>
          <th>Grade Exam Type</th>
        </tr>
      </tfoot>
      <tbody>
      </tbody>
    </table>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>