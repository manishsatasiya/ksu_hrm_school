<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
	
<script type="text/javascript" src="<?php print base_url(); ?>js/grid/contracted_summary.js"></script>

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

<div class="row-fluid">
	<div class="span12">
		<div class="grid simple ">
			<div class="grid-title">
			  <h4>Contracted summary</h4>
			</div>
			<div class="grid-body ">
			<div id="processing_message" class="hide" title="Processing">Please wait while your request is being processed...</div>
			<table class="table" id="grid_contracted_summary">
				<thead>
					<tr>
						<th style="width:77px">ID</th>
						<th>Contractor</th>
                        <th>Campus</th>
                        <th>Role</th>
						<th>Contracted numbers</th>
						<th>Actual total number</th>
                        <th>Shortfall</th>
						<th style="width:150px">Updated Date</th>
					</tr>
				</thead>
				<tbody></tbody>
				<tfoot>
					<tr>
						<th style="width:77px">ID</th>
						<th>Contractor</th>
                        <th>Campus</th>
                        <th>Role</th>
						<th>Contracted numbers</th>
						<th>Actual total number</th>
                        <th>Shortfall</th>
						<th style="width:150px">Updated Date</th>
					</tr>
				</tfoot>
			</table>
			</div>
		</div>
	</div>
</div>