<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<script type="text/javascript">
var edit_flag = 1;
<?php 
if($this->session->userdata('role_id') != '1' && !in_array("edit",$this->arrAction))
{
?>
	edit_flag = 0;
<?php 
}
?>
</script>
<script type="text/javascript" src="<?php print base_url(); ?>js/grid/line_managers_list.js?t=newv"></script>
<div id="admin">
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Loading....</h2>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <br />
      </div>
      <div class="modal-body">
        <div style="text-align:center;"><i class="fa fa-spinner fa fa-6x fa-spin" id="animate-icon"></i></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    
    <!-- /.modal-content --> 
    
  </div>
  
  <!-- /.modal-dialog --> 
  
</div>
<!-- /.modal -->
<div class="row-fluid">
  <div class="span12">
    <div class="grid simple ">
      <div class="grid-title">
        <h4>Line managers list</h4>
      </div>
      <div class="grid-body ">
        <div id="processing_message" style="display:none" title="Processing">Please wait while your request is being processed...</div>
        <form action="" method="post">
        <table class="table" id="grid_line_managers_list">
          <thead>
            <tr>
              <th>DB ID</th>
              <th>Attendance</th>
              <th>Staff Name</th>
              <th>ELSD ID</th>
              <th>Job Title</th>
              <th>Personal E-mail</th>
              <th>Work E-mail</th>
              <th>Mobile</th>
              <th>Company</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>DB ID</th>
              <th>Attendance</th>
              <th>Staff Name</th>
              <th>ELSD ID</th>
              <th>Job Title</th>
              <th>Personal E-mail</th>
              <th>Work E-mail</th>
              <th>Mobile</th>
              <th>Company</th>
            </tr>
          </tfoot>
          <tbody>
          </tbody>
        </table>
        <?php
		if($show_submit){ ?>
        <input type="submit" name="submit" value="Submit" class="btn btn-warning" />
        <?php
		} ?>
			</form>
      </div>
    </div>
  </div>
</div>
<script language="javascript">
$(document).ready(function() {
	<?php
	if($this->session->userdata('role_id') != '1' && !in_array("edit",$this->arrAction))
	{
	?>
		//fnShowHide(8);
	<?php 
	}
	?>
});
</script> 