<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript" src="<?php print base_url(); ?>js/grid/user_qualification.js?t=newv"></script>
<div id="admin">
<div class="row-fluid">
	<div class="span12">
		<div class="grid simple ">
			<div class="grid-title">
			  <h4>User qualification</h4>
			</div>
			<div class="grid-body ">
			<div id="processing_message" style="display:none" title="Processing">Please wait while your request is being processed...</div>
			<table class="table" id="grid_other_user">
			<thead>
				<tr>
					<th rowspan="2">DB ID</th>
					<th rowspan="2">ELSD ID</th>
					<th rowspan="2">Staff Name</th>
					<th rowspan="2">Company</th>
                    <th rowspan="2">Campus</th>
					<th rowspan="2">Status</th>
                    <th rowspan="2">Nationality</th>
                    <?php 
					if(count($dt_columns) > 0){
						foreach($dt_columns as $dt_column) {
							echo '<th colspan="2">'.$dt_column.'</th>';
						}
					}
					?>
                </tr>  
                <tr><?php
					$js_dt_columns = '';
					$js_dt_columns_filter = '';
					if(count($dt_columns) > 0){
						foreach($dt_columns as $dt_column) {
							$js_dt_columns .= '{"sName": "ID","bSearchable": false,"bSortable": false},';
							$js_dt_columns .= '{"sName": "ID","bSearchable": false,"bSortable": false},';
							$js_dt_columns_filter .= 'null,';
							$js_dt_columns_filter .= 'null,';
							echo '<th>Subject</th>';
							echo '<th>Subject related</th>';
						}
					}
					?>
                </tr>
			</thead>
			<tfoot>
				<tr>
					<th>DB ID</th>
					<th>ELSD ID</th>
					<th>Staff Name</th>
					<th>Company</th>
                    <th>Campus</th>
					<th>Status</th>
                    <th>Nationality</th>
                    <?php 
					if(count($dt_columns) > 0){
						foreach($dt_columns as $dt_column) {
							echo '<th colspan="2">'.$dt_column.'</th>';
						}
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
<script language="javascript">
var js_dt_columns = [
						null ,
						{"sName": "elsd_id"},
						{"sName": "staff_name"},
						{"sName": "contractor"},
						{"sName": "campus"},
						{"sName": "status"},
						{"sName": "nationality"},
						<?=$js_dt_columns?>
		           ];
var js_dt_columns_filter = [    
								 null,
								 { type: "text" },
								 { type: "text" },
								 { type: "text" },
								 { type: "text" },
								 { type: "text" },
								 { type: "text" },
								 <?=$js_dt_columns_filter?>
							];
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