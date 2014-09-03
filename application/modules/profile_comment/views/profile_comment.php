<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

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
<script type="text/javascript" src="<?php print base_url(); ?>js/grid/profile_comment.js"></script>

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
			  <h4>Comments</h4>
			</div>
			<div class="grid-body ">
			 
              <table class="table" id="grid_profile_comment">
				<thead>
                  <tr>
					<th>ID</th>
					<th>Staff Name</th>
                    <th>Note type</th>
					<th>Department</th>
					<th>Recommended </th>
                    <th>Detail</th>
					<th>Created by</th>
					<th>Date</th>
                    <th>Action</th>
				</tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>ID</th>
					<th>Staff Name</th>
                    <th>Note type</th>
					<th>Department</th>
					<th>Recommended </th>
                    <th>Detail</th>
					<th>Created by</th>
					<th>Date</th>
                    <th>Action</th>
                  </tr>
                </tfoot>
                <tbody></tbody>
              </table>
			</div>
		</div>
	</div>
</div>  
