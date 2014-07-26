<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript" src="<?php print base_url(); ?>js/grid/list_report.js?t=123"></script>
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
<div class="row-fluid">
	<div class="span12">
		<div class="grid simple ">
			<div class="grid-title">
			  <h4><?php echo $this->lang->line('log_p_heading'); ?></h4>
			</div>
			<div class="grid-body ">    
			<table class="table" id="grid_report">
				<thead>
					<tr>
						<th><?php echo $this->lang->line('log_p_dataid'); ?></th>
						<th><?php echo $this->lang->line('log_p_user'); ?></th>
						<th><?php echo $this->lang->line('log_p_parent_menu'); ?></th>
						<th><?php echo $this->lang->line('log_p_sub_menu'); ?></th>
						<th><?php echo $this->lang->line('log_p_action'); ?></th>
						<th><?php echo $this->lang->line('log_p_user_ip'); ?></th>
						<th><?php echo $this->lang->line('log_p_date'); ?></th>
						<th>View Data</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?php echo $this->lang->line('log_p_dataid'); ?></th>
						<th><?php echo $this->lang->line('log_p_user'); ?></th>
						<th><?php echo $this->lang->line('log_p_parent_menu'); ?></th>
						<th><?php echo $this->lang->line('log_p_sub_menu'); ?></th>
						<th><?php echo $this->lang->line('log_p_action'); ?></th>
						<th><?php echo $this->lang->line('log_p_user_ip'); ?></th>
						<th><?php echo $this->lang->line('log_p_date'); ?></th>
						<th>View Data</th>
					</tr>
				</tfoot>
				<tbody>
				
				</tbody>
			</table>
			</div>
		</div>
	</div>
</div>